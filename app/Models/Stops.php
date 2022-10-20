<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stops extends Model
{
    use HasFactory;

    protected $fillable = [
        'stop_id',
        'stop_code',
        'stop_name',
        'stop_lat',
        'stop_lon',
        'location_type',
        'parent_id'
    ];

    public function parentStop(): BelongsTo
    {
        return $this->BelongsTo(Stops::class, 'parent_id');
    }

    public function stopTimes(): HasMany
    {
        return $this->HasMany(StopTimes::class);
    }
}
