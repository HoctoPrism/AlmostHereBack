<?php

namespace App\Http\Repositories;

use App\Interfaces\RouteRepositoryInterface;
use App\Models\Routes;
use Illuminate\Database\Eloquent\Collection;

class RouteRepository implements RouteRepositoryInterface {

    /**
     * Get All routes and shapes for the big map
     *
     * @return Collection|array
     */
    public function getAllRouteForTheBigMap(): Collection|array
    {
        $routes = Routes::query()
            ->select('route_id', 'route_long_name', 'route_color', 'trip_id', 'shape_id')
            ->join('trips', 'routes.route_id', '=', 'trips.route_id')
            ->groupBy('route_id')
            ->get();




        $result = [];

        return $routes;
    }

}
