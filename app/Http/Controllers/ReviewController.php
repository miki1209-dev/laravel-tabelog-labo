<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ReviewController extends Controller
{
	public function store(Request $request)
	{
		$request->validate([
			'title' => 'required|max:20',
			'content' => 'required',
			'score' => 'required|integer|min:1|max:5',
		]);

		try {
			$userId = Auth::id();
			$shopId = $request->input('shop_id');
			$title = $request->input('title');
			$content = $request->input('content');
			$score = $request->input('score');

			$recentReview = Review::where('user_id', $userId)
				->where('shop_id', $shopId)
				->where('title', $title)
				->where('content', $content)
				->where('created_at', '>=', now()->subSeconds(10))
				->first();

			if ($recentReview) {
				return back();
			}

			$review = new Review;
			$review->user_id = $userId;
			$review->shop_id = $shopId;
			$review->title = $title;
			$review->content = $content;
			$review->score = $score;
			$review->save();

			return back();
		} catch (QueryException $e) {
			Log::error('Database Error' . $e->getMessage());
			return back()->withErrors(['db_error' => 'データベースへの登録が失敗しました。時間をおいて再度試してみてください'])->withInput();
		} catch (Exception $e) {
			Log::error('General Error' . $e->getMessage());
			return back()->withErrors(['general_error' => '予期せぬエラーが発生しました'])->withInput();
		}
	}

	public function update(Request $request, Review $review)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required|max:20',
			'content' => 'required',
			'score' => 'required|integer|min:1|max:5'
		]);

		if ($validator->fails()) {
			return back()->withErrors($validator, 'editReview')->withInput()->with('modal_open', 'editReviewModal')->with('action', route('reviews.update', $review->id));
		}

		if (Auth::id() !== $review->user_id) {
			abort(403);
		}

		try {
			$review->update([
				'title' => $request->input('title'),
				'content' => $request->input('content'),
				'score' => $request->input('score'),
			]);

			return redirect()->route('shops.show', $review->shop_id);
		} catch (QueryException $e) {
			Log::error('Database Error' . $e->getMessage());
			return back()->withErrors(['database_error' => 'データベースへの登録が失敗しました。時間をおいて再度試してみてください'])->withInput()->with('modal_open', 'editReviewModal');
		} catch (Exception $e) {
			Log::error('General Error' . $e->getMessage());
			return back()->withErrors(['general_error' => '予期せぬエラーが発生しました'])->withInput()->with('modal_open', 'editReviewModal');
		}
	}

	public function destroy(Review $review)
	{

		if (Auth::id() !== $review->user_id) {
			abort(403);
		}

		try {
			$review->delete();
			return redirect()->route('shops.show', $review->shop_id);
		} catch (QueryException $e) {
			Log::warning('Database Error' . $e->getMessage());
			return redirect()->route('shops.show', $review->shop_id)->withErrors(['db_error' => 'データベースへの登録が失敗しました。時間をおいて再度試してみてください']);
		} catch (Exception $e) {
			Log::warning('General Error' . $e->getMessage());
			return redirect()->route('shops.show', $review->shop_id)->withErrors(['general_error' => '予期せぬエラーが発生しました']);
		}
	}
}
