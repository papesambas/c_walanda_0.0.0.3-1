<?php

namespace App\Service;

use App\Repository\PeresRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class PeresCacheService
{
    private const CACHE_TAG = 'peres_list';
    private const CACHE_KEY = 'peres_list_cache';
    private const CACHE_TTL = 3600; // 1 heure

    private TagAwareCacheInterface $cache;
    private PeresRepository $repository;
    private string $appVersion;

    public function __construct(
        TagAwareCacheInterface $cache,
        PeresRepository $repository,
        string $appVersion = 'v1' // Valeur par défaut
    ) {
        $this->cache = $cache;
        $this->repository = $repository;
        $this->appVersion = $appVersion;
    }

    public function getPeresList(): array
    {
        // Créer une clé unique incluant la version
        $cacheKey = self::CACHE_KEY . '_' . $this->appVersion;
        
        return $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->tag(self::CACHE_TAG);
            $item->expiresAfter(self::CACHE_TTL);
            return $this->repository->findForAll();
        });
    }
    
    public function invalidateCache(): void
    {
        $this->cache->invalidateTags([self::CACHE_TAG]);
    }
}