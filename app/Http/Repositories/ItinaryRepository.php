<?php

namespace App\Http\Repositories;

use App\Interfaces\ItinaryRepositoryInterface;
use App\Models\Shapes;
use App\Models\Stops;
use App\Models\StopTimes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItinaryRepository implements ItinaryRepositoryInterface {

    /**
     * Get the first 5 closest stops for itinary
     *
     * @param float|int|string $lat
     * @param float|int|string $long
     * @return Collection|array
     */
    public function getStopsForItinary(float|int|string $lat, float|int|string $long): Collection|array
    {
        $lat = number_format($lat, 8);
        $long = number_format($long, 8);

        return Stops::query()
            ->selectRaw("stop_name, MAX(stop_id) as stop_id, MAX(stop_lat) as stop_lat, MAX(stop_lon) as stop_lon, TRUNCATE(MIN( 3959 * acos( cos( radians($lat) ) * cos( radians( stop_lat ) ) *
                cos( radians( stop_lon ) - radians($long) ) + sin( radians($lat) ) *
                sin( radians( stop_lat ) ) ) ), 3) AS distance"
            )
            ->groupBy('stop_id', 'stop_name')
            ->having('distance', '<', 25)
            ->orderBy('distance')
            ->limit(25)
            ->get();
    }

    /**
     * Get the first 5 closest stops for destination position on available trips
     *
     * @param array|Collection $tripsAvailableFromStartPosition
     * @param float|int|string $lat
     * @param float|int|string $long
     * @return Collection|array
     */
    public function getNearestEndingStopsForItinary(array|Collection $tripsAvailableFromStartPosition,float|int|string $lat, float|int|string $long): Collection|array
    {
        $lat = number_format($lat, 8);
        $long = number_format($long, 8);

        return Stops::query()
            ->selectRaw("route_long_name, MAX(trips.shape_id) as shape_id, route_color, stop_name, MAX(stop_lat) as stop_lat, MAX(stop_lon) as stop_lon, TRUNCATE(MIN( 3959 * acos( cos( radians($lat) ) * cos( radians( stop_lat ) ) *
                cos( radians( stop_lon ) - radians($long) ) + sin( radians($lat) ) *
                sin( radians( stop_lat ) ) ) ), 3) AS distance"
            )
            ->join('stop_times', 'stops.stop_id', '=', 'stop_times.stop_id')
            ->join('trips', 'stop_times.trip_id', '=', 'trips.trip_id')
            ->join('routes', 'trips.route_id', '=', 'routes.route_id')
            ->whereIn('route_long_name', $tripsAvailableFromStartPosition->pluck('route_long_name'))
            ->groupBy('stop_name', 'route_long_name', 'route_color')
            ->having('distance', '<', 25)
            ->orderBy('distance')
            ->limit(5)
            ->get();
    }

    /**
     * Get trips from a list of stops
     *
     * @param Collection|array $stops
     * @return Collection|array
     */
    public function getTripsNamesFromStopsForItinary(Collection|array $stops): Collection|array
    {
        return StopTimes::query()
            ->selectRaw('route_long_name, MAX(stop_id) as stop_id')
            ->join('trips', 'stop_times.trip_id', '=', 'trips.trip_id')
            ->join('routes', 'trips.route_id', '=', 'routes.route_id')
            ->whereIn('stop_id', $stops->pluck('stop_id'))
            ->groupBy('stop_id', 'route_long_name')
            ->orderBy('stop_id')
            ->get();
    }

    /**
     * Get trips from a list of stops
     *
     * @param int|string $shape_id
     * @param float|int|string $startPtlat
     * @param float|int|string $startPtlon
     * @param float|int|string $destinationlat
     * @param float|int|string $destinationlon
     * @return Builder|Collection
     */
    public function getShapesFromTripIdForItinary(
        int|string $shape_id,
        float|int|string $startPtlat,
        float|int|string $startPtlon,
        float|int|string $destinationlat,
        float|int|string $destinationlon
    ): Collection|array
    {
        $temp = [];
        $result = [];

        $queryStart = Shapes::query()
            ->selectRaw("shape_pt_sequence, MAX(shape_pt_lat) as shape_pt_lat, MAX(shape_pt_lon) as shape_pt_lon, TRUNCATE(MIN( 3959 * acos( cos( radians($startPtlat) ) * cos( radians( shape_pt_lat ) ) *
                cos( radians( shape_pt_lon ) - radians($startPtlon) ) + sin( radians($startPtlat) ) *
                sin( radians( shape_pt_lat ) ) ) ), 3) AS distance"
            )
            ->groupBy('shape_pt_sequence')
            ->where('shape_id', $shape_id)
            ->orderBy('distance')
            ->limit(1)
            ->get();

        $queryEnd = Shapes::query()
            ->selectRaw("shape_pt_sequence, MAX(shape_pt_lat) as shape_pt_lat, MAX(shape_pt_lon) as shape_pt_lon, TRUNCATE(MIN( 3959 * acos( cos( radians($destinationlat) ) * cos( radians( shape_pt_lat ) ) *
                cos( radians( shape_pt_lon ) - radians($destinationlon) ) + sin( radians($destinationlat) ) *
                sin( radians( shape_pt_lat ) ) ) ), 3) AS distance"
            )
            ->groupBy('shape_pt_sequence')
            ->where('shape_id', $shape_id)
            ->orderBy('distance')
            ->limit(1)
            ->get();
        if ($queryStart[0]->shape_pt_sequence < $queryEnd[0]->shape_pt_sequence) {
            $temp[] = [$queryStart[0]->shape_pt_sequence, $queryEnd[0]->shape_pt_sequence];
        } else {
            $temp[] = [$queryEnd[0]->shape_pt_sequence, $queryStart[0]->shape_pt_sequence];
        }


        $queryFinal = Shapes::query()
            ->select('shape_pt_lat', 'shape_pt_lon')
            ->where('shape_id', $shape_id)
            ->whereBetween('shape_pt_sequence', $temp[0])
            ->get();

        foreach ($queryFinal as $shape) {
            $shape->shape_pt_lat = number_format($shape->shape_pt_lat, 8);
            $shape->shape_pt_lon = number_format($shape->shape_pt_lon, 8);
            $result[] = [$shape->shape_pt_lon, $shape->shape_pt_lat];
        }

        return $result;
    }

}
