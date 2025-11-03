<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStepStatus extends Controller
{
    public function checkStep()
    {
        $userId = Auth::id();

        $vehicle = Vehicle::where('user_id', $userId)->first();

        if (!$vehicle) {
            return response()->json([
                'success' => true,
                'step' => 0,
                'message' => 'No vehicle information found.'
            ], 200);
        }
        
        $appointment = Appointment::where('vehicle_id', $vehicle->id)->first();

        if (!$appointment) {
            return response()->json([
                'success' => true,
                'step' => 2,
                'message' => 'Vehicle info found, no appointment yet.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'step' => 3,
            'message' => 'Vehicle info and appointment found.'
        ], 200);
    }
}
