<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'type' => $vehicle->type,
                'capacity' => $vehicle->capacity,
                'status' => $vehicle->status,
                'latitude' => $vehicle->current_location->latitude,
                'longitude' => $vehicle->current_location->longitude,
                'created_at' => $vehicle->created_at,
                'updated_at' => $vehicle->updated_at,
            ];
        });

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles
        ]);
    }

    public function show(Vehicle $vehicle)
    {
        return Inertia::render('Vehicles/Show', [
            'vehicle' => [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'type' => $vehicle->type,
                'capacity' => $vehicle->capacity,
                'status' => $vehicle->status,
                'latitude' => $vehicle->current_location->latitude,
                'longitude' => $vehicle->current_location->longitude,
                'created_at' => $vehicle->created_at,
                'updated_at' => $vehicle->updated_at,
            ]
        ]);
    }

    public function updateLocation(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $vehicle->updateLocation($request->latitude, $request->longitude);

        return response()->json([
            'message' => 'Ubicación actualizada exitosamente',
            'vehicle' => [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'latitude' => $vehicle->current_location->latitude,
                'longitude' => $vehicle->current_location->longitude,
            ]
        ]);
    }

    public function nearbyVehicles(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:0.1|max:50000', // Radio en metros
        ]);

        $center = new Point($request->latitude, $request->longitude);

        $vehicles = Vehicle::withinRadius($center, $request->radius)
            ->active()
            ->get()
            ->map(function ($vehicle) use ($center) {
                return [
                    'id' => $vehicle->id,
                    'license_plate' => $vehicle->license_plate,
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'type' => $vehicle->type,
                    'status' => $vehicle->status,
                    'latitude' => $vehicle->current_location->latitude,
                    'longitude' => $vehicle->current_location->longitude,
                    'distance_meters' => round($vehicle->getDistanceToPoint($center), 2),
                ];
            });

        return response()->json([
            'center' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ],
            'radius_meters' => $request->radius,
            'vehicles_found' => $vehicles->count(),
            'vehicles' => $vehicles
        ]);
    }
}
