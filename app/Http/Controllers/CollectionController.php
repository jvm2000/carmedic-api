<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CollectionController extends Controller
{
   public function store(Request $request, Appointment $appointment) {
        $form = Validator::make($request->all(), [
            'scheduled_date' => 'required|date',
            'location' => 'required|string',
            'collected_by' => 'required|string',
            'towing_team_name' => 'required|string',
        ]);

        if ($form->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $form->errors(),
            ], 422);
        }

        $validated = $form->validated();    

        $collection = Collection::create([
            ...$validated,
            'appointment_id' => $appointment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Collection registered successfully.',
            'data'    => $collection
        ], 201);
    }
}
