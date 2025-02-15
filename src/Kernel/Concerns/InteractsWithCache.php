<?php

/*
 * This file is part of the mingyoung/dingtalk.
 *
 * (c) 张铭阳 <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyDingTalk\Kernel\Concerns;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Simple\FilesystemCache;

trait InteractsWithCache
{
    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    /**
     * @return \Psr\SimpleCache\CacheInterface
     */
    public function getCache()
    {
        if ($this->cache) {
            return $this->cache;
        }

//         if (property_exists($this, 'app') && $this->app->offsetExists('cache') && ($this->app['cache'] instanceof CacheInterface)) {
//             return $this->cache = $this->app['cache'];
//         }
        
        if (property_exists($this, 'app') && isset($this->app['cache'])) {
            $this->cache = new Psr16Cache($this->app['cache']);

            // Fix PHPStan error
            assert($this->cache instanceof \Psr\SimpleCache\CacheInterface);

            return $this->cache;
        }

        return $this->cache = $this->createDefaultCache();
    }

    /**
     * @return \Psr\SimpleCache\CacheInterface
     */
    protected function createDefaultCache()
    {
        if (class_exists(Psr16Cache::class)) {
            return new Psr16Cache(new FilesystemAdapter('easydingtalk'));
        }

        return new FilesystemCache('easydingtalk');
    }
}
