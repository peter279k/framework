<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Cache;

use Linna\Storage\AbstractStorageFactory;
use Psr\SimpleCache\CacheInterface;

/**
 * Storage Factory.
 */
class CacheFactory extends AbstractStorageFactory
{
    /**
     * @var array Factory supported driver
     */
    protected $supportedDriver = [
        'disk'       => DiskCache::class,
        'memcached'  => MemcachedCache::class,
    ];

    /**
     * Return Cache Resource.
     *
     * @return CacheInterface
     */
    public function get(): CacheInterface
    {
        return $this->returnStorageObject();
    }
}
