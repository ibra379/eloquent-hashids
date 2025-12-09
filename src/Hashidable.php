<?php

namespace DialloIbrahima\EloquentHashids;

use DialloIbrahima\EloquentHashids\Contracts\HashidableConfigInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Trait to add hashid functionality to Eloquent models.
 *
 * @mixin Model
 */
trait Hashidable
{
    /**
     * Get the hashid for the model.
     */
    public function getHashidAttribute(): string
    {
        return $this->generateHashid($this->getKey());
    }

    /**
     * Get the route key for the model (used in route model binding).
     */
    public function getRouteKey(): mixed
    {
        return $this->hashid;
    }

    /**
     * Get the route key name for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'hashid';
    }

    /**
     * Resolve the route binding for the model.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if ($field && $field !== 'hashid') {
            return $this->where($field, $value)->first();
        }

        $id = $this->decodeHashid($value);

        if ($id === null) {
            return null;
        }

        return $this->where($this->getKeyName(), $id)->first();
    }

    /**
     * Find a model by its hashid.
     *
     * @return static|null
     */
    public static function findByHashid(string $hashid): ?Model
    {
        $instance = new static;
        $id = $instance->decodeHashid($hashid);

        if ($id === null) {
            return null;
        }

        return static::find($id);
    }

    /**
     * Find a model by its hashid or throw an exception.
     *
     * @return static
     *
     * @throws ModelNotFoundException
     */
    public static function findByHashidOrFail(string $hashid): Model
    {
        $model = static::findByHashid($hashid);

        if ($model === null) {
            throw (new ModelNotFoundException)->setModel(static::class, [$hashid]);
        }

        return $model;
    }

    /**
     * Generate a hashid from an ID.
     */
    protected function generateHashid(int|string $id): string
    {
        $config = $this->getHashidableConfig();
        $encoder = $this->getEncoderInstance($config);

        $hash = $encoder->encode((int) $id);

        return $this->formatHashid($hash, $config);
    }

    /**
     * Decode a hashid to get the original ID.
     */
    protected function decodeHashid(string $hashid): ?int
    {
        $config = $this->getHashidableConfig();
        $strippedHashid = $this->stripHashid($hashid, $config);

        $encoder = $this->getEncoderInstance($config);

        return $encoder->decode($strippedHashid);
    }

    /**
     * Get a HashidEncoder instance with the given configuration.
     *
     * @param  array<string, mixed>  $config
     */
    protected function getEncoderInstance(array $config): HashidEncoder
    {
        return new HashidEncoder(
            $config['salt'] ?? config('app.key', ''),
            $config['length'] ?? 16,
            $config['alphabet'] ?? null
        );
    }

    /**
     * Get the hashidable configuration for this model.
     *
     * @return array<string, mixed>
     */
    protected function getHashidableConfig(): array
    {
        $globalConfig = config('eloquent-hashids', []);

        if ($this instanceof HashidableConfigInterface) {
            return array_merge($globalConfig, $this->hashidableConfig());
        }

        return $globalConfig;
    }

    /**
     * Format a hashid with prefix and suffix.
     *
     * @param  array<string, mixed>  $config
     */
    protected function formatHashid(string $hash, array $config): string
    {
        $prefix = $config['prefix'] ?? '';
        $suffix = $config['suffix'] ?? '';
        $separator = $config['separator'] ?? '-';

        $result = $hash;

        if ($prefix !== '') {
            $result = $prefix . $separator . $result;
        }

        if ($suffix !== '') {
            $result = $result . $separator . $suffix;
        }

        return $result;
    }

    /**
     * Strip prefix and suffix from a hashid.
     *
     * @param  array<string, mixed>  $config
     */
    protected function stripHashid(string $hashid, array $config): string
    {
        $prefix = $config['prefix'] ?? '';
        $suffix = $config['suffix'] ?? '';
        $separator = $config['separator'] ?? '-';

        $result = $hashid;

        if ($prefix !== '' && str_starts_with($result, $prefix . $separator)) {
            $result = substr($result, strlen($prefix . $separator));
        }

        if ($suffix !== '' && str_ends_with($result, $separator . $suffix)) {
            $result = substr($result, 0, -strlen($separator . $suffix));
        }

        return $result;
    }
}
