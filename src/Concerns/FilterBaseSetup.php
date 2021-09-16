<?php

namespace Touhidurabir\Filterable\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;

trait FilterBaseSetup {

    /**
     * The request object.
     *
     * @var object<\Illuminate\Http\Request>
     */
    protected $request;


    /**
     * Create a new filter instance.
     *
     * @param  object<\Illuminate\Http\Request>|null $request
     * @return void
     */
    public function __construct(Request $request = null) {

        $this->request = $request;
    }


    /**
     * Set the request that query filters are based on
     *
     * @param  object<\Illuminate\Http\Request> $request
     * @return self
     */
    public function setRequest(Request $request) {

        $this->request = $request;

        return $this;
    }
    

    /**
     * Retrieve the request that query filters are based on
     *
     * @return object<\Illuminate\Http\Request>
     */
    public function getRequest() {

        if ( ! $this->request ) {

            $this->request = RequestFacade::instance();
        }

        return $this->request;
    }


    /**
     * Hydrate query filters from an array.
     *
     * @param  array $queries
     * @return static
     */
    public static function hydrate(array $queries) {
        
        $request = new Request($queries);

        return (new static())->setRequest($request);
    }
}