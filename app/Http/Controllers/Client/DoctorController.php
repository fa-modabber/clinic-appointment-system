<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function show(Doctor $doctor){
        return view('client.doctor.index' , [
            'doctor' => $doctor,
            'profile' => $doctor->profile
        ]);
    }
}
