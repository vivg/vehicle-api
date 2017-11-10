<?php

namespace App\Http\Controllers;

use App\Services\Nhtsa\Api as NhtsaApiService;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public $api;

    /**
     * VehicleController constructor.
     *
     * @param NhtsaApiService $api
     */
    public function __construct(NhtsaApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Handles get request for vehicle details.
     *
     * @param Request $request
     * @param $modelYear
     * @param $manufacturer
     * @param $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request, $modelYear, $manufacturer, $model)
    {
        $withRating = $request->input('withRating', false);
        $includeRating = ($withRating === 'true') ? true : false;

        $vehicles = $this->api->getVehicles($modelYear, $manufacturer, $model, $includeRating);

        return response()->json($vehicles);
    }


    /**
     * Handles post request for vehicle details.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postData(Request $request)
    {
        //get model year, manufacturer and model from request
        $modelYear = $request->input('modelYear', null);
        $manufacturer = $request->input('manufacturer', null);
        $model = $request->input('model', null);

        if (is_null($modelYear) || is_null($manufacturer) || is_null($model)) {
            return response()->json([
                'Count' => 0,
                'Results' => [],
            ]);
        }

        $vehicles = $this->api->getVehicles($modelYear, $manufacturer, $model);
        return response()->json($vehicles);
    }
}
