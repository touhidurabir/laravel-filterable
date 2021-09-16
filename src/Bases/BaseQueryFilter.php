<?php

namespace Touhidurabir\Filterable\Bases;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Database\Eloquent\Builder;
use Touhidurabir\Filterable\Concerns\FilterBaseSetup;
use Touhidurabir\Filterable\Concerns\FilterValidator;

abstract class BaseQueryFilter {

    use FilterBaseSetup, FilterValidator;

    /**
     * The builder instance.
     *
     * @var object<\Illuminate\Database\Eloquent\Builder>
     */
    protected $builder;


    /**
     * Apply the filters to the builder.
     *
     * @param  object<\Illuminate\Database\Eloquent\Builder> $builder
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
    public function apply(Builder $builder) {

        $this->builder = $builder;
        
        foreach ($this->filters() as $filter => $value) {

            if (! method_exists($this, $filter)) {

                continue;
            }

            if ( !empty($this->getRules()) && !$this->validateFilter($filter, $value) ) {

                continue;
            }

            is_array($value)
                ? $this->$filter($value)
                : ( strlen($value) ? $this->$filter($value) : $this->$filter() );
        }

        return $this->builder;
    }


    /**
     * Get all request filters data.
     *
     * @return array
     */
    public function filters() {
    	
        return optional($this->getRequest())->all() ?? [];
    }


    /**
     * Get all filterables for the running class
     *
     * @return array
     */
    public function filterables() {

        $filterables = [];

        $reflection = new ReflectionClass(get_class($this));
        
        foreach ( $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method ) {

            if ( $method->class === $reflection->getName() ) {

                $filterables[$method->name] = $this->request->get($method->name);
            }
        }

        return $filterables;
    }

}