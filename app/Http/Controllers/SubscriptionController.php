<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
	public function store(Request $request)
	{

		$request->validate([
			'stripeToken' => 'required|string',
			'card_name' => 'required|string|max:255'
		]);

		/** @var \App\Models\User $user */
		$user = Auth::user();

		try {
			$user->createOrGetStripeCustomer([
				'name' => $request->input('card_name'),
				'email' => $user->email,
			]);
			$user->newSubscription('premium', config('cashier.plan_id'))->create($request->input('stripeToken'));

			return redirect()->route('mypage')->with('success', '有料会員登録が完了しました。');
		} catch (Exception $e) {
			Log::error('Payment Error' . $e->getMessage());
			return back()->withErrors(['stripe_error' => 'サブスクリプション登録に失敗しました。']);
		}
	}

	public function cancel(Request $request)
	{

		/** @var \App\Models\User $user */
		$user = Auth::user();

		try {
			if ($user->subscribed('premium')) {
				$user->subscription('premium')->cancel();
				return redirect()->route('mypage')->with('success', 'サブスクリプションを解約しました。');
			} else {
				return redirect()->route('mypage')->withErrors('サブスクリプションが見つかりませんでした');
			}
		} catch (Exception $e) {
			Log::error('Subscription Cancellation Error' . $e->getMessage());
			return back()->withErrors(['subscription_cancellation_error' => 'サブスクリプション解約に失敗しました。']);
		}
	}

	public function update(Request $request)
	{
		$request->validate([
			'stripeToken' => 'required|string',
			'card_name' => 'required|string|max:255'
		]);

		/** @var \App\Models\User $user */
		$user = Auth::user();

		try {
			$user->createOrGetStripeCustomer([
				'name' => $request->input('card_name')
			]);

			$user->updateDefaultPaymentMethod($request->input('stripeToken'));

			return redirect()->route('mypage')->with(['success' => 'お支払い情報を更新しました']);
		} catch (Exception $e) {
			Log::error('Payment Method Update Error' . $e->getMessage());
			return back()->withErrors(['stripe_error' => 'お支払い方法の更新に失敗しました。']);
		}
	}
}
