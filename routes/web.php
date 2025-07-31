<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para el sistema de geolocalización de vehículos
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::patch('/vehicles/{vehicle}/location', [VehicleController::class, 'updateLocation'])->name('vehicles.update-location');
    Route::get('/api/vehicles/nearby', [VehicleController::class, 'nearbyVehicles'])->name('vehicles.nearby');
});

// Rutas de prueba para funcionalidades espaciales (sin autenticación para facilitar pruebas)
Route::get('/test-spatial', function () {
    $vehicles = \App\Models\Vehicle::all()->map(function ($vehicle) {
        return [
            'id' => $vehicle->id,
            'license_plate' => $vehicle->license_plate,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'type' => $vehicle->type,
            'status' => $vehicle->status,
            'latitude' => $vehicle->current_location->latitude,
            'longitude' => $vehicle->current_location->longitude,
        ];
    });

    return response()->json([
        'message' => 'Prueba de funcionalidades espaciales exitosa',
        'total_vehicles' => $vehicles->count(),
        'vehicles' => $vehicles
    ]);
})->name('test.spatial');

Route::get('/test-nearby', function (Illuminate\Http\Request $request) {
    $request->validate([
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'radius' => 'required|numeric|min:0.1|max:50000',
    ]);

    $center = new \MatanYadaev\EloquentSpatial\Objects\Point($request->latitude, $request->longitude);

    $vehicles = \App\Models\Vehicle::withinRadius($center, $request->radius)
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
})->name('test.nearby');

// Ruta para simular actualización de posición GPS (sin CSRF para pruebas)
Route::post('/test-update-location/{vehicle}', function (\App\Models\Vehicle $vehicle, Illuminate\Http\Request $request) {
    $request->validate([
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
    ]);

    $newLocation = new \MatanYadaev\EloquentSpatial\Objects\Point($request->latitude, $request->longitude);
    $vehicle->current_location = $newLocation;
    $vehicle->save();

    return response()->json([
        'message' => 'Ubicación actualizada exitosamente',
        'vehicle' => [
            'id' => $vehicle->id,
            'license_plate' => $vehicle->license_plate,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'new_location' => [
                'latitude' => $vehicle->current_location->latitude,
                'longitude' => $vehicle->current_location->longitude,
            ],
            'updated_at' => $vehicle->updated_at->toISOString(),
        ]
    ]);
})->name('test.update.location');

// Ruta para obtener el estado de la flota
Route::get('/test-fleet-status', function () {
    $vehicles = \App\Models\Vehicle::all();
    $activeVehicles = $vehicles->where('status', 'active');
    $maintenanceVehicles = $vehicles->where('status', 'maintenance');

    // Calcular centro geográfico de la flota activa
    $centerLat = $activeVehicles->avg(fn($v) => $v->current_location->latitude);
    $centerLng = $activeVehicles->avg(fn($v) => $v->current_location->longitude);

    return response()->json([
        'fleet_summary' => [
            'total_vehicles' => $vehicles->count(),
            'active_vehicles' => $activeVehicles->count(),
            'maintenance_vehicles' => $maintenanceVehicles->count(),
            'fleet_center' => [
                'latitude' => round($centerLat, 6),
                'longitude' => round($centerLng, 6),
            ]
        ],
        'vehicles' => $vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'type' => $vehicle->type,
                'status' => $vehicle->status,
                'capacity' => $vehicle->capacity,
                'location' => [
                    'latitude' => $vehicle->current_location->latitude,
                    'longitude' => $vehicle->current_location->longitude,
                ],
                'last_update' => $vehicle->updated_at->toISOString(),
            ];
        })
    ]);
})->name('test.fleet.status');

// Ruta GET para simular actualización de posición GPS (sin CSRF)
Route::get('/test-update-location-get/{vehicle}', function (\App\Models\Vehicle $vehicle, Illuminate\Http\Request $request) {
    $request->validate([
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
    ]);

    $newLocation = new \MatanYadaev\EloquentSpatial\Objects\Point($request->latitude, $request->longitude);
    $vehicle->current_location = $newLocation;
    $vehicle->save();

    return response()->json([
        'message' => 'Ubicación actualizada exitosamente',
        'vehicle' => [
            'id' => $vehicle->id,
            'license_plate' => $vehicle->license_plate,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'new_location' => [
                'latitude' => $vehicle->current_location->latitude,
                'longitude' => $vehicle->current_location->longitude,
            ],
            'updated_at' => $vehicle->updated_at->toISOString(),
        ]
    ]);
})->name('test.update.location.get');

// Página de demostración del sistema de geolocalización
Route::get('/demo', function () {
    return Inertia::render('demo/GeoDemo');
})->name('demo.geo');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
