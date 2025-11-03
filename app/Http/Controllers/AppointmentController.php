<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function get(Vehicle $vehicle)
    {
        $appointment = Appointment::where('vehicle_id', $vehicle->id)->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'No appointment found for this user.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $appointment
        ], 200);
    }
    
    public function store(Request $request, Vehicle $vehicle) {
        $form = $request->validate([
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required|date_format:H:i:s',
            'additional_notes' => 'required|string',
        ]);

        $form['vehicle_id'] = $vehicle->id;  

        $appointment = Appointment::create($form);

        return response()->json([
            'success' => true,
            'message' => 'Appointment registered successfully.',
            'data'    => $appointment
        ], 201);
    }
}
