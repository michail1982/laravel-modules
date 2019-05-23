<?php
namespace Michail1982\Modules\Repository;

use Michail1982\Modules\Contracts\ModuleRepositoryInterface;
use Michail1982\Modules\Contracts\ModuleInterface;
use Michail1982\Modules\Exception\ModuleNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Michail1982\Modules\Module\CachedModule;

abstract class AbstractRepository implements ModuleRepositoryInterface
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array<ModuleInterface>
     */
    protected $_modules;

    protected $_enabled_keys;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function find($route_key) : ModuleInterface
    {
        if(!array_key_exists($route_key, $this->_modules)) {
            throw (new ModuleNotFoundException())->setRouteKey($route_key);
        }
        return $this->_modules[$route_key];
    }

    public function register()
    {
        $this->load();

        $enabled_keys = [];

        $queue = array_keys($this->_modules);
        $next_loop = sizeof($queue);
        while($next_loop) {
            $next_loop = false;
            foreach ($queue as $route_key) {
                $module = $this->find($route_key);
                if(!$module->getStatus()->isEnabled()) {
                    unset($queue[$route_key]);
                    continue;
                }
                if(array_diff($this->_enabled_keys, $module->getDependencies())) {
                    continue;
                }

                $enabled_keys[] = $module->getRouteKey();
                $next_loop = true;
            }
            if(!sizeof($queue)) {
                $next_loop = false;
            }
        }
        foreach ($enabled_keys as $route_key) {
            $this->app->register($this->find($route_key)->getProvider());
        }
    }

    protected function load()
    {
        if($this->app['config']['modules.cache.enabled']) {
            $modules = $this->loadCached();
        } else {
            $modules = $this->scan();
        }

        foreach ($modules as $module) {
            $this->_modules[$module->getRouteKey()] = $module;
        }

        uasort($this->_modules, function(ModuleInterface $a, ModuleInterface $b){
            return $a->getSort() <=> $b->getSort();
        });
    }

    protected function loadCached()
    {
        $cachedModules = Cache::rememberForever(Config::get('modules.cache.key'), function(){
            $modules = $this->scan();
            $return = [];
            foreach ($modules as $module) {
                $return[] = $module->toArray();
            }
            return $return;
        });
        $modules = [];
        foreach ($cachedModules as $attributes) {
            try {
                $modules[] = $this->createCachedModule($attributes);
            } catch (\RuntimeException $e) {
            }
        }
        return $modules;
    }

    protected function createCachedModule($attributes)
    {
        return new CachedModule($attributes);
    }
}

