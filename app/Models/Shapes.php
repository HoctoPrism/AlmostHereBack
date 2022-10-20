<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shapes extends Model
{
    use HasFactory;

    protected $fillable = ['shape_id', 'shape_pt_lat', 'shape_pt_lon', 'shape_pt_sequence'];

    public function trips(): HasMany
    {
        return $this->HasMany(Trips::class);
    }
}
