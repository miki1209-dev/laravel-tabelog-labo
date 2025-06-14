<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;

class FavoriteApiController extends Controller
{
	public function store(Request $request)
	{
		$user = $request->user();
		$shopId = $request->input('shop_id');
		$shop = Shop::findOrFail($shopId);

		try {
			if (!$user->favorite_shops()->where('shop_id', $shopId)->exists()) {
				$user->favorite_shops()->attach($shopId);
			}
			return response()->json(['message' => 'お気に入り登録しました'], 200);
		} catch (QueryException $e) {
			Log::error('Database Error' . $e->getMessage());
			return response()->json(['message' => 'お気に入り登録に失敗しました。時間をおいて再度お試しください。'], 500);
		} catch (Exception $e) {
			Log::error('General Error' . $e->getMessage());
			return response()->json(['message' => '予期せぬエラーが発生しました'], 500);
		}
	}

	public function destroy(Request $request, Shop $shop)
	{
		$user = $request->user();
		try {
			if ($user->favorite_shops()->where('shop_id', $shop->id)->exists()) {
				$user->favorite_shops()->detach($shop->id);
				return response()->json(['message' => 'お気に入りを削除しました'], 200);
			} else {
				return response()->json(['message' => 'お気に入り登録はされていませんでした'], 200);
			}
		} catch (QueryException $e) {
			Log::error('Database Error' . $e->getMessage());
			return response()->json(['message' => 'お気に入り削除に失敗しました。時間をおいて再度お試しください。'], 500);
		} catch (Exception $e) {
			Log::error('General Error' . $e->getMessage());
			return response()->json(['message' => '予期せぬエラーが発生しました'], 500);
		}
	}
}
