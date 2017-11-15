<?php
namespace Uzzal\Crud;

use Illuminate\Support\ServiceProvider;
use Blade;

class CrudServiceProvider extends ServiceProvider {
    use ViewCompiler;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {                        

        
        $this->loadViewsFrom(__DIR__ . '/views', 'acl');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/database/seeds/' => database_path('seeds')
                ], 'seeds');
        
        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/acl'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

    }

}
