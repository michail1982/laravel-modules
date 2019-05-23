<?php
namespace Michail1982\Modules\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Routing\UrlGenerator;
use Michail1982\Modules\Enum\Status;

/**
 *
 * @author Michail1982
 *
 */
interface ModuleInterface extends Arrayable
{

    const NAME_KEY = 'name';

    const NAMESPACE_KEY = 'namespace';

    const PATH_KEY = 'path';

    const ROUTE_KEY = 'route_key';

    const VERSION_KEY = 'version';

    const STATUS_KEY = 'status';

    const DEPENDENCIES_KEY = 'dependencies';

    const SORT_KEY = 'sort_order';

    const PROVIDER_KEY = 'provider';

    public function getName(): string;

    public function getNamespace($class = ''): string;

    public function getPath($path = '') : string;

    public function getRouteKey() : string;

    public function url($path = '', $extra = [], $secure = null) : string;

    public function asset($path = '', $secure = null);

    public function getVersion() : string;

    public function getStatus() : Status;

    public function setStatus(Status $status) : ModuleInterface;

    public function getDependencies() : array;

    public function getSort() : int;

    public function getProvider(): string;
}