<?php

namespace DialloIbrahima\EloquentHashids\Contracts;

/**
 * Interface for models that want to define custom hashid configuration.
 */
interface HashidableConfigInterface
{
    /**
     * Get the hashidable configuration for this model.
     *
     * @return array<string, mixed>
     */
    public function hashidableConfig(): array;
}
