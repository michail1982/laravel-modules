<?php
namespace Michail1982\Modules\Providers;

use Carbon\Laravel\ServiceProvider;
use Michail1982\Modules\Contracts\ModuleRepositoryInterface;

class BootstrapServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app[ModuleRepositoryInterface::class]->register();
    }
}

