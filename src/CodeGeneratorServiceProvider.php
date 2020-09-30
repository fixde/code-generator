<?php

namespace Fixde\CodeGenerator;

use Fixde\CodeGenerator\Console\Commands\BaseCommand;
use Fixde\CodeGenerator\Console\Commands\GenerateLaravelCommand;
use Illuminate\Support\ServiceProvider;

class CodeGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../templates/laravel', 'laravel-templates');

        $this->publishes([
            __DIR__.'/../config/generator.php' => config_path('generator.php'),
            __DIR__.'/../templates/laravel' => resource_path('views/vendor/laravel-templates'),
        ], 'code-generator');

        if ($this->app->runningInConsole()) {
            $this->commands([
                BaseCommand::class,
                GenerateLaravelCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/generator.php',
            'generator'
        );
    }
}
