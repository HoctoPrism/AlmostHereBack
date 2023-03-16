<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ItinaryRepositoryInterface
{
    public function getStopsForItinary(float|int|string $lat, float|int|string $long): Collection|array;
}
