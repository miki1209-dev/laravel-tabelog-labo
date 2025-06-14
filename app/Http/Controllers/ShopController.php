<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
	public function index(Request $request)
	{
		$keyword = $request->input('keyword');
		$category = $request->input('category');

		$query = Shop::query();

		if (!empty($keyword)) {
			$query->where(function ($q) use ($keyword) {
				$q->where('name', 'like', "%{$keyword}%")->orWhereHas('categories', function ($q2) use ($keyword) {
					$q2->where('name', 'like', "%{$keyword}%");
				});
			});
		}

		if (!empty($category)) {
			$query->whereHas('categories', function ($q) use ($category) {
				$q->where('name', $category);
			});
		}
		$shops = $query->with('categories')->paginate(5)->withQueryString();
		$shop_count = $shops->total();

		$categoriesName = Category::pluck('name');

		$queryParams = $request->only(['keyword', 'category']);

		return view('shops.index', compact('keyword', 'shops', 'shop_count', 'category', 'categoriesName', 'queryParams'));
	}

	public function show(Shop $shop, Request $request)
	{

		$user = Auth::user();
		$isFavorited = $user->favorite_shops->contains($shop->id);
		$reviews = $shop->reviews()->latest()->paginate(5);
		$reviewTotal = $reviews->total();
		$tomorrow = Carbon::tomorrow()->format('Y-m-d');
		$startHour = Carbon::parse($shop->opening_time);
		$endHour = Carbon::parse($shop->closing_time)->subMinutes(30);

		$queryParams = $request->only(['keyword', 'category']);

		return view('shops/show', compact('shop', 'reviews', 'startHour', 'endHour', 'tomorrow', 'queryParams', 'reviewTotal', 'isFavorited'));
	}
}
