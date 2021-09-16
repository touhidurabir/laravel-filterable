# Laravel Filterable

A package to filter laravel model based on query params or retrived model collection.


## Installation

Require/Install the package using composer:

```bash
composer require touhidurabir/laravel-filterable
```

To publish the config file:
```bash
php artisan vendor:publish --provider="Touhidurabir\Filterable\FilterableServiceProvider" --tag=config
```

## Configuration

The package comes with a configuration file named **filterable** that has to 2 important configs 

### Base Filter Class

This is a array that contains the base filter class for both the **Query** and **Collection** filter as : 

```php
'base_class' => [
    'query'         => \Touhidurabir\Filterable\Bases\BaseQueryFilter::class,
    'collection'    => \Touhidurabir\Filterable\Bases\BaseCollectionFilter::class,
],
```

If one need to even extend it to add more functionality or any custom feaure, can do it and set the base class in the config file . 

### Filter Class Namespace

This config define what would be the default namespace(and the store path) of the generated filter classes for the both the **Query** and **Collection** filter classes as : 

```php
'filterable_namespace' => [
    'query'         => 'App\\QueryFilters',
    'collection'    => 'App\\CollectionFilters',
],
```

If needed to, one can change the default path from there . but it is also possible to pass a different namespace to the filter generate command to provide a different namespace.


## Command

This package includes a handly command to generate filter classes as 

```bash
php artisan make:filter User
```

It will generate 2 class **UserQueryFilter** and **UserCollectionFilter** as per defined namespace in the config file . 

This command also includes several handful options to make the filer class generation as flexiable as possible such as 

### --filters=

By passign comma separated filters, it will put those filters as filterable method in both the query and collection filter class as : 

```bash
php artisan make:filter User --filter=name,email
```
For Query FIlter : 

```php
public function name($value) {

    // return $this->builder->;
}

public function email($value) {

    // return $this->builder->;
}
```

For Collection Filter : 

```php
public function name($item, $value) {

}

public function email($item, $value) {

}
```

### --query-suffix=QueryFilter

Define what would be query filter class file name and class name suffix .


### --collection-suffix=CollectionFilter

Define what would be collection filter class file name and class name suffix .

### --no-suffix

If passed as switch option or flag, no suffix will be added to query or collection filter class names or files name.

### --only-query

If passed as switch option or flag, will only generate the query filters and omit the collection filter class.

### --only-collection

If passed as switch option or flag, will only generate the collection filters and omit the query filter class.

### --replace

If passed as switch option or flag, will replace the existing file. By defalt if a given file already present, it will not replace it . 


## Usage

Generate the filters as 

```bash
php artisan make:filter User --filter=name,email
```
it will generate **UserQueryFilter.php** and **UserCollectionFilter.php** class at the given path as :

```php
<?php

namespace App\QueryFilters;

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
        
        return [];
    }

	
    /**
     * Filter by request param name
     *
     * @param  mixed $value
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
    public function name($value) {

        // return $this->builder->;
    }


    /**
     * Filter by request param email
     *
     * @param  mixed $value
     * @return object<\Illuminate\Database\Eloquent\Builder>
     */
    public function email($value) {

        // return $this->builder->;
    }


}
```

```php
<?php

namespace App\CollectionFilters;

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
        
        return [];
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
        
        // return
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

        // return
    }
}
```

and the use the **Filterable** trait in the model as 

```php
use Touhidurabir\Filterable\Filterable;

class User extends Model {

    use Filterable;
}
```

The form some controller, use it as such

```php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\QueryFilters\UserQueryFilters;
use App\CollectionFilter\UserCollectionFilter;

class UserController extends Controller {

    public function index(Request $request) {

        $users = User::filter(new UserQueryFilter($request))->get();

        // or filter a collection as 

        $users = (new UserCollectionFilter)->filter(User::all(), ['email', 'name']);

        // of pass $request in constructor for collection filter

        $users = (new UserCollectionFilter($request))->filter(User::all());
    }
}
```

Also possible to use an existing **array** to pass as query to initiate the filter class as 

```php
UserQueryFilter::hydrate([]);
UserCollection::applyFilter(User::all() ,[]);
```
> Note that it's not required to pass the $request as if not passed , it will resolve it from the Request Facade . Useful for case like when app running on laravel octane.

It can also handle the filter param validation as :

```php
protected function getRules() {
    
    return [];
}
```

Set the validation rules there and those params that do not pass the validation will not be applied.

## WHY a Collection Filter ?

It is a valid question why a collection filter as most of the time a query filter is sufficient. But some times a collection filter can be helpful to do some custom filter again after records are pull from DB. As this package allow to generate seperate collection filter, in those cases it can be helpful to such cause . 

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE.md)
