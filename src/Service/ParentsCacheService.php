<?php
// src/Service/ParentsCacheService.php

namespace App\Service;

use App\Repository\ParentsRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ParentsCacheService
{
    private const CACHE_TAG = 'parents_list';
    private const CACHE_KEY = 'parents_list_cache';
    private const CACHE_TTL = 3600;

    public function __construct(
        private TagAwareCacheInterface $cache,
        private ParentsRepository       $repository,
        private string                  $appVersion = 'v1'
    ) {}

    public function getParentsList(): array
    {
        $cacheKey = self::CACHE_KEY . '_' . $this->appVersion;
        
        return $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->tag(self::CACHE_TAG);
            $item->expiresAfter(self::CACHE_TTL);

            return $this->repository->findForAll();
        });
    }

    // Invalidation par tag
    public function invalidateCache(): void
    {
        $this->cache->invalidateTags([self::CACHE_TAG]);
    }

    // Si vous préférez invalider par clé plutôt que par tag :
    public function clearParentsList(): void
    {
        $cacheKey = self::CACHE_KEY . '_' . $this->appVersion;
        $this->cache->delete($cacheKey);
    }
}
