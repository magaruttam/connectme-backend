<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ActivityCategory;

class Activity extends Model
{
   protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'description',
    'cover_image_url',
    'cover_image_public_id',
    'location_name',
    'latitude',
    'longitude',
    'start_at',
    'end_at',
    'max_participants',
    'is_free',
    'price',
    'status',
];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    /**
     * The profile that hosts this activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     /**
     * Category of the activity.
     */
    public function category()
    {
        return $this->belongsTo(ActivityCategory::class);
    }

}
