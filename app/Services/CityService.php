<?php

namespace App\Services;

use App\Models\City;

class CityService {

    public function index(){
        return City::all();
    }
}
