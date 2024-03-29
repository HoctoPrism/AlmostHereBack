<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Frequencies extends Model
{
    use HasFactory;

    protected $primaryKey = 'frequencies_id';
    protected $fillable = ['frequencies_id', 'start_time', 'end_time', 'headway_secs', 'trip_id'];

    public function trip(): BelongsTo
    {
        return $this->BelongsTo(Trips::class, 'trip_id', 'trip_id');
    }
}
