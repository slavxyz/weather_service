<?php

namespace App\V1\Infrastructure\Cache;

interface CacheInterface
{
    public function getItem(string $key);

    public function pushToCache(string $key, mixed $value): void;
}
