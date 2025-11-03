<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function get()
    {
        $user = Auth::id();

        $appointment = Appointment::where('user_id', $user)->first();

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
        $form = Validator::make($request->all(), [
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required|tune',
            'additional_notes' => 'required|string',
        ]);

        if ($form->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $form->errors(),
            ], 422);
        }

        $validated = $form->validated();    

        $appointment = Appointment::create([
            ...$validated,
            'vehicle_id' => $vehicle,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment registered successfully.',
            'data'    => $appointment
        ], 201);
    }
}
