<?php

namespace App\Services\Nhtsa;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class Api
{
    /**
     * NHTSA base url.
     *
     * @var string
     */
    protected $baseUrl = 'https://one.nhtsa.gov';

    /**
     * @var Client
     */
    protected $client;

    /**
     * NHTSA Api constructor.
     */
    public function __construct()
    {
        // Create guzzle client
        $this->client = new Client([
            'base_uri' => $this->baseUrl
        ]);
    }

    /**
     * Get details of vehicles by model year, make, and model.
     *
     * @param $modelYear
     * @param $make
     * @param $model
     * @param bool $includeRating
     *
     * @return array
     */
    public function getVehicles($modelYear, $make, $model, $includeRating = false)
    {
        try {
            $vehicles = [
                'Count' => 0,
                'Results' => [],
            ];

            $requestUrl = "/webapi/api/SafetyRatings/modelyear/{$modelYear}/make/{$make}/model/{$model}?format=json";
            $response = $this->client->get($requestUrl);

            $responseObject = json_decode($response->getBody()->getContents());

            $results = [];

            foreach ($responseObject->Results as $index => $vehicle) {
                $results[$index]['Description'] = $vehicle->VehicleDescription;
                $results[$index]['VehicleId'] = $vehicle->VehicleId;
            }

            $vehicles['Count'] = $responseObject->Count;
            $vehicles['Results'] = $results;

            if ($includeRating) {
                $vehicles = $this->getRatingForVehicles($vehicles);
            }

            return $vehicles;

        } catch (Exception $e) {
            return [
                'Count' => 0,
                'Results' => [],
            ];
        }

    }

    /**
     * Include ratings for the vehicles.
     *
     * @param array $vehicles
     *
     * @return array
     * @throws Exception
     */
    protected function getRatingForVehicles($vehicles = [])
    {
        try {
            if (!empty($vehicles['Count'])) {
                $promises = [];

                foreach ($vehicles['Results'] as $vehicle) {
                    $requestUrl = "/webapi/api/SafetyRatings/VehicleId/{$vehicle['VehicleId']}?format=json";
                    $promises[$vehicle['VehicleId']] = $this->client->getAsync($requestUrl);
                }

                $results = Promise\unwrap($promises);

                foreach ($vehicles['Results'] as $index => $vehicle) {
                    $rating = json_decode($results[$vehicle['VehicleId']]->getBody()->getContents());
                    $vehicles['Results'][$index]['CrashRating'] = $rating->Results[0]->OverallRating;
                }
            }

            return $vehicles;

        } catch (Exception $e) {
            throw $e;
        }
    }

}