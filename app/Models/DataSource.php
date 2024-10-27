<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    public $guarded = [];
    public $casts = [
        'schema' => 'array',
    ];
}
