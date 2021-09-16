<?php

namespace Touhidurabir\Filterable\Bases;

use Throwable;
use Illuminate\Support\Collection;
use Touhidurabir\Filterable\Concerns\FilterBaseSetup;
use Touhidurabir\Filterable\Concerns\FilterValidator;

abstract class BaseCollectionFilter {

    use FilterBaseSetup, FilterValidator;

    /**
     * Apply the filters to the collection.
     *
     * @param  object<\Illuminate\Support\Collection>   $collections
     * @param  array                                    $filterables
     * 
     * @return object<\Illuminate\Support\Collection>
     */
    public function filter(Collection $collections, array $filterables = []) {

        $filters = $this->filters($filterables);

        $self = $this;

        return $collections->filter(function ($collection) use ($self, $filters) {

            $applicable = true;

            foreach ( $filters as $filter => $value ) {

                if (! method_exists($self, $filter)) {

                    continue;
                }

                if ( !empty($self->getRules()) && !$self->validateFilter($filter, $value) ) {

                    continue;
                }

                $applicable = $applicable && $self->$filter($collection, $value);
            }

            return $applicable;
        });
    }


    /**
     * Get all request filters data.
     *
     * @return array
     */
    public function filters(array $filterables = []) {
    	
        return empty($filterables) ? (optional($this->getRequest())->all() ?? []) : $filterables;
    }


    /**
     * Static method to Apply the filters to the collection.
     *
     * @param  object<\Illuminate\Support\Collection>   $collections
     * @param  array                                    $filterables
     * 
     * @return object<\Illuminate\Support\Collection>
     */
    public static function applyFilter(Collection $collections, array $filterables = []) {

        return (new static)->filter($collections, $filterables);
    }

}