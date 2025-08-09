<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'views',
        'likes',
        'reactions',
        'duration',
        'score',
        'published_at',
        'tags',
        'reading_time',
        'external_id',
        'provider_id'

    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array'
    ];


    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }


    public function calculateScore(): float
    {
        $score = 0;
        if ($this->type == 'video') {
            $score = $this->calculateVideoBasePoint() * 1.5;

            $score += $this->calculateVideoReactionScore();

            $score += $this->calculateFreshnessScore();

            return  number_format($score, 2, '.', '');
        }

        $score = $this->calculateArticleBasePoint();

        $score += $this->calculateArticleReactionScore();

        $score += $this->calculateFreshnessScore();

        return  number_format($score, 2, '.', '');
    }

    public function calculateVideoBasePoint()
    {
        return ($this->views / 1000) +  ($this->likes / 100);
    }

    public function calculateVideoReactionScore(): float
    {
        return ($this->likes / $this->views) * 10;
    }

    public function calculateArticleBasePoint()
    {
        return $this->reading_time + ($this->reactions / 50);
    }

    public function calculateArticleReactionScore(): float
    {
        return ($this->reactions / $this->reading_time) * 5;
    }


    public function calculateFreshnessScore()
    {
        $now = Carbon::now();
        $diffInDays = $this->published_at->diffIndays($now);
        if ($diffInDays <= 7) return 5;
        if ($diffInDays <= 30) return 3;
        if ($diffInDays <= 90) return 1;
        return 0;
    }
}
