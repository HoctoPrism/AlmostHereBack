<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = ['agency_id', 'agency_name', 'agency_url', 'agency_fare_url', 'agency_timezone', 'agency_phone', 'agency_lang'];

    public function routes(): HasMany
    {
        return $this->HasMany(Routes::class);
    }
}
