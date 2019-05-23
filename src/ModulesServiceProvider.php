<?php
namespace Michail1982\Modules;

use Illuminate\Support\ServiceProvider;
use Michail1982\Modules\Contracts\ModuleRepositoryInterface;
use Michail1982\Modules\Providers\BootstrapServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerServices();

    }

    public function boot()
    {
        $this->registerNamespaces();
        $this->registerModules();
    }
    public function provides()
    {
        return [
            ModuleRepositoryInterface::class
        ];
    }

    protected function registerServices()
    {
        $this->app->singleton(ModuleRepositoryInterface::class, function ($app) {
            return new ModulesManager($app);
        });
    }

    protected function registerNamespaces()
    {
        $configPath = __DIR__ . '/config/config.php';
        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([
            $configPath => config_path('modules.php'),
        ], 'config');
    }

    protected function registerModules()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

}

