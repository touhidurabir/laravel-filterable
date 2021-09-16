<?php

namespace Touhidurabir\Filterable\Tests\App\CollectionFilters;

use Throwable;
use Touhidurabir\Filterable\Bases\BaseCollectionFilter;
use Illuminate\Support\Collection;

class ProfileCollectionFilter extends BaseCollectionFilter {

    
    /**
     * Filter by first_name
     *
     * @param  object $item
     * @param  mixed  $value
     *
     * @return
     */
    public function first_name($item, $value) {

        return strtolower($item->first_name) == strtolower($value);
    }


    /**
     * Filter by last_name
     *
     * @param  object $item
     * @param  mixed  $value
     *
     * @return
     */
    public function last_name($item, $value) {

        return strtolower($item->last_name) == strtolower($value);
    }


}