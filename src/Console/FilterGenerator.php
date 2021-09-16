<?php

namespace Touhidurabir\Filterable\Console;

use Exception;
use Throwable;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Touhidurabir\StubGenerator\StubGenerator;
use Touhidurabir\StubGenerator\Concerns\NamespaceResolver;
use Touhidurabir\Filterable\Console\Concerns\CommandExceptionHandler;

class FilterGenerator extends Command {

    use NamespaceResolver;
    
    /**
     * Process the handeled exception and provide output
     */
    use CommandExceptionHandler;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter
                            {class                                  : Filter class name}
                            {--filters=                             : The applicable filters}
                            {--query-suffix=QueryFilter             : The class suffix of query filters to add end of provided class name}
                            {--collection-suffix=CollectionFilter   : The class suffix of collection filters to add end of provided class name}
                            {--no-suffix                            : Do not attach the default class name suffux}
                            {--only-query                           : Generate only query filter}
                            {--only-collection                      : Generate only collection filter}
                            {--replace                              : Should replace an existing one}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Filter Class Generator';


    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Filter';


    /**
     * Class generator stub paths
     *
     * @var array
     */
    protected $stubPaths = [
        'query' => [
            'filter' => '/stubs/query-filter/filter.stub',
            'method' => '/stubs/query-filter/method.stub'
        ],
        'collection' => [
            'filter' => '/stubs/collection-filter/filter.stub',
            'method' => '/stubs/collection-filter/method.stub'
        ],
    ];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        
        $this->info('Generating Filter class');

        try {

            foreach( $this->generateableFilters() as $type => $generateable ) {

                if ( ! $generateable ) {

                    continue;
                }

                if ( $this->generateFilter($type) ) {

                    $this->info("{$type} filter class generated successfully");
                }
            }
            
        } catch (Throwable $exception) {

            // ray($exception);
            
            $this->outputConsoleException($exception);

            return 1;
        }
    }


    /**
     * Genrate the stub file full absolute path
     *
     * @param  string $stubRelativePath
     * @return string
     */
    private function generateFullPathOfStubFile(string $stubRelativePath) {

        return __DIR__ . $stubRelativePath;
    }


    /**
     * Genrate the filter class and store of given type[query,collection]
     *
     * @param  string $type
     * @return bool
     */
    private function generateFilter(string $type) {

        return (new StubGenerator)
                    ->from( $this->generateFullPathOfStubFile($this->stubPaths[$type]['filter']), true )
                    ->to( 
                        $this->generateFilePathFromNamespace(
                            $this->resolveClassNamespace(
                                $this->argument('class')
                            ) ?? config("filterable.filterable_namespace.{$type}")
                        ), 
                        true 
                    )
                    ->as( 
                        $this->resolveClassName(
                            $this->argument('class')) 
                                . ($this->option('no-suffix') ? '' : $this->option("{$type}-suffix")) 
                    )
                    ->withReplacers([
                        'class'                 => $this->resolveClassName($this->argument('class')),
                        'suffix'                => !$this->option('no-suffix') ? $this->option("{$type}-suffix") : '',
                        'classNamespace'        => $this->resolveClassNamespace($this->argument('class')) ?? config("filterable.filterable_namespace.{$type}"),
                        'baseClassNamespace'    => config("filterable.base_class.{$type}"),
                        'baseClassName'         => last(explode('\\', config("filterable.base_class.{$type}"))),
                        'methods'               => $this->generateFilterMethodsFromStub($this->stubPaths[$type]['method'], $this->option('filters')),
                    ])
                    ->replace($this->option('replace'))
                    ->save();
    }


    /**
     * Generate the filter methods of give filters
     *
     * @param  string               $stubRelativePath
     * @param  mixed<string|null>   $filters
     * 
     * @return string 
     */
    private function generateFilterMethodsFromStub(string $stubRelativePath, string $filters = null) {

        $filters = array_filter(array_map('trim', explode(',', $filters)));

        if ( empty($filters) ) {

            return '';
        }

        $methods = '';

        $stubGenerator = (new StubGenerator)
                            ->from($this->generateFullPathOfStubFile($stubRelativePath), true);

        foreach($filters as $filter) {

            $generatedMethod = $stubGenerator
                                    ->withReplacers(['method' => $filter])
                                    ->toString();

            $methods = $methods . $generatedMethod . PHP_EOL;
        }

        return $methods;
    }


    /**
     * Generate the filterable types list as collection that can be generated
     *
     * @return object<\Illuminate\Support\Collection> 
     */
    private function generateableFilters() : Collection {

        if ( $this->option('only-query') && !$this->option('only-collection') ) {

            return collect(['query' => true, 'collection' => false]);
        }

        if ( !$this->option('only-query') && $this->option('only-collection') ) {

            return collect(['query' => false, 'collection' => true]);
        }

        return collect(['query' => true, 'collection' => true]);
    }
    
}