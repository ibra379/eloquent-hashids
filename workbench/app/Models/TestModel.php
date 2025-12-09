<?php

namespace Workbench\App\Models;

use DialloIbrahima\EloquentHashids\Hashidable;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use Hashidable;

    protected $table = 'test_models';

    protected $guarded = [];
}
