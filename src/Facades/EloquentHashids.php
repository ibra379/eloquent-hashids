<?php

namespace DialloIbrahima\EloquentHashids\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DialloIbrahima\EloquentHashids\EloquentHashids
 */
class EloquentHashids extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DialloIbrahima\EloquentHashids\EloquentHashids::class;
    }
}
