<?php
namespace Michail1982\Modules\Contracts;

/**
 * @author Michail1982
 *
 */
interface ModuleRepositoryInterface
{
    public function find($route_key) : ModuleInterface;

    public function register() : void;

    public function boot() : void;

    public function save(ModuleInterface $module) : bool;

    public function scan();
}

