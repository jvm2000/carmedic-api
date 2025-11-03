<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function get()
    {
        $user = Auth::id();

        $vehicle = Vehicle::where('user_id', $user)->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'No vehicle found for this user.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vehicle
        ], 200);
    }

    public function store(Request $request) {
        $form = $request->validate([
            'plate_number' => 'required|string',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|string',
            'registration_card_number' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('vehicles', 'public');
                $imagePaths[] = $path;
            }
        }

        $form['images'] = $imagePaths;
        $form['user_id'] = Auth::id();

        $vehicle = Vehicle::create($form);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle registered successfully.',
            'data'    => $vehicle
        ], 201);
    }
}
