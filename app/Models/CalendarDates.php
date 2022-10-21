<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarDates extends Model
{
    use HasFactory;

    protected $fillable = ['calendar_dates_id', 'date', 'exception_type', 'service_id'];

    public function service(): BelongsTo
    {
        return $this->BelongsTo(Calendar::class, 'service_id', 'service_id');
    }
}
