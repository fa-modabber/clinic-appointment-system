<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Services\CityService;
use App\Services\DoctorService;
use App\Services\SpecialtyService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected SpecialtyService $specialtyService,
        protected CityService $cityService,
        protected DoctorService $doctorService
    ) {}

    public function index(Request $request)
    {
        $cityId = (int) $request->query('city_id', config('app.default_city_id'));

        $cities = City::all();

        $specialties = $this->specialtyService->getAll();

        $randomDoctors = Doctor::where('city_id', $cityId)
            ->latest()
            ->take(8)
            ->get();


        return view('client.home', [
            'currentCity' => $cityId,
            'cities' => $cities,
            'specialties' => $specialties,
            'doctors' => $randomDoctors
        ]);
    }


    public function searchForm(Request $request)
    {
        dd($request->all());

        $request->validate([
           'doctor-specialty' => 'required|string',
           'city' => 'required|string',
        ]);

       
    }

    public function getDoctorsByCityAndSpecialty(?City $city, ?Specialty $specialty)
    {
        $doctors = $this->doctorService->getByCityAndSpecialty($city, $specialty);
        return view('client.home', [
            'doctors' => $doctors,
            'currentCity' => $city
        ]);
    }
}
