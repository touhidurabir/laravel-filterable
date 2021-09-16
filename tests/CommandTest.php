<?php

namespace Touhidurabir\Filterable\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Touhidurabir\Filterable\Tests\Traits\FileHelpers;
use Touhidurabir\Filterable\Tests\Traits\LaravelTestBootstrapping;

class CommandTest extends TestCase {

    use LaravelTestBootstrapping;

    use FileHelpers;


    /**
     * Filter class store full absolute directory path
     *
     * @var array
     */
    protected $filterStoreFullPath = [];


    /**
     * Generate the seeder class store full absolute directory path
     *
     * @return void
     */
    protected function generateFileStorePath(string $namespace) {

        return $this->sanitizePath(
            str_replace(
                '/public', 
                $this->sanitizePath($this->generateFilePathFromNamespace($namespace)), 
                public_path()
            )
        );
    }


    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void {

        parent::setUp();

        $this->filterStoreFullPath = [
            'query'         => $this->generateFileStorePath(config('filterable.filterable_namespace.query')),
            'collection'    => $this->generateFileStorePath(config('filterable.filterable_namespace.collection')),
        ];

        $self = $this;

        $this->beforeApplicationDestroyed(function () use ($self) {

            foreach($self->filterStoreFullPath as $type => $filterClassStorePath) {

                if ( File::isDirectory($filterClassStorePath) ) {

                    array_map('unlink', glob($filterClassStorePath . '*.*'));
    
                    rmdir($filterClassStorePath);
                }
            }
        });
    }


    /**
     * @test
     */
    public function filter_command_will_run() {
        
        $this->artisan('make:filter User')->assertExitCode(0);

        $this->artisan('make:filter Profile --filters=first_name,last_name')->assertExitCode(0);
    }


    /**
     * @test
     */
    public function filter_command_will_generate_proper_filter_class_files() {
        
        $this->artisan('make:filter User')->assertExitCode(0);

        $this->assertTrue(File::exists($this->filterStoreFullPath['query'] . 'UserQueryFilter.php'));
        $this->assertTrue(File::exists($this->filterStoreFullPath['collection'] . 'UserCollectionFilter.php'));
    }


    /**
     * @test
     */
    public function filter_command_will_replace_existing_files_if_instructed() {
        
        $this->artisan('make:filter User')->assertExitCode(0);

        $this->artisan('make:filter User --replace')->assertExitCode(0);

        $this->assertTrue(File::exists($this->filterStoreFullPath['query'] . 'UserQueryFilter.php'));
        $this->assertTrue(File::exists($this->filterStoreFullPath['collection'] . 'UserCollectionFilter.php'));
    }


    /**
     * @test
     */
    public function filter_command_will_generate_file_with_proper_name_if_no_suffix_instructed() {
        
        $this->artisan('make:filter User --no-suffix')->assertExitCode(0);

        $this->assertTrue(File::exists($this->filterStoreFullPath['query'] . 'User.php'));
        $this->assertTrue(File::exists($this->filterStoreFullPath['collection'] . 'User.php'));

        $this->assertFalse(File::exists($this->filterStoreFullPath['query'] . 'UserQueryFilter.php'));
        $this->assertFalse(File::exists($this->filterStoreFullPath['collection'] . 'UserCollectionFilter.php'));
    }


    /**
     * @test
     */
    public function filter_command_will_generate_only_instructed_type_filter_class() {
        
        $this->artisan('make:filter User --only-query')->assertExitCode(0);

        $this->assertTrue(File::exists($this->filterStoreFullPath['query'] . 'UserQueryFilter.php'));
        $this->assertFalse(File::exists($this->filterStoreFullPath['collection'] . 'UserCollectionFilter.php'));

        $this->artisan('make:filter Profile --only-collection')->assertExitCode(0);

        $this->assertFalse(File::exists($this->filterStoreFullPath['query'] . 'ProfileQueryFilter.php'));
        $this->assertTrue(File::exists($this->filterStoreFullPath['collection'] . 'ProfileCollectionFilter.php'));

        $this->artisan('make:filter Test --only-collection --only-query --replace')->assertExitCode(0);

        $this->assertTrue(File::exists($this->filterStoreFullPath['query'] . 'TestQueryFilter.php'));
        $this->assertTrue(File::exists($this->filterStoreFullPath['collection'] . 'TestCollectionFilter.php'));
    }

}