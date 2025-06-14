<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
	// public function store(Shop $shop)
	// {
	// 	try {
	// 		/** @var \App\Models\User $user */
	// 		$user = Auth::user();
	// 		$user->favorite_shops()->attach($shop->id);

	// 		return back();
	// 	} catch (QueryException $e) {
	// 		Log::error('Database Error' . $e->getMessage());
	// 		return back()->withErrors(['db_error' => 'データベースへの登録が失敗しました。時間をおいて再度試してみてください']);
	// 	} catch (Exception $e) {
	// 		Log::error('General Error' . $e->getMessage());
	// 		return back()->withErrors(['general_error' => '予期せぬエラーが発生しました']);
	// 	}
	// }

	// public function destroy(Shop $shop)
	// {

	// 	try {
	// 		/** @var \App\Models\User $user */
	// 		$user = Auth::user();
	// 		$user->favorite_shops()->detach($shop->id);

	// 		return back()->with(['success' => 'お気に入りを削除しました']);
	// 	} catch (QueryException $e) {
	// 		Log::error('Database Error' . $e->getMessage());
	// 		return back()->withErrors(['db_error' => 'データベースへの登録が失敗しました。時間をおいて再度試してみてください']);
	// 	} catch (Exception $e) {
	// 		Log::error('General Error' . $e->getMessage());
	// 		return back()->withErrors(['general_error' => '予期せぬエラーが発生しました']);
	// 	}
	// }
}
