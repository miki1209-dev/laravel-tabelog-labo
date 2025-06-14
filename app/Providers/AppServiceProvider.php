<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Laravel\Cashier\CashierServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register(CashierServiceProvider::class);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Paginator::useBootstrap();
		if (App::environment(['production'])) {
			URL::forceScheme('https');
		}
	}
}
