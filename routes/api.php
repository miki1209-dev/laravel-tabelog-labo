<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FavoriteApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::middleware('auth:sanctum', 'paid')->group(function () {
	Route::controller(FavoriteApiController::class)->group(function () {
		Route::post('/favorites', 'store')->name('api.favorites.store');
		Route::delete('/favorites/{shop}', 'destroy')->name('api.favorites.destroy');
	});
});
