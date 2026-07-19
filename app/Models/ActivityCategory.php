<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'image_public_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}