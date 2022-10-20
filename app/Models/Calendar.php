<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'start_date',
        'start_end'
    ];

    public function calendarDates(): HasMany
    {
        return $this->HasMany(CalendarDates::class);
    }

    public function trips(): HasMany
    {
        return $this->HasMany(Trips::class);
    }
}
