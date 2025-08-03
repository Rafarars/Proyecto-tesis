<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('driver')
            ->where('is_active', true)
            ->get()
            ->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'license_plate' => $vehicle->license_plate,
                    'code' => $vehicle->code,
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'year' => $vehicle->year,
                    'color' => $vehicle->color,
                    'type' => $vehicle->type,
                    'capacity_tons' => $vehicle->capacity_tons,
                    'fuel_type' => $vehicle->fuel_type,
                    'status' => $vehicle->status,
                    'driver' => $vehicle->driver ? [
                        'id' => $vehicle->driver->id,
                        'full_name' => $vehicle->driver->full_name,
                        'employee_code' => $vehicle->driver->employee_code,
                        'phone' => $vehicle->driver->phone,
                        'license_status' => $vehicle->driver->license_status,
                    ] : null,
                    'current_location' => $vehicle->current_location,
                    'current_speed' => $vehicle->current_speed,
                    'odometer_km' => $vehicle->odometer_km,
                    'last_maintenance' => $vehicle->last_maintenance,
                    'next_maintenance' => $vehicle->next_maintenance,
                    'maintenance_status' => $vehicle->maintenance_status,
                    'insurance_expiry' => $vehicle->insurance_expiry,
                    'location_updated_at' => $vehicle->location_updated_at,
                    'created_at' => $vehicle->created_at,
                    'updated_at' => $vehicle->updated_at,
                ];
            });

        return Inertia::render('vehicles/Index', [
            'vehicles' => $vehicles
        ]);
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['driver', 'maintenanceRecords' => function($query) {
            $query->latest()->limit(5);
        }, 'currentRoute']);

        return Inertia::render('vehicles/Show', [
            'vehicle' => [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'code' => $vehicle->code,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'color' => $vehicle->color,
                'vin' => $vehicle->vin,
                'type' => $vehicle->type,
                'capacity_tons' => $vehicle->capacity_tons,
                'fuel_capacity_liters' => $vehicle->fuel_capacity_liters,
                'fuel_type' => $vehicle->fuel_type,
                'engine' => $vehicle->engine,
                'status' => $vehicle->status,
                'driver' => $vehicle->driver ? [
                    'id' => $vehicle->driver->id,
                    'full_name' => $vehicle->driver->full_name,
                    'employee_code' => $vehicle->driver->employee_code,
                    'phone' => $vehicle->driver->phone,
                    'license_number' => $vehicle->driver->license_number,
                    'license_type' => $vehicle->driver->license_type,
                    'license_expiry' => $vehicle->driver->license_expiry,
                    'license_status' => $vehicle->driver->license_status,
                ] : null,
                'current_location' => $vehicle->current_location,
                'current_speed' => $vehicle->current_speed,
                'odometer_km' => $vehicle->odometer_km,
                'last_maintenance' => $vehicle->last_maintenance,
                'next_maintenance' => $vehicle->next_maintenance,
                'maintenance_status' => $vehicle->maintenance_status,
                'purchase_date' => $vehicle->purchase_date,
                'purchase_price' => $vehicle->purchase_price,
                'insurance_policy' => $vehicle->insurance_policy,
                'insurance_expiry' => $vehicle->insurance_expiry,
                'notes' => $vehicle->notes,
                'location_updated_at' => $vehicle->location_updated_at,
                'assigned_route' => $vehicle->currentRoute->first() ? [
                    'id' => $vehicle->currentRoute->first()->id,
                    'name' => $vehicle->currentRoute->first()->name,
                    'code' => $vehicle->currentRoute->first()->code,
                    'status' => $vehicle->currentRoute->first()->status,
                    'collection_points_count' => $vehicle->currentRoute->first()->collection_points_count,
                    'total_distance_km' => $vehicle->currentRoute->first()->total_distance_km,
                    'estimated_duration_formatted' => $vehicle->currentRoute->first()->estimated_duration_formatted,
                ] : null,
                'maintenance_records' => $vehicle->maintenanceRecords->map(function($record) {
                    return [
                        'id' => $record->id,
                        'record_number' => $record->record_number,
                        'type' => $record->type,
                        'status' => $record->status,
                        'scheduled_date' => $record->scheduled_date,
                        'completion_date' => $record->completion_date,
                        'description' => $record->description,
                        'total_cost' => $record->total_cost,
                    ];
                }),
                'created_at' => $vehicle->created_at,
                'updated_at' => $vehicle->updated_at,
            ]
        ]);
    }

    public function create()
    {
        $drivers = Driver::active()->get()->map(function($driver) {
            return [
                'id' => $driver->id,
                'full_name' => $driver->full_name,
                'employee_code' => $driver->employee_code,
                'license_status' => $driver->license_status,
            ];
        });

        return Inertia::render('vehicles/Create', [
            'drivers' => $drivers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:255',
            'vin' => 'nullable|string|max:255',
            'type' => 'required|in:compactador,camion,pickup,van,volqueta',
            'capacity_tons' => 'required|numeric|min:0.1|max:50',
            'fuel_capacity_liters' => 'required|numeric|min:10|max:1000',
            'fuel_type' => 'required|in:gasolina,diesel,gas,electrico',
            'engine' => 'nullable|string|max:255',
            'driver_id' => 'nullable|exists:drivers,id',
            'odometer_km' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'insurance_policy' => 'nullable|string|max:255',
            'insurance_expiry' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        // Generar código automáticamente
        $validated['code'] = $this->generateVehicleCode();

        // Si no se proporciona kilometraje, establecer en 0
        if (!isset($validated['odometer_km'])) {
            $validated['odometer_km'] = 0;
        }

        $vehicle = Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo creado exitosamente.');
    }

    /**
     * Genera un código único para el vehículo
     */
    private function generateVehicleCode()
    {
        $prefix = 'CAR';
        $lastVehicle = Vehicle::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastVehicle) {
            // Extraer el número del último código
            $lastNumber = (int) substr($lastVehicle->code, strlen($prefix) + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function edit(Vehicle $vehicle)
    {
        $drivers = Driver::active()->get()->map(function($driver) {
            return [
                'id' => $driver->id,
                'full_name' => $driver->full_name,
                'employee_code' => $driver->employee_code,
                'license_status' => $driver->license_status,
            ];
        });

        return Inertia::render('vehicles/Edit', [
            'vehicle' => $vehicle,
            'drivers' => $drivers
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate,' . $vehicle->id,
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:255',
            'vin' => 'nullable|string|max:255',
            'type' => 'required|in:compactador,camion,pickup,van,volqueta',
            'capacity_tons' => 'required|numeric|min:0.1|max:50',
            'fuel_capacity_liters' => 'required|numeric|min:10|max:1000',
            'fuel_type' => 'required|in:gasolina,diesel,gas,electrico',
            'engine' => 'nullable|string|max:255',
            'status' => 'required|in:activo,mantenimiento,fuera_servicio,reparacion',
            'driver_id' => 'nullable|exists:drivers,id',
            'odometer_km' => 'required|numeric|min:0',
            'last_maintenance' => 'nullable|date',
            'next_maintenance' => 'nullable|date|after:last_maintenance',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'insurance_policy' => 'nullable|string|max:255',
            'insurance_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo actualizado exitosamente.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->update(['is_active' => false]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehículo desactivado exitosamente.');
    }

    public function updateLocation(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed' => 'nullable|numeric|min:0|max:200',
        ]);

        $vehicle->updateLocation(
            $request->latitude,
            $request->longitude,
            $request->speed ?? 0
        );

        return response()->json([
            'message' => 'Ubicación actualizada exitosamente',
            'vehicle' => [
                'id' => $vehicle->id,
                'code' => $vehicle->code,
                'license_plate' => $vehicle->license_plate,
                'current_location' => $vehicle->current_location,
                'current_speed' => $vehicle->current_speed,
                'location_updated_at' => $vehicle->location_updated_at,
            ]
        ]);
    }

    public function nearbyVehicles(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'required|numeric|min:0.1|max:50', // Radio en kilómetros
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->radius_km;

        // Buscar vehículos dentro del radio usando fórmula de Haversine
        $vehicles = Vehicle::active()
            ->whereNotNull('current_lat')
            ->whereNotNull('current_lng')
            ->selectRaw("
                *,
                (6371 * acos(cos(radians(?)) * cos(radians(current_lat)) *
                cos(radians(current_lng) - radians(?)) + sin(radians(?)) *
                sin(radians(current_lat)))) AS distance_km
            ", [$lat, $lng, $lat])
            ->having('distance_km', '<=', $radius)
            ->orderBy('distance_km')
            ->with('driver')
            ->get()
            ->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'code' => $vehicle->code,
                    'license_plate' => $vehicle->license_plate,
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'type' => $vehicle->type,
                    'status' => $vehicle->status,
                    'current_location' => $vehicle->current_location,
                    'current_speed' => $vehicle->current_speed,
                    'distance_km' => round($vehicle->distance_km, 2),
                    'driver' => $vehicle->driver ? $vehicle->driver->full_name : null,
                ];
            });

        return response()->json([
            'center' => [
                'latitude' => $lat,
                'longitude' => $lng,
            ],
            'radius_km' => $radius,
            'vehicles_found' => $vehicles->count(),
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Get vehicles with their assigned routes for live map
     */
    public function getLiveVehicles()
    {
        $vehicles = Vehicle::with(['driver', 'currentRoute'])
            ->where('is_active', true)
            ->get()
            ->map(function ($vehicle) {
                // Si el vehículo no tiene ubicación pero tiene ruta asignada, usar el punto 1 de la ruta
                if ((!$vehicle->current_lat || !$vehicle->current_lng) && $vehicle->currentRoute) {
                    $collectionPoints = $vehicle->currentRoute->collection_points;
                    if ($collectionPoints && count($collectionPoints) > 0) {
                        $firstPoint = $collectionPoints[0];
                        $vehicle->current_lat = $firstPoint['lat'];
                        $vehicle->current_lng = $firstPoint['lng'];
                        $vehicle->save();
                    }
                }

                $assignedRoute = null;

                // Obtener la ruta activa asignada al vehículo
                $currentRoute = $vehicle->currentRoute;
                if ($currentRoute) {
                    $assignedRoute = [
                        'id' => $currentRoute->id,
                        'name' => $currentRoute->name,
                        'code' => $currentRoute->code,
                        'status' => $currentRoute->status,
                        'type' => $currentRoute->type,
                        'collection_points' => $currentRoute->collection_points,
                        'optimized_sequence' => $currentRoute->optimized_sequence,
                        'total_distance_km' => $currentRoute->total_distance_km,
                        'estimated_duration_minutes' => $currentRoute->estimated_duration_minutes,
                        'route_points' => $currentRoute->collection_points,
                    ];
                }

                return [
                    'id' => $vehicle->id,
                    'license_plate' => $vehicle->license_plate,
                    'code' => $vehicle->code,
                    'brand' => $vehicle->brand,
                    'model' => $vehicle->model,
                    'type' => $vehicle->type,
                    'status' => $vehicle->status,
                    'current_location' => [
                        'lat' => (float) $vehicle->current_lat,
                        'lng' => (float) $vehicle->current_lng,
                    ],
                    'current_speed' => $vehicle->current_speed ?? 0,
                    'driver' => $vehicle->driver ? [
                        'id' => $vehicle->driver->id,
                        'full_name' => $vehicle->driver->full_name,
                        'employee_code' => $vehicle->driver->employee_code,
                    ] : null,
                    'assigned_route' => $assignedRoute,
                    'location_updated_at' => $vehicle->location_updated_at?->toISOString(),
                    'updated_at' => $vehicle->updated_at->toISOString(),
                ];
            });

        return response()->json([
            'vehicles' => $vehicles,
            'total_count' => $vehicles->count(),
            'active_count' => $vehicles->where('status', 'activo')->count(),
            'last_updated' => now()->toISOString(),
        ]);
    }
}
