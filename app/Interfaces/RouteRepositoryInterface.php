<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface RouteRepositoryInterface
{
    public function getAllRouteForTheBigMap(): Collection|array;
}
