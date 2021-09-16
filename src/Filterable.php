<?php

namespace Touhidurabir\Filterable;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait Filterable {

	/**
     * Filter a result set.
     *
     * @param  object<\Illuminate\Database\Eloquent\Builder> 	$query
     * @param  object      	                                    $filters
     *
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
	public function scopeFilter(Builder $query, $filters) {

        $baseQueryFilterClass = config('filterable.base_class.query');

        if ( ! ($filters instanceof $baseQueryFilterClass) ) {

            throw new Exception('$filters is not a proper instance of ' . $baseQueryFilterClass);
        }

		return $filters->apply($query);
	}
}