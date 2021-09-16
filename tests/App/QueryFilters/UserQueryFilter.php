<?php

namespace Touhidurabir\Filterable\Tests\App\QueryFilters;

use Touhidurabir\Filterable\Bases\BaseQueryFilter;
use Illuminate\Database\Eloquent\Builder;

class UserQueryFilter extends BaseQueryFilter {

    /**
     * Retrieve the rules to validate filters value.
     * If a filter validation fails, the filter is not applied.
     *
     * @return array
     */
    protected function getRules() {
        
        return [
            'email' => ['email', 'string', 'nullable'],
            'name'  => ['string', 'nullable'],
        ];
    }

	
    /**
     * Filter by request param name
     *
     * @param  mixed $value
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
    public function name($value) {

        return $this->builder->where('name', 'LIKE', "%{$value}%") ;
    }


    /**
     * Filter by request param email
     *
     * @param  mixed $value
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
    public function email($value) {

        return $this->builder->where('email', '=', $value);
    }


}