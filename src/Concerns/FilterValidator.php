<?php

namespace Touhidurabir\Filterable\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

trait FilterValidator {

    /**
     * Get the rules to validate filters value.
     * If a filter validation fails, the filter is not applied.
     *
     * @return array
     */
    protected function getRules() {

        return [];
    }


    /**
     * Validate a specific filter
     *
     * @param  string $filter
     * @param  mixed  $value
     * 
     * @return bool
     */
    protected function validateFilter(string $filter, $value) {

        $rules = $this->getRules();

        if ( !in_array($filter, array_keys($rules)) ) {

            return true;
        }

        $validator = Validator::make([$filter => $value], Arr::only($rules, $filter));

        return $validator->passes();
    }
}