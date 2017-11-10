# Vehicles Data Api
Simple api to get vehicles data and its crash rating based on model year, manufacturer and model. Powered by Laravel 5.5

## Server Requirements
Laravel 5.5 requires the server to meet the following requirements:
- PHP >= 7.0.0
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installation
```
$ git clone https://github.com/vivg/vehicle-api
$ cd vehicle-api
$ composer install
```

**Note:** Usually, **.env** isn't committed to the repository because it contains sensitive information. It's been included here only for testing purposes.


## Available APIs
| API endpoints                                        | Method | Description                      | Options/Parameters                                                                                                                       |
|------------------------------------------------------|--------|----------------------------------|----------------------------------------------------------------------------------------------------------------------------------|
| /vehicles/\<MODEL YEAR\>/\<MANUFACTURER\>/\<MODEL\>  | GET    | Returns a list of vehicle data   | `?withRating=true` will include crash rating of vehicle                                                                          |
| /vehicles                                            | POST   | Returns a list of vehicle data   | Pass `{ "modelYear": "<MODEL YEAR>", "manufacturer": "<MANUFACTURER>", "model": "<MODEL>"}` as json body to get vehicle details  |


### Usage Example
**Endpoint:** `GET /vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>`
- Default
```
$ curl -X GET \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    http://localhost:8080/vehicles/2015/Audi/A3
```

Output: 
```
{
    "Count": 4,
    "Results": [
        {
            "Description": "2015 Audi A3 4 DR AWD",
            "VehicleId": 9403
        },
        {
            "Description": "2015 Audi A3 4 DR FWD",
            "VehicleId": 9408
        },
        {
            "Description": "2015 Audi A3 C AWD",
            "VehicleId": 9405
        },
        {
            "Description": "2015 Audi A3 C FWD",
            "VehicleId": 9406
        }
    ]
}
```

- With Rating
```
$ curl -X GET \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    http://localhost:8080/vehicles/2015/Audi/A3?withRating=true
``` 

Output:
```
{
    "Count": 4,
    "Results": [
        {
            "Description": "2015 Audi A3 4 DR AWD",
            "VehicleId": 9403,
            "CrashRating": "5"
        },
        {
            "Description": "2015 Audi A3 4 DR FWD",
            "VehicleId": 9408,
            "CrashRating": "5"
        },
        {
            "Description": "2015 Audi A3 C AWD",
            "VehicleId": 9405,
            "CrashRating": "Not Rated"
        },
        {
            "Description": "2015 Audi A3 C FWD",
            "VehicleId": 9406,
            "CrashRating": "Not Rated"
        }
    ]
}
```

**Endpoint:** `POST /vehicles`
```
$ curl -X POST \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"modelYear": 2015, "manufacturer": "Audi", "model": "A3"}' \
    http://localhost:8000/vehicles
```
Output:
```
{
    "Count": 4,
    "Results": [
        {
            "Description": "2015 Audi A3 4 DR AWD",
            "VehicleId": 9403
        },
        {
            "Description": "2015 Audi A3 4 DR FWD",
            "VehicleId": 9408
        },
        {
            "Description": "2015 Audi A3 C AWD",
            "VehicleId": 9405
        },
        {
            "Description": "2015 Audi A3 C FWD",
            "VehicleId": 9406
        }
    ]
}
``` 