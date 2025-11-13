<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    /**
     * Get cache key prefix for this model
     */
    protected function getCacheKeyPrefix(): string
    {
        return strtolower(class_basename($this)) . '_';
    }

    /**
     * Get cache key for specific item
     */
    public function getCacheKey(string $suffix = ''): string
    {
        $key = $this->getCacheKeyPrefix() . $this->getKey();
        return $suffix ? $key . '_' . $suffix : $key;
    }

    /**
     * Get cache tag for this model type
     */
    public static function getCacheTag(): string
    {
        return strtolower(class_basename(static::class)) . 's';
    }

    /**
     * Cache this model instance
     */
    public function cacheModel(int $ttl = 3600): self
    {
        Cache::tags([static::getCacheTag()])
            ->put($this->getCacheKey(), $this, $ttl);

        return $this;
    }

    /**
     * Get model from cache or database
     */
    public static function findCached($id, int $ttl = 3600)
    {
        $instance = new static;
        $cacheKey = $instance->getCacheKeyPrefix() . $id;

        return Cache::tags([static::getCacheTag()])
            ->remember($cacheKey, $ttl, function () use ($id) {
                return static::find($id);
            });
    }

    /**
     * Forget cached model
     */
    public function forgetCache(): bool
    {
        return Cache::tags([static::getCacheTag()])
            ->forget($this->getCacheKey());
    }

    /**
     * Flush all cache for this model type
     */
    public static function flushCache(): bool
    {
        return Cache::tags([static::getCacheTag()])->flush();
    }

    /**
     * Boot the cacheable trait
     */
    protected static function bootCacheable(): void
    {
        // Clear cache on model updates
        static::updated(function ($model) {
            $model->forgetCache();
        });

        // Clear cache on model deletion
        static::deleted(function ($model) {
            $model->forgetCache();
        });

        // Cache on model creation
        static::created(function ($model) {
            $model->cacheModel();
        });
    }
}
