<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function store(Request $request) {
        $form = Validator::make($request->all(), [
            'plate_number' => 'required|string',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|string',
            'registration_card_number' => 'required|string',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($form->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $form->errors(),
            ], 422);
        }

        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('vehicles', 'public');
                $imagePaths[] = $path;
            }
        }

        $form['images'] = $imagePaths;

        $data = $form->validated();

        $data['user_id'] = Auth::id();

        $vehicle = Vehicle::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle registered successfully.',
            'data'    => $vehicle
        ], 201);
    }
}
