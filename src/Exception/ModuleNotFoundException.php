<?php
namespace Michail1982\Modules\Exception;

class ModuleNotFoundException extends \RuntimeException
{
    protected $route_key;

    public function setRouteKey($route_key)
    {
        $this->route_key = $route_key;

        $this->message = sprintf('Module with key [%s] not found', $route_key);
    }

    public function getRouteKey()
    {
        return $this->route_key;
    }
}

