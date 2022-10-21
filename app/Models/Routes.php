<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Routes extends Model
{
    use HasFactory;

    protected $primaryKey = 'route_id';
    protected $fillable = [
        'route_id',
        'route_short_name',
        'route_long_name',
        'route_desc',
        'route_type',
        'route_url',
        'route_color',
        'route_text_color',
        'agency_id'
    ];

    public function agency(): BelongsTo
    {
        return $this->BelongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function trips(): HasMany
    {
        return $this->HasMany(Trips::class);
    }
}
