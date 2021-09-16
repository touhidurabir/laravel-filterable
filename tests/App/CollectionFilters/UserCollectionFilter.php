<?php

namespace Touhidurabir\Filterable\Tests\App\CollectionFilters;

use Throwable;
use Touhidurabir\Filterable\Bases\BaseCollectionFilter;
use Illuminate\Support\Collection;

class UserCollectionFilter extends BaseCollectionFilter {

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
     * Filter by name
     *
     * @param  object $item
     * @param  mixed  $value
     *
     * @return
     */
    public function name($item, $value) {

        return strtolower($item->name) == strtolower($value);
    }


    /**
     * Filter by email
     *
     * @param  object $item
     * @param  mixed  $value
     *
     * @return
     */
    public function email($item, $value) {

        return strtolower($item->email) == strtolower($value);
    }


}