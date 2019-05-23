<?php
namespace Michail1982\Modules\Module;

use Michail1982\Modules\Contracts\ModuleInterface;
use Michail1982\Modules\Enum\Status;
use Illuminate\Contracts\Routing\UrlGenerator;

abstract class AbstractModule implements ModuleInterface
{

    protected $_attributes = [];

    public function getRouteKey()
    {
        return $this->_attributes[self::ROUTE_KEY];
    }

    public function getVersion()
    {
        return $this->_attributes[self::VERSION_KEY] ?? '1.0.0';
    }

    public function getName()
    {
        return $this->_attributes[self::NAME_KEY];
    }

    public function getNamespace($class = '')
    {
        return $this->_attributes[self::NAMESPACE_KEY] . ($class ? '\\' . $class : $class);
    }

    public function getDependencies()
    {
        return $this->_attributes[self::DEPENDENCIES_KEY] ?? [];
    }

    public function getPath($path = '')
    {
        return $this->_attributes[self::PATH_KEY] . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function toArray()
    {
        return $this->_attributes;
    }

    public function getSort()
    {
        return $this->_attributes[self::SORT_KEY] ?? 100;
    }

    public function asset($path = '', $secure = null)
    {
        app(UrlGenerator::class)->asset($this->getRouteKey() . ($path ? '/' . $path : $path));
    }

    public function getStatus()
    {
        return Status::make($this->_attributes[self::STATUS_KEY] ?? 'enabled');
    }

    public function url($path = '', $extra = [], $secure = null)
    {
        app(UrlGenerator::class)->to($this->getRouteKey() . ($path ? '/' . $path : $path), $extra, $secure);
    }

    public function setStatus(Status $status)
    {
        $this->_attributes[self::STATUS_KEY] = $status->getValue();
        return $this;
    }

    public function getProvider()
    {
        return $this->getNamespace($this->_attributes[self::PROVIDER_KEY] ?? 'Providers\\ModuleServiceProvider');
    }
}

