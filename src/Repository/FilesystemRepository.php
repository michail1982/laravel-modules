<?php
namespace Michail1982\Modules\Repository;

use Michail1982\Modules\Contracts\ModuleInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Symfony\Component\Finder\Finder;
use Michail1982\Modules\Module\JsonModule;

class FilesystemRepository extends AbstractRepository
{

    const MANIFEST_FILE = 'module.json';

    /**
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $_filesystem;

    /**
     *
     * @var array
     */
    protected $_paths;

    protected $_depth = 3;

    public function __construct(Application $app)
    {
        $this->_paths = $app['config']['modules.filesystem.paths'] ?? [];
        $this->_depth = $app['config']['modules.filesystem.depth'] ?? 3;
        parent::__construct($app);
    }

    public function save(ModuleInterface $module) : bool
    {
        return $this->getFilesystem()->put($module->getPath(self::MANIFEST_FILE), json_encode($module->toArray())) !==false;
    }

    public function scan()
    {
        $paths = array_unique(Arr::wrap($this->_paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return [];
        }
        $modules = [];
        foreach ((new Finder())->in($paths)
            ->files()
            ->depth('< ' . $this->_depth)
            ->name(self::MANIFEST_FILE) as $moduleFile) {
            /** @var \Symfony\Component\Finder\SplFileInfo $moduleFile */
            try {
                $modules[] = $this->createJsonModule($moduleFile);
            } catch (\RuntimeException $e) {}
        }
        return $modules;
    }

    protected function createJsonModule(\Symfony\Component\Finder\SplFileInfo $moduleFile)
    {
        return new JsonModule($moduleFile);
    }

    /**
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function getFilesystem()
    {
        if (! $this->_filesystem) {
            $this->_filesystem = new Filesystem();
        }
        return $this->_filesystem;
    }
}

