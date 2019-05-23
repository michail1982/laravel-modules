<?php
namespace Michail1982\Modules\Module;

use Symfony\Component\Finder\SplFileInfo;

class JsonModule extends AbstractModule
{
    public function __construct(SplFileInfo $file)
    {
        $json = $file->getContents();

        $attributes = json_decode($json, true);

        $attributes[self::PATH_KEY] = $file->getPath();

        $this->_attributes = $attributes;
    }

}

