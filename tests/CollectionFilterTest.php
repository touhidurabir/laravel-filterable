<?php

namespace Touhidurabir\Filterable\Tests;

use Exception;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\DB;
use Touhidurabir\Filterable\Tests\App\User;
use Touhidurabir\Filterable\Tests\App\Profile;
use Touhidurabir\Filterable\Bases\BaseCollectionFilter;
use Touhidurabir\Filterable\Tests\Traits\LaravelTestBootstrapping;
use Touhidurabir\Filterable\Tests\App\CollectionFilters\UserCollectionFilter;
use Touhidurabir\Filterable\Tests\App\CollectionFilters\ProfileCollectionFilter;

class CollectionFilterTest extends TestCase {

    use LaravelTestBootstrapping;

    /**
     * Table seed data
     *
     * @return array
     */
    protected $data = [
        'users' => [
            ['name' => 'Test name 1', 'email' => 'testser1@test.test', 'password' => '123456'],
            ['name' => 'Test name 2', 'email' => 'testser2@test.test', 'password' => '123456'],
        ],
        'profiles' => [
            ['first_name' => 'Test', 'last_name' => 'User1'],
            ['first_name' => 'Test', 'last_name' => 'User2'],
        ],
    ];


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void {

        parent::setUp();

        $self = $this;

        DB::table('users')->insert($this->data['users']);
        DB::table('profiles')->insert($this->data['profiles']);
    }


    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations() {

        $this->loadMigrationsFrom(__DIR__ . '/App/database/migrations');
        
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->beforeApplicationDestroyed(function () {

            $this->artisan('migrate:rollback', ['--database' => 'testbench'])->run();
        });
    }


    /**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application $app
     * @return void
     */
    protected function defineEnvironment($app) {

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app.url', 'http://localhost/');
        $app['config']->set('app.debug', false);
        $app['config']->set('app.key', env('APP_KEY', '1234567890123456'));
        $app['config']->set('app.cipher', 'AES-128-CBC');
    }


    /**
     * @test
     */
    public function the_collection_filter_class_can_be_initialized() {

        $this->assertIsObject(new UserCollectionFilter);
        $this->assertIsObject(new ProfileCollectionFilter);

        $this->assertInstanceof(BaseCollectionFilter::class, new UserCollectionFilter);
        $this->assertInstanceof(BaseCollectionFilter::class, new ProfileCollectionFilter);
    }


    /**
     * @test
     */
    public function the_collection_filter_class_can_filter_collection_data() {

        $users = (new UserCollectionFilter)->filter(User::all(), ['email'=>'testser1@test.test']);
        $this->assertCount(1, $users);

        $profiles = (new ProfileCollectionFilter)->filter(Profile::all(), ['first_name'=>'TEST']);
        $this->assertCount(2, $profiles);

        $profiles = (new ProfileCollectionFilter)->filter(Profile::all(), ['first_name'=>'TEST', 'last_name' => 'User2']);
        $this->assertCount(1, $profiles);
    }


    /**
     * @test
     */
    public function the_collection_filter_class_can_filter_collection_data_via_static_method() {

        $users = UserCollectionFilter::applyFilter(User::all(), ['email'=>'testser1@test.test']);
        $this->assertCount(1, $users);

        $profiles = ProfileCollectionFilter::applyFilter(Profile::all(), ['first_name'=>'TEST']);
        $this->assertCount(2, $profiles);
    }


    /**
     * @test
     */
    public function the_collection_filter_class_can_validate_filter_collection_data_before_filtering() {

        $users = UserCollectionFilter::applyFilter(User::all(), ['email'=>'testser1']);
        $this->assertCount(2, $users);
    }
    
}