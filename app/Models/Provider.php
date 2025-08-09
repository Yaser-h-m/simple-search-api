<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    /** @use HasFactory<\Database\Factories\ProviderFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'type',
        'api_key',
        'api_secret',
    ];

    public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
