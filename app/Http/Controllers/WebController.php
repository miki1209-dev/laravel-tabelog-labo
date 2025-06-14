<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Category;

class WebController extends Controller
{
	public function index()
	{
		$recentlyShops = Shop::orderBy('created_at', 'desc')->take(4)->get();
		$featuredCategories = Category::where('is_featured', true)->take(6)->get();
		$normalCategories = Category::where('is_featured', false)->get();
		$featuredShops = Shop::withAvg('reviews', 'score')->orderBy('reviews_avg_score', 'desc')->take(4)->get();
		return view('web.index', compact('recentlyShops', 'featuredCategories', 'normalCategories', 'featuredShops'));
	}
}
