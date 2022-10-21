<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StopTimes extends Model
{
    use HasFactory;

    protected $primaryKey = 'stop_times_id';
    protected $fillable = [
        'stop_times_id',
        'stop_sequence',
        'arrival_time',
        'departure_time',
        'stop_headsign',
        'pickup_type',
        'drop_off_type',
        'shape_dist_traveled',
        'stop_id',
        'trip_id'
    ];

    public function stop(): BelongsTo
    {
        return $this->BelongsTo(Stops::class, 'stop_id', 'stop_id');
    }

    public function trip(): BelongsTo
    {
        return $this->BelongsTo(Trips::class, 'trip_id', 'trip_id');
    }
}
