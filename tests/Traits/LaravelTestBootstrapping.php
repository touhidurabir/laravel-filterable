<?php

namespace Touhidurabir\Filterable\Tests\Traits;

use Touhidurabir\Filterable\FilterableServiceProvider;

trait LaravelTestBootstrapping {

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app) {

        return [
            FilterableServiceProvider::class,
        ];
    }   

}