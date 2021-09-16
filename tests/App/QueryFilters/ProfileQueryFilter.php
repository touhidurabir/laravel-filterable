<?php

namespace Touhidurabir\Filterable\Tests\App\QueryFilters;

use Touhidurabir\Filterable\Bases\BaseQueryFilter;
use Illuminate\Database\Eloquent\Builder;

class ProfileQueryFilter extends BaseQueryFilter {

    /**
     * Retrieve the rules to validate filters value.
     * If a filter validation fails, the filter is not applied.
     *
     * @return array
     */
    protected function getRules() {
        
        return [];
    }

	
    /**
     * Filter by request param first_name
     *
     * @param  mixed $value
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
    public function first_name($value) {

        return $this->builder->where('first_name', 'LIKE', "%{$value}%");
    }


    /**
     * Filter by request param last_name
     *
     * @param  mixed $value
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
    public function last_name($value) {

        return $this->builder->where('last_name', '=', "$value");
    }


}