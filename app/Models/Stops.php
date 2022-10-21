<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stops extends Model
{
    use HasFactory;

    protected $primaryKey = 'stop_id';
    protected $fillable = [
        'stop_id',
        'stop_code',
        'stop_name',
        'stop_lat',
        'stop_lon',
        'location_type',
        'parent_station'
    ];

    public function parentStation(): BelongsTo
    {
        return $this->BelongsTo(Stops::class, 'parent_station');
    }

    public function stopTimes(): HasMany
    {
        return $this->HasMany(StopTimes::class);
    }
}
