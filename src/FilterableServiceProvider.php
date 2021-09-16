<?php

namespace Touhidurabir\Filterable;

use Illuminate\Support\ServiceProvider;
use Touhidurabir\Filterable\Console\FilterGenerator;

class FilterableServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        if ( $this->app->runningInConsole() ) {
			$this->commands([
				FilterGenerator::class
			]);
		}

        $this->publishes([
            __DIR__.'/../config/filterable.php' => base_path('config/filterable.php'),
        ], 'config');
    }

    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        $this->mergeConfigFrom(
            __DIR__.'/../config/filterable.php', 'filterable'
        );
    }
}