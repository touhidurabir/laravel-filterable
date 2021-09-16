<?php

namespace Touhidurabir\Filterable\Tests\App;

use Illuminate\Database\Eloquent\Model;
use Touhidurabir\Filterable\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model {

    use SoftDeletes;

    use Filterable;

    /**
     * The model associated table
     *
     * @var string
     */
    protected $table = 'profiles';


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}