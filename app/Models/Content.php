<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory;

     protected $fillable = [
        'title', 'type', 'views', 'likes', 'duration', 'score', 'published_at', 'tags', 'reading_time', 'external_id', 'provider_id'
        
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array'
    ];


    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }


}
