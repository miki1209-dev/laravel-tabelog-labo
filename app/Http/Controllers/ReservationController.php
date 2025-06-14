<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Shop;

class ReservationController extends Controller
{
	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'visit_date' => [
				'required',
				'date',
				'after:today',
				function ($_attr, $_val, $fail) use ($request) {
					$user_id = Auth::user()->id;
					$shop_id = $request->input('shop_id');
					$visit_date = $request->input('visit_date');
					$visit_time = $request->input('visit_time');

					$exists = Reservation::where('user_id', $user_id)
						->where('shop_id', $shop_id)
						->where('visit_date', $visit_date)
						->where('visit_time', $visit_time)
						->where('status', 'confirmed')
						->exists();

					if ($exists) {
						$fail('この日時の予約はすでに存在します。');
					}
				}
			],
			'visit_time' => ['required', 'date_format:H:i'],
			'number_of_people' => ['required', 'integer', 'min:1', 'max:15'],
		]);

		Log::debug($request->all());
		if ($validator->fails()) {
			return redirect()->route('shops.show', $request->input('shop_id'))->withErrors($validator, 'reservation')->withInput();
		}

		try {
			$reservation = new Reservation;
			$reservation->user_id = Auth::user()->id;
			$reservation->shop_id = $request->input('shop_id');
			$reservation->visit_date = $request->input('visit_date');
			$reservation->visit_time = $request->input('visit_time');
			$reservation->number_of_people = $request->input('number_of_people');
			$reservation->save();

			return redirect()->route('reservations.complete');
		} catch (QueryException $e) {
			Log::error('Database Error' . $e->getMessage());
			return back()->withErrors(['database_error' => 'データベースへの登録が失敗しました。時間をおいて再度試してみてください'])->withInput();
		} catch (Exception $e) {
			Log::error('General Error' . $e->getMessage());
			return back()->withErrors(['general_error' => '予期せぬエラーが発生しました'])->withInput();
		}
	}

	public function confirm(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'visit_date' => ['required', 'date', 'after:today'],
			'visit_time' => ['required', 'date_format:H:i'],
			'number_of_people' => ['required', 'integer', 'min:1', 'max:15'],
		]);

		if ($validator->fails()) {
			return back()->withErrors($validator, 'reservation')->withInput();
		}

		$reservation = new Reservation();
		$reservation->visit_date = $request->input('visit_date');
		$reservation->visit_time = $request->input('visit_time');
		$reservation->number_of_people = $request->input('number_of_people');
		$reservation->shop_id = $request->input('shop_id');
		$shop = Shop::find($reservation->shop_id);

		return view('reservations.confirm', compact('reservation', 'shop'));
	}

	public function complete()
	{
		return view('reservations.complete');
	}
}
