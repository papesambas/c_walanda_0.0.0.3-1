<?php

namespace App\Service;

use App\Repository\MeresRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class MeresCacheService
{
    private const CACHE_TAG = 'meres_list';
    private const CACHE_KEY = 'meres_list_cache';
    private const CACHE_TTL = 3600; // 1 heure

    private TagAwareCacheInterface $cache;
    private MeresRepository $repository;
    private string $appVersion;

    public function __construct(
        TagAwareCacheInterface $cache,
        MeresRepository $repository,
        string $appVersion = 'v1' // Valeur par défaut
    ) {
        $this->cache = $cache;
        $this->repository = $repository;
        $this->appVersion = $appVersion;
    }

    public function getMeresList(): array
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