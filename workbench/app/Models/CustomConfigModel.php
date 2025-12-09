<?php

namespace Workbench\App\Models;

use DialloIbrahima\EloquentHashids\Contracts\HashidableConfigInterface;
use DialloIbrahima\EloquentHashids\Hashidable;
use Illuminate\Database\Eloquent\Model;

class CustomConfigModel extends Model implements HashidableConfigInterface
{
    use Hashidable;

    protected $table = 'test_models';

    protected $guarded = [];

    public function hashidableConfig(): array
    {
        return [
            'prefix' => 'custom',
            'suffix' => 'v1',
            'length' => 8,
            'separator' => '_',
        ];
    }
}
