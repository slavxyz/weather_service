<?php

namespace App\V1\Interfaces\Weather;

interface CacheInterface
{
    public function getItem(string $key);

    public function pushToCache(string $key, mixed $value): void;
}
