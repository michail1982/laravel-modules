<?php
namespace Michail1982\Modules\Module;

use Michail1982\Modules\Contracts\ModuleInterface;
use Michail1982\Modules\Enum\Status;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Str;

abstract class AbstractModule implements ModuleInterface
{

    protected $_attributes = [];

    public function getRouteKey(): string
    {
        return $this->_attributes[self::ROUTE_KEY] ?? Str::slug($this->getName());
    }

    public function getVersion(): string
    {
        return $this->_attributes[self::VERSION_KEY] ?? '1.0.0';
    }

    public function getName(): string
    {
        return $this->_attributes[self::NAME_KEY];
    }

    public function getNamespace($class = ''): string
    {
        return $this->_attributes[self::NAMESPACE_KEY] . ($class ? '\\' . $class : $class);
    }

    public function getDependencies(): array
    {
        return $this->_attributes[self::DEPENDENCIES_KEY] ?? [];
    }

    public function getPath($path = ''): string
    {
        return $this->_attributes[self::PATH_KEY] . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function toArray()
    {
        return $this->_attributes;
    }

    public function getSort(): int
    {
        return $this->_attributes[self::SORT_KEY] ?? 100;
    }

    public function asset($path = '', $secure = null): string
    {
        app(UrlGenerator::class)->asset($this->getRouteKey() . ($path ? '/' . $path : $path));
    }

    public function getStatus(): Status
    {
        return Status::make($this->_attributes[self::STATUS_KEY] ?? 'enabled');
    }

    public function url($path = '', $extra = [], $secure = null): string
    {
        app(UrlGenerator::class)->to($this->getRouteKey() . ($path ? '/' . $path : $path), $extra, $secure);
    }

    public function setStatus(Status $status): ModuleInterface
    {
        $this->_attributes[self::STATUS_KEY] = $status->getValue();
        return $this;
    }

    public function getProvider(): string
    {
        return $this->getNamespace($this->_attributes[self::PROVIDER_KEY] ?? 'Providers\\ModuleServiceProvider');
    }
}

