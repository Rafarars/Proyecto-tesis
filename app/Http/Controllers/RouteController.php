<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::with(['assignedVehicle', 'creator'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($route) {
                return [
                    'id' => $route->id,
                    'name' => $route->name,
                    'code' => $route->code,
                    'description' => $route->description,
                    'status' => $route->status,
                    'type' => $route->type,
                    'collection_points_count' => $route->collection_points_count,
                    'total_distance_km' => $route->total_distance_km,
                    'estimated_duration_formatted' => $route->estimated_duration_formatted,
                    'formatted_days' => $route->formatted_days,
                    'start_time' => $route->start_time,
                    'end_time' => $route->end_time,
                    'is_assigned' => $route->is_assigned,
                    'assigned_vehicle' => $route->assignedVehicle ? [
                        'id' => $route->assignedVehicle->id,
                        'license_plate' => $route->assignedVehicle->license_plate,
                        'code' => $route->assignedVehicle->code,
                        'type' => $route->assignedVehicle->type,
                    ] : null,
                    'creator' => [
                        'name' => $route->creator->name,
                    ],
                    'status_badge_color' => $route->status_badge_color,
                    'created_at' => $route->created_at->format('d/m/Y H:i'),
                    'assigned_at' => $route->assigned_at?->format('d/m/Y H:i'),
                ];
            });

        return Inertia::render('routes/Index', [
            'routes' => $routes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener vehículos disponibles (activos y sin ruta asignada)
        $availableVehicles = Vehicle::active()
            ->whereDoesntHave('assignedRoutes', function($query) {
                $query->where('status', 'activa');
            })
            ->get()
            ->map(function($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'license_plate' => $vehicle->license_plate,
                    'code' => $vehicle->code,
                    'type' => $vehicle->type,
                    'capacity_tons' => $vehicle->capacity_tons,
                    'full_name' => $vehicle->full_name,
                ];
            });

        return Inertia::render('routes/Create', [
            'availableVehicles' => $availableVehicles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:recoleccion_residencial,recoleccion_comercial,recoleccion_mixta',
            'collection_points' => 'required|array|min:2',
            'collection_points.*.lat' => 'required|numeric|between:-90,90',
            'collection_points.*.lng' => 'required|numeric|between:-180,180',
            'collection_points.*.address' => 'required|string|max:255',
            'collection_points.*.priority' => 'nullable|integer|min:1|max:10',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|between:0,6',
            'start_lat' => 'nullable|numeric|between:-90,90',
            'start_lng' => 'nullable|numeric|between:-180,180',
            'start_address' => 'nullable|string|max:255',
            'end_lat' => 'nullable|numeric|between:-90,90',
            'end_lng' => 'nullable|numeric|between:-180,180',
            'end_address' => 'nullable|string|max:255',
            'assigned_vehicle_id' => 'nullable|exists:vehicles,id',
        ]);

        // Generar código único para la ruta
        $validated['code'] = $this->generateRouteCode();
        $validated['created_by'] = Auth::id();

        // Si se asigna un vehículo, establecer estado como activa
        if ($validated['assigned_vehicle_id']) {
            $validated['status'] = 'activa';
            $validated['assigned_at'] = now();
        } else {
            $validated['status'] = 'en_planificacion';
        }

        // Calcular optimización básica si hay puntos
        if (count($validated['collection_points']) >= 2) {
            $optimizationResult = $this->basicOptimization($validated['collection_points']);
            $validated['optimized_sequence'] = $optimizationResult['sequence'];
            $validated['total_distance_km'] = $optimizationResult['total_distance'];
            $validated['estimated_duration_minutes'] = $optimizationResult['estimated_duration'];
            $validated['last_optimized_at'] = now();
        }

        $route = Route::create($validated);

        return redirect()->route('routes.show', $route)
            ->with('success', 'Ruta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        $route->load(['assignedVehicle', 'creator']);

        $routeData = [
            'id' => $route->id,
            'name' => $route->name,
            'code' => $route->code,
            'description' => $route->description,
            'status' => $route->status,
            'type' => $route->type,
            'collection_points' => $route->collection_points,
            'optimized_sequence' => $route->optimized_sequence,
            'total_distance_km' => $route->total_distance_km,
            'estimated_duration_minutes' => $route->estimated_duration_minutes,
            'estimated_duration_formatted' => $route->estimated_duration_formatted,
            'start_time' => $route->start_time,
            'end_time' => $route->end_time,
            'days_of_week' => $route->days_of_week,
            'formatted_days' => $route->formatted_days,
            'start_lat' => $route->start_lat,
            'start_lng' => $route->start_lng,
            'start_address' => $route->start_address,
            'end_lat' => $route->end_lat,
            'end_lng' => $route->end_lng,
            'end_address' => $route->end_address,
            'is_assigned' => $route->is_assigned,
            'assigned_vehicle' => $route->assignedVehicle ? [
                'id' => $route->assignedVehicle->id,
                'license_plate' => $route->assignedVehicle->license_plate,
                'code' => $route->assignedVehicle->code,
                'type' => $route->assignedVehicle->type,
                'capacity_tons' => $route->assignedVehicle->capacity_tons,
                'status' => $route->assignedVehicle->status,
                'current_location' => $route->assignedVehicle->current_location,
            ] : null,
            'creator' => [
                'name' => $route->creator->name,
            ],
            'status_badge_color' => $route->status_badge_color,
            'collection_points_count' => $route->collection_points_count,
            'created_at' => $route->created_at->format('d/m/Y H:i'),
            'assigned_at' => $route->assigned_at?->format('d/m/Y H:i'),
            'last_optimized_at' => $route->last_optimized_at?->format('d/m/Y H:i'),
        ];

        return Inertia::render('routes/Show', [
            'route' => $routeData
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        // Obtener vehículos disponibles (activos y sin ruta asignada, o el vehículo actualmente asignado)
        $availableVehicles = Vehicle::active()
            ->where(function($query) use ($route) {
                $query->whereDoesntHave('assignedRoutes', function($subQuery) {
                    $subQuery->where('status', 'activa');
                })->orWhere('id', $route->assigned_vehicle_id);
            })
            ->get()
            ->map(function($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'license_plate' => $vehicle->license_plate,
                    'code' => $vehicle->code,
                    'type' => $vehicle->type,
                    'capacity_tons' => $vehicle->capacity_tons,
                    'full_name' => $vehicle->full_name,
                ];
            });

        $routeData = [
            'id' => $route->id,
            'name' => $route->name,
            'code' => $route->code,
            'description' => $route->description,
            'type' => $route->type,
            'collection_points' => $route->collection_points,
            'start_time' => $route->start_time,
            'end_time' => $route->end_time,
            'days_of_week' => $route->days_of_week,
            'start_lat' => $route->start_lat,
            'start_lng' => $route->start_lng,
            'start_address' => $route->start_address,
            'end_lat' => $route->end_lat,
            'end_lng' => $route->end_lng,
            'end_address' => $route->end_address,
            'assigned_vehicle_id' => $route->assigned_vehicle_id,
        ];

        return Inertia::render('routes/Edit', [
            'routeData' => $routeData,
            'availableVehicles' => $availableVehicles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:recoleccion_residencial,recoleccion_comercial,recoleccion_mixta',
            'collection_points' => 'required|array|min:2',
            'collection_points.*.lat' => 'required|numeric|between:-90,90',
            'collection_points.*.lng' => 'required|numeric|between:-180,180',
            'collection_points.*.address' => 'required|string|max:255',
            'collection_points.*.priority' => 'nullable|integer|min:1|max:10',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|between:0,6',
            'start_lat' => 'nullable|numeric|between:-90,90',
            'start_lng' => 'nullable|numeric|between:-180,180',
            'start_address' => 'nullable|string|max:255',
            'end_lat' => 'nullable|numeric|between:-90,90',
            'end_lng' => 'nullable|numeric|between:-180,180',
            'end_address' => 'nullable|string|max:255',
            'assigned_vehicle_id' => 'nullable|exists:vehicles,id',
        ]);

        // Manejar cambio de asignación de vehículo
        $previousVehicleId = $route->assigned_vehicle_id;
        $newVehicleId = $validated['assigned_vehicle_id'];

        if ($previousVehicleId !== $newVehicleId) {
            if ($newVehicleId) {
                $validated['status'] = 'activa';
                $validated['assigned_at'] = now();
            } else {
                $validated['status'] = 'en_planificacion';
                $validated['assigned_at'] = null;
            }
        }

        // Recalcular optimización si cambiaron los puntos
        if ($route->collection_points !== $validated['collection_points']) {
            $optimizationResult = $this->basicOptimization($validated['collection_points']);
            $validated['optimized_sequence'] = $optimizationResult['sequence'];
            $validated['total_distance_km'] = $optimizationResult['total_distance'];
            $validated['estimated_duration_minutes'] = $optimizationResult['estimated_duration'];
            $validated['last_optimized_at'] = now();
        }

        $route->update($validated);

        return redirect()->route('routes.index')
            ->with('success', 'Ruta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->update(['is_active' => false]);

        return redirect()->route('routes.index')
            ->with('success', 'Ruta desactivada exitosamente.');
    }

    /**
     * Assign a vehicle to a route
     */
    public function assignVehicle(Request $request, Route $route)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $route->assignVehicle($vehicle);

        return redirect()->back()
            ->with('success', 'Vehículo asignado exitosamente.');
    }

    /**
     * Unassign vehicle from route
     */
    public function unassignVehicle(Route $route)
    {
        $route->unassignVehicle();

        return redirect()->back()
            ->with('success', 'Vehículo desasignado exitosamente.');
    }

    /**
     * Optimize route sequence
     */
    public function optimize(Route $route)
    {
        if (!$route->collection_points || count($route->collection_points) < 2) {
            return redirect()->back()
                ->with('error', 'La ruta debe tener al menos 2 puntos de recolección.');
        }

        $optimizationResult = $this->basicOptimization($route->collection_points);

        $route->update([
            'optimized_sequence' => $optimizationResult['sequence'],
            'total_distance_km' => $optimizationResult['total_distance'],
            'estimated_duration_minutes' => $optimizationResult['estimated_duration'],
            'last_optimized_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Ruta optimizada exitosamente.');
    }

    /**
     * Generate unique route code
     */
    private function generateRouteCode(): string
    {
        do {
            $code = 'RT-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (Route::where('code', $code)->exists());

        return $code;
    }

    /**
     * Basic route optimization algorithm
     * En el futuro se puede reemplazar con algoritmos más avanzados
     */
    private function basicOptimization(array $points): array
    {
        if (count($points) < 2) {
            return [
                'sequence' => $points,
                'total_distance' => 0,
                'estimated_duration' => 0
            ];
        }

        // Algoritmo básico: ordenar por prioridad y luego por proximidad
        $optimizedSequence = collect($points)
            ->sortBy('priority')
            ->values()
            ->toArray();

        // Calcular distancia total estimada (usando distancia euclidiana simple)
        $totalDistance = 0;
        for ($i = 0; $i < count($optimizedSequence) - 1; $i++) {
            $point1 = $optimizedSequence[$i];
            $point2 = $optimizedSequence[$i + 1];

            $distance = $this->calculateDistance(
                $point1['lat'], $point1['lng'],
                $point2['lat'], $point2['lng']
            );

            $totalDistance += $distance;
        }

        // Estimar duración (asumiendo velocidad promedio de 30 km/h + tiempo de parada)
        $drivingTime = ($totalDistance / 30) * 60; // minutos
        $stopTime = count($points) * 10; // 10 minutos por parada
        $estimatedDuration = round($drivingTime + $stopTime);

        return [
            'sequence' => $optimizedSequence,
            'total_distance' => round($totalDistance, 2),
            'estimated_duration' => $estimatedDuration
        ];
    }

    /**
     * Calculate distance between two points using Haversine formula
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Radio de la Tierra en kilómetros

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}
