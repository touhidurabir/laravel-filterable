<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base Filterable classes
    |--------------------------------------------------------------------------
    |
    | The base filterable classes that will be extended by all all other generated
    | query filter classes and collection filter classes . 
    |
    */

    'base_class' => [
        'query'         => \Touhidurabir\Filterable\Bases\BaseQueryFilter::class,
        'collection'    => \Touhidurabir\Filterable\Bases\BaseCollectionFilter::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Path/location namespace to save filter classes
    |--------------------------------------------------------------------------
    |
    | location where to store the query/collection filter classes to save/store. 
    |
    */

    'filterable_namespace' => [
        'query'         => 'App\\QueryFilters',
        'collection'    => 'App\\CollectionFilters',
    ],
];