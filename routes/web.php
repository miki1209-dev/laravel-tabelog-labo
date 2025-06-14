<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WebController::class, 'index'])->name('top');

require __DIR__ . '/auth.php';
Route::middleware(['auth', 'verified'])->group(function () {

	Route::controller(UserController::class)->group(function () {
		Route::get('users/mypage', 'mypage')->name('mypage');
		Route::get('users/mypage/edit', 'edit')->name('mypage.edit');
		Route::put('users/mypage', 'update')->name('mypage.update');
		Route::get('users/mypage/subscription', 'subscription')->name('mypage.subscription');
		Route::get('users/mypage/subscription/payment', 'payment')->name('subscription.payment');
		Route::delete('users/mypage/delete', 'destroy')->name('mypage.destroy');
		Route::middleware(['paid'])->group(function () {
			Route::get('users/mypage/favorites', 'favorites')->name('mypage.favorites');
			Route::get('users/mypage/reservations', 'reservations')->name('mypage.reservations');
			Route::patch('users/mypage/reservations/{reservation}', 'cancelReservation')->name('mypage.reservations.cancel');
		});
	});

	Route::controller(ShopController::class)->group(function () {
		Route::get('shops', 'index')->name('shops.index');
		Route::get('shops/{shop}', 'show')->name('shops.show');
	});

	// お気に入りAPI化の前に使っていたルートは残しておきます
	// Route::controller(FavoriteController::class)->group(function () {
	// 	Route::middleware(['paid'])->group(function () {
	// 		Route::post('shops/{shop}/favorite', 'store')->name('favorite.store');
	// 		Route::delete('shops/{shop}/favorite', 'destroy')->name('favorite.destroy');
	// 	});
	// });

	Route::controller(ReviewController::class)->group(function () {
		Route::middleware(['paid'])->group(function () {
			Route::post('/reviews', 'store')->name('reviews.store');
			Route::put('/reviews/{review}', 'update')->name('reviews.update');
			Route::delete('/reviews/{review}', 'destroy')->name('reviews.destroy');
		});
	});

	Route::controller(ReservationController::class)->group(function () {
		Route::middleware(['paid'])->group(function () {
			Route::post('/reservations', 'store')->name('reservations.store');
			Route::post('/reservations/confirm', 'confirm')->name('reservations.confirm');
			Route::get('/reservations/complete', 'complete')->name('reservations.complete');
		});
	});

	Route::controller(SubscriptionController::class)->group(function () {
		Route::post('/subscription/subscribe', 'store')->name('subscription.store');
		Route::middleware(['paid'])->group(function () {
			Route::post('/subscription/cancel', 'cancel')->name('subscription.cancel');
			Route::post('/subscription/payment-method', 'update')->name('subscription.update');
		});
	});
});
