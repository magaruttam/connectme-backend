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
        'location',
        'latitude',
        'longitude',
        'start_time',
        'end_time',
        'max_participants',
        'is_free',
        'price',
        'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    /**
     * The profile that hosts this activity.
     */
    public function host()
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
