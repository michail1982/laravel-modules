<?php
namespace Michail1982\Modules;

use Illuminate\Support\Manager;
use Michail1982\Modules\Repository\FilesystemRepository;

class ModulesManager extends Manager
{
    public function createFilesystemDriver()
    {
        return new FilesystemRepository($this->app);
    }

    public function getDefaultDriver()
    {
        return $this->app['config']['modules.provider'] ?? 'filesystem';
    }
}

