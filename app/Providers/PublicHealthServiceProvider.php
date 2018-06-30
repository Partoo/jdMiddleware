<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PublicHealthServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->publishes([
			__DIR__ . '/publicHealthConfig.php' => config_path('publicHealth.php'),
		]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
