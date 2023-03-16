<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ItinaryRepository;
use App\Models\Agency;
use App\Models\StopTimes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class ItinaryController extends Controller
{
    private ItinaryRepository $itinaryRepository;
    public function __construct(ItinaryRepository $itinaryRepository)
    {
        $this->itinaryRepository = $itinaryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param float|int|string $startPointLat
     * @param float|int|string $startPointLong
     * @param float|int|string $endPointLat
     * @param float|int|string $endPointLong
     * @return JsonResponse
     */
    public function index(float|int|string $startPointLat, float|int|string $startPointLong, float|int|string $endPointLat, float|int|string $endPointLong): JsonResponse
    {
        $startingPoint  = $this->itinaryRepository->getStopsForItinary($startPointLat, $startPointLong);
        $startingPointTrips = $this->itinaryRepository->getTripsNamesFromStopsForItinary($startingPoint);

        $closestEndpointFromDestination = $this->itinaryRepository->getNearestEndingStopsForItinary($startingPointTrips, $endPointLat, $endPointLong);

        return response()->json([
            'status' => 'Success',
            'data' => $this->filterLinesAndKeepTheShorterOneOfEach($closestEndpointFromDestination, $startingPointTrips, $startingPoint)
        ]);
    }

    /**
     * @param array|Collection $closestEndpointFromDestination
     * @param array|Collection $startingPointTrips
     * @param array|Collection $startingPoint
     * @return array
     */
    public function filterLinesAndKeepTheShorterOneOfEach(
        Collection|array $closestEndpointFromDestination,
        Collection|array $startingPointTrips,
        Collection|array $startingPoint
    ): array
    {
        $oneTripPerResult = [];

        // One loop to polulate the array with the first result of each line
        foreach ($closestEndpointFromDestination as $destination) {
            foreach ($startingPointTrips as $trip) {
                if ($trip->route_long_name === $destination->route_long_name) {
                    $startPt = $startingPoint->firstWhere('stop_id', $trip->stop_id);
                    $oneTripPerResult[$destination->route_long_name] = [
                        'route_long_name' => $destination->route_long_name,
                        'start_point' => $startPt,
                        'end_point' => $destination
                    ];
                }
            }
        }

        // Second loop to check if the distance is shorter than the one already in the array
        foreach ($closestEndpointFromDestination as $destination) {
            foreach ($startingPointTrips as $trip) {
                if ($trip->route_long_name === $destination->route_long_name) {
                    if ($oneTripPerResult[$destination->route_long_name]['start_point']->distance > $startingPoint->firstWhere('stop_id', $trip->stop_id)->distance) {
                        $startPt = $startingPoint->firstWhere('stop_id', $trip->stop_id);
                        $oneTripPerResult[$destination->route_long_name] = [
                            'route_long_name' => $destination->route_long_name,
                            'start_point' => $startPt,
                            'end_point' => $destination
                        ];
                    }
                }
            }
        }

        return $oneTripPerResult;
    }


}
