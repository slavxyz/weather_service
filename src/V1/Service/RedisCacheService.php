<?php

namespace App\V1\Service;

use App\V1\Interfaces\Weather\CacheInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class RedisCacheService implements CacheInterface
{
    public function __construct(
        private TagAwareCacheInterface $cache
    ) {}

    /**
     * Get item by key
     *
     * @param string $key
     * @return ItemInterface
     */
    public function getItem(string $key): ItemInterface
    {
        return $this->cache->getItem($key);
    }

    /**
     * Store in cache
     *
     * @param ItemInterface $item
     * @return boolean
     */
    public function save(ItemInterface $item): bool
    {
        return $this->cache->save($item);
    }
  
    /**
     * Set item value and store in cache
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function pushToCache(string $key, mixed $value): void
    {
        $item = $this->cache->getItem($key);

        $item->set($value);
        $this->cache->save($item);
    }

    /**
     * Get the full history array from cache
     *
     * @param string $key
     * @return array
     */
    public function getHistory(string $key): array
    {
        $item = $this->cache->getItem($key);

        return $item->isHit() ? $item->get() : [];
    }
   
}
