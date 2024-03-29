<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trips extends Model
{
    use HasFactory;

    protected $primaryKey = 'trip_id';
    protected $fillable = ['trip_id', 'trip_headsign', 'direction_id', 'shape_id', 'route_id', 'service_id'];

    public function shape(): HasMany
    {
        return $this->HasMany(Shapes::class, 'shape_id', 'shape_id');
    }

    public function route(): BelongsTo
    {
        return $this->BelongsTo(Routes::class, 'route_id', 'route_id');
    }

    public function calendar(): BelongsTo
    {
        return $this->BelongsTo(Calendar::class, 'service_id', 'service_id');
    }

    public function stopTimes(): HasMany
    {
        return $this->HasMany(StopTimes::class, 'trip_id', 'trip_id');
    }

    public function calendars(): HasMany
    {
        return $this->HasMany(Calendar::class);
    }
}
