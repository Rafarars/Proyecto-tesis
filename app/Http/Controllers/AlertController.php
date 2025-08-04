<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Alert;
use App\Models\AlertRule;
use App\Models\Vehicle;
use App\Models\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    /**
     * Display the main alerts dashboard with subpanels
     */
    public function index(Request $request)
    {
        $activePanel = $request->get('panel', 'deteccion');

        // Estadísticas generales
        $stats = [
            'total_alerts' => Alert::count(),
            'active_alerts' => Alert::active()->count(),
            'critical_alerts' => Alert::critical()->active()->count(),
            'resolved_today' => Alert::resolved()->whereDate('resolved_at', today())->count(),
        ];

        // Alertas recientes para el panel de monitoreo
        $recentAlerts = Alert::with(['vehicle', 'route', 'creator'])
            ->active()
            ->orderBy('detected_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'code' => $alert->code,
                    'type' => $alert->type,
                    'type_label' => $alert->type_label,
                    'priority' => $alert->priority,
                    'priority_label' => $alert->priority_label,
                    'priority_badge_color' => $alert->priority_badge_color,
                    'status' => $alert->status,
                    'status_label' => $alert->status_label,
                    'status_badge_color' => $alert->status_badge_color,
                    'title' => $alert->title,
                    'description' => $alert->description,
                    'latitude' => $alert->latitude,
                    'longitude' => $alert->longitude,
                    'location_address' => $alert->location_address,
                    'detected_at' => $alert->detected_at->format('d/m/Y H:i'),
                    'vehicle' => $alert->vehicle ? [
                        'id' => $alert->vehicle->id,
                        'license_plate' => $alert->vehicle->license_plate,
                        'code' => $alert->vehicle->code,
                        'type' => $alert->vehicle->type,
                    ] : null,
                    'route' => $alert->route ? [
                        'id' => $alert->route->id,
                        'name' => $alert->route->name,
                        'code' => $alert->route->code,
                    ] : null,
                ];
            });

        // Datos específicos según el panel activo
        $panelData = $this->getPanelData($activePanel);

        return Inertia::render('alerts/Index', [
            'activePanel' => $activePanel,
            'stats' => $stats,
            'recentAlerts' => $recentAlerts,
            'panelData' => $panelData,
        ]);
    }

    /**
     * Get data specific to each panel
     */
    private function getPanelData(string $panel): array
    {
        switch ($panel) {
            case 'deteccion':
                return $this->getDetectionData();
            case 'monitoreo':
                return $this->getMonitoringData();
            case 'estados':
                return $this->getStatusData();
            case 'incidencias':
                return $this->getIncidentsData();
            case 'configuracion':
                return $this->getConfigurationData();
            default:
                return [];
        }
    }

    /**
     * Get detection system data
     */
    private function getDetectionData(): array
    {
        $detectionStats = [
            'rules_active' => AlertRule::active()->count(),
            'vehicles_monitored' => Vehicle::active()->count(),
            'routes_monitored' => Route::where('status', 'activa')->count(),
            'alerts_last_24h' => Alert::recent(24)->count(),
        ];

        $alertsByType = Alert::select('type', DB::raw('count(*) as count'))
            ->recent(168) // Última semana
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->count];
            });

        return [
            'detection_stats' => $detectionStats,
            'alerts_by_type' => $alertsByType,
        ];
    }

    /**
     * Get real-time monitoring data
     */
    private function getMonitoringData(): array
    {
        // Vehículos con alertas activas para el minimapa
        $vehiclesWithAlerts = Vehicle::whereHas('alerts', function ($query) {
            $query->active();
        })->with(['alerts' => function ($query) {
            $query->active()->orderBy('detected_at', 'desc');
        }])->get()->map(function ($vehicle) {
            $activeAlert = $vehicle->alerts->first();
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'code' => $vehicle->code,
                'type' => $vehicle->type,
                'latitude' => $vehicle->current_location ? $vehicle->current_location['lat'] : null,
                'longitude' => $vehicle->current_location ? $vehicle->current_location['lng'] : null,
                'alert' => $activeAlert ? [
                    'id' => $activeAlert->id,
                    'type' => $activeAlert->type,
                    'type_label' => $activeAlert->type_label,
                    'priority' => $activeAlert->priority,
                    'priority_badge_color' => $activeAlert->priority_badge_color,
                    'title' => $activeAlert->title,
                    'detected_at' => $activeAlert->detected_at->format('H:i'),
                ] : null,
            ];
        });

        return [
            'vehicles_with_alerts' => $vehiclesWithAlerts,
            'map_center' => [
                'latitude' => 10.6678,
                'longitude' => -63.2583,
            ],
        ];
    }

    /**
     * Get alerts status data
     */
    private function getStatusData(): array
    {
        $alerts = Alert::with(['vehicle', 'route', 'creator', 'assignedUser'])
            ->orderBy('detected_at', 'desc')
            ->paginate(20)
            ->through(function ($alert) {
                return [
                    'id' => $alert->id,
                    'code' => $alert->code,
                    'type_label' => $alert->type_label,
                    'priority_label' => $alert->priority_label,
                    'priority_badge_color' => $alert->priority_badge_color,
                    'status_label' => $alert->status_label,
                    'status_badge_color' => $alert->status_badge_color,
                    'title' => $alert->title,
                    'vehicle' => $alert->vehicle ? $alert->vehicle->license_plate : 'N/A',
                    'route' => $alert->route ? $alert->route->name : 'N/A',
                    'assigned_to' => $alert->assignedUser ? $alert->assignedUser->name : 'Sin asignar',
                    'detected_at' => $alert->detected_at->format('d/m/Y H:i'),
                    'response_time' => $alert->response_time_minutes ? $alert->response_time_minutes . ' min' : 'N/A',
                ];
            });

        $statusCounts = [
            'abierta' => Alert::open()->count(),
            'en_atencion' => Alert::inProgress()->count(),
            'resuelta' => Alert::resolved()->count(),
            'cerrada' => Alert::closed()->count(),
        ];

        return [
            'alerts' => $alerts,
            'status_counts' => $statusCounts,
        ];
    }

    /**
     * Get incidents management data
     */
    private function getIncidentsData(): array
    {
        $incidents = Alert::with(['vehicle', 'route', 'creator', 'assignedUser', 'resolver'])
            ->whereIn('status', ['resuelta', 'cerrada'])
            ->orderBy('resolved_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'code' => $alert->code,
                    'type_label' => $alert->type_label,
                    'priority_label' => $alert->priority_label,
                    'title' => $alert->title,
                    'vehicle' => $alert->vehicle ? $alert->vehicle->license_plate : 'N/A',
                    'route' => $alert->route ? $alert->route->name : 'N/A',
                    'creator' => $alert->creator ? $alert->creator->name : 'Sistema',
                    'resolver' => $alert->resolver ? $alert->resolver->name : 'N/A',
                    'detected_at' => $alert->detected_at->format('d/m/Y H:i'),
                    'resolved_at' => $alert->resolved_at ? $alert->resolved_at->format('d/m/Y H:i') : 'N/A',
                    'response_time' => $alert->response_time_minutes ? $alert->response_time_minutes . ' min' : 'N/A',
                    'resolution_time' => $alert->resolution_time_minutes ? $alert->resolution_time_minutes . ' min' : 'N/A',
                ];
            });

        $resolutionStats = [
            'avg_response_time' => Alert::whereNotNull('response_time_minutes')->avg('response_time_minutes'),
            'avg_resolution_time' => Alert::whereNotNull('resolution_time_minutes')->avg('resolution_time_minutes'),
            'resolved_today' => Alert::resolved()->whereDate('resolved_at', today())->count(),
            'resolved_this_week' => Alert::resolved()->whereBetween('resolved_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return [
            'incidents' => $incidents,
            'resolution_stats' => $resolutionStats,
        ];
    }

    /**
     * Get configuration data
     */
    private function getConfigurationData(): array
    {
        $alertRules = AlertRule::with(['creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($rule) {
                return [
                    'id' => $rule->id,
                    'name' => $rule->name,
                    'code' => $rule->code,
                    'type_label' => $rule->type_label,
                    'priority_label' => $rule->priority_label,
                    'priority_badge_color' => $rule->priority_badge_color,
                    'status_label' => $rule->status_label,
                    'status_badge_color' => $rule->status_badge_color,
                    'is_active' => $rule->is_active,
                    'formatted_active_days' => $rule->formatted_active_days,
                    'formatted_active_hours' => $rule->formatted_active_hours,
                    'creator' => $rule->creator ? $rule->creator->name : 'Sistema',
                    'created_at' => $rule->created_at->format('d/m/Y H:i'),
                ];
            });

        return [
            'alert_rules' => $alertRules,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::active()->get()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'code' => $vehicle->code,
                'type' => $vehicle->type,
                'full_name' => $vehicle->full_name,
                'current_location' => $vehicle->current_location,
                'location_updated_at' => $vehicle->location_updated_at,
            ];
        });

        $routes = Route::where('status', 'activa')->get()->map(function ($route) {
            return [
                'id' => $route->id,
                'name' => $route->name,
                'code' => $route->code,
                'type' => $route->type,
            ];
        });

        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        });

        return Inertia::render('alerts/Create', [
            'vehicles' => $vehicles,
            'routes' => $routes,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:desvio_ruta,parada_prolongada,perdida_senal,fuera_zona,retraso_horario,velocidad_excesiva,combustible_bajo,mantenimiento_vencido',
            'priority' => 'required|in:critica,alta,media,baja',
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_id' => 'nullable|exists:routes,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
            'metadata' => 'nullable|array',
        ]);

        // Obtener la ubicación del vehículo seleccionado
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        $validated['code'] = $this->generateAlertCode();
        $validated['created_by'] = Auth::id();
        $validated['detected_at'] = now();
        $validated['status'] = 'activa';

        // Asignar ubicación del vehículo
        $validated['latitude'] = $vehicle->current_lat;
        $validated['longitude'] = $vehicle->current_lng;
        $validated['location_address'] = $vehicle->current_lat && $vehicle->current_lng
            ? "Ubicación del vehículo {$vehicle->license_plate}"
            : "Vehículo {$vehicle->license_plate} - Sin ubicación GPS";

        $alert = Alert::create($validated);

        return redirect()->route('alerts.show', $alert)
            ->with('success', 'Alerta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alert $alert)
    {
        $alert->load(['vehicle', 'route', 'alertRule', 'creator', 'assignedUser', 'resolver', 'notifications.user']);

        $alertData = [
            'id' => $alert->id,
            'code' => $alert->code,
            'type' => $alert->type,
            'type_label' => $alert->type_label,
            'priority' => $alert->priority,
            'priority_label' => $alert->priority_label,
            'priority_badge_color' => $alert->priority_badge_color,
            'status' => $alert->status,
            'status_label' => $alert->status_label,
            'status_badge_color' => $alert->status_badge_color,
            'title' => $alert->title,
            'description' => $alert->description,
            'latitude' => $alert->latitude,
            'longitude' => $alert->longitude,
            'location_address' => $alert->location_address,
            'metadata' => $alert->metadata,
            'detected_at' => $alert->detected_at->format('d/m/Y H:i:s'),
            'acknowledged_at' => $alert->acknowledged_at ? $alert->acknowledged_at->format('d/m/Y H:i:s') : null,
            'resolved_at' => $alert->resolved_at ? $alert->resolved_at->format('d/m/Y H:i:s') : null,
            'closed_at' => $alert->closed_at ? $alert->closed_at->format('d/m/Y H:i:s') : null,
            'response_time_minutes' => $alert->response_time_minutes,
            'resolution_time_minutes' => $alert->resolution_time_minutes,
            'vehicle' => $alert->vehicle ? [
                'id' => $alert->vehicle->id,
                'license_plate' => $alert->vehicle->license_plate,
                'code' => $alert->vehicle->code,
                'type' => $alert->vehicle->type,
                'brand' => $alert->vehicle->brand,
                'model' => $alert->vehicle->model,
            ] : null,
            'route' => $alert->route ? [
                'id' => $alert->route->id,
                'name' => $alert->route->name,
                'code' => $alert->route->code,
                'type' => $alert->route->type,
            ] : null,
            'creator' => $alert->creator ? [
                'id' => $alert->creator->id,
                'name' => $alert->creator->name,
                'email' => $alert->creator->email,
            ] : null,
            'assigned_user' => $alert->assignedUser ? [
                'id' => $alert->assignedUser->id,
                'name' => $alert->assignedUser->name,
                'email' => $alert->assignedUser->email,
            ] : null,
            'resolver' => $alert->resolver ? [
                'id' => $alert->resolver->id,
                'name' => $alert->resolver->name,
                'email' => $alert->resolver->email,
            ] : null,
        ];

        return Inertia::render('alerts/Show', [
            'alert' => $alertData,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alert $alert)
    {
        $vehicles = Vehicle::active()->get()->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'code' => $vehicle->code,
                'type' => $vehicle->type,
                'full_name' => $vehicle->full_name,
            ];
        });

        $routes = Route::where('status', 'activa')->get()->map(function ($route) {
            return [
                'id' => $route->id,
                'name' => $route->name,
                'code' => $route->code,
                'type' => $route->type,
            ];
        });

        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        });

        $alertData = [
            'id' => $alert->id,
            'code' => $alert->code,
            'type' => $alert->type,
            'priority' => $alert->priority,
            'status' => $alert->status,
            'vehicle_id' => $alert->vehicle_id,
            'route_id' => $alert->route_id,
            'title' => $alert->title,
            'description' => $alert->description,
            'latitude' => $alert->latitude,
            'longitude' => $alert->longitude,
            'location_address' => $alert->location_address,
            'assigned_to' => $alert->assigned_to,
            'metadata' => $alert->metadata,
        ];

        return Inertia::render('alerts/Edit', [
            'alert' => $alertData,
            'vehicles' => $vehicles,
            'routes' => $routes,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alert $alert)
    {
        $validated = $request->validate([
            'type' => 'required|in:desvio_ruta,parada_prolongada,perdida_senal,fuera_zona,retraso_horario,velocidad_excesiva,combustible_bajo,mantenimiento_vencido',
            'priority' => 'required|in:critica,alta,media,baja',
            'status' => 'required|in:activa,reconocida,resuelta,descartada',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
            'metadata' => 'nullable|array',
        ]);

        // Calcular tiempos de respuesta y resolución
        if ($validated['status'] === 'reconocida' && !$alert->acknowledged_at) {
            $validated['acknowledged_at'] = now();
            $validated['acknowledged_by'] = Auth::id();
            $validated['response_time_minutes'] = $alert->detected_at->diffInMinutes(now());
        }

        if ($validated['status'] === 'resuelta' && !$alert->resolved_at) {
            $validated['resolved_at'] = now();
            $validated['resolved_by'] = Auth::id();
            if ($alert->acknowledged_at) {
                $validated['resolution_time_minutes'] = $alert->acknowledged_at->diffInMinutes(now());
            }
        }

        if ($validated['status'] === 'descartada' && !$alert->resolved_at) {
            $validated['resolved_at'] = now();
            $validated['resolved_by'] = Auth::id();
        }

        $alert->update($validated);

        return redirect()->route('alerts.index')
            ->with('success', 'Alerta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alert $alert)
    {
        $alert->delete();

        return redirect()->route('alerts.index')
            ->with('success', 'Alerta eliminada exitosamente.');
    }

    /**
     * Generate unique alert code
     */
    private function generateAlertCode(): string
    {
        do {
            $code = 'AL-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Alert::where('code', $code)->exists());

        return $code;
    }

    /**
     * API endpoint for live alerts data
     */
    public function getLiveAlerts()
    {
        $alerts = Alert::with(['vehicle', 'route'])
            ->active()
            ->orderBy('detected_at', 'desc')
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'code' => $alert->code,
                    'type' => $alert->type,
                    'type_label' => $alert->type_label,
                    'priority' => $alert->priority,
                    'priority_badge_color' => $alert->priority_badge_color,
                    'status' => $alert->status,
                    'title' => $alert->title,
                    'latitude' => $alert->latitude,
                    'longitude' => $alert->longitude,
                    'detected_at' => $alert->detected_at->format('H:i'),
                    'vehicle' => $alert->vehicle ? [
                        'license_plate' => $alert->vehicle->license_plate,
                        'type' => $alert->vehicle->type,
                    ] : null,
                ];
            });

        return response()->json($alerts);
    }

    /**
     * Simulate alert for testing purposes
     */
    public function simulateAlert(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|in:desvio_ruta,parada_prolongada,perdida_senal,fuera_zona',
            'priority' => 'required|in:critica,alta,media,baja',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        $alertData = [
            'code' => $this->generateAlertCode(),
            'type' => $validated['type'],
            'priority' => $validated['priority'],
            'vehicle_id' => $vehicle->id,
            'title' => $this->getSimulatedTitle($validated['type'], $vehicle),
            'description' => $this->getSimulatedDescription($validated['type'], $vehicle),
            'latitude' => $vehicle->current_location ? $vehicle->current_location['lat'] : null,
            'longitude' => $vehicle->current_location ? $vehicle->current_location['lng'] : null,
            'location_address' => 'Ubicación simulada - ' . $vehicle->license_plate,
            'created_by' => Auth::id() ?? 1, // Usar usuario ID 1 si no hay autenticación
            'detected_at' => now(),
            'status' => 'activa',
            'metadata' => [
                'simulated' => true,
                'simulation_time' => now()->toISOString(),
            ],
        ];

        $alert = Alert::create($alertData);

        return response()->json([
            'success' => true,
            'message' => 'Alerta simulada creada exitosamente',
            'alert' => [
                'id' => $alert->id,
                'code' => $alert->code,
                'type_label' => $alert->type_label,
                'priority_label' => $alert->priority_label,
                'title' => $alert->title,
            ],
        ]);
    }

    /**
     * Get simulated alert title
     */
    private function getSimulatedTitle(string $type, Vehicle $vehicle): string
    {
        return match($type) {
            'desvio_ruta' => "Desvío de ruta detectado - {$vehicle->license_plate}",
            'parada_prolongada' => "Parada prolongada - {$vehicle->license_plate}",
            'perdida_senal' => "Pérdida de señal GPS - {$vehicle->license_plate}",
            'fuera_zona' => "Vehículo fuera de zona autorizada - {$vehicle->license_plate}",
            default => "Alerta simulada - {$vehicle->license_plate}",
        };
    }

    /**
     * Get simulated alert description
     */
    private function getSimulatedDescription(string $type, Vehicle $vehicle): string
    {
        return match($type) {
            'desvio_ruta' => "El vehículo {$vehicle->license_plate} se ha desviado más de 200 metros de la ruta asignada. Verificar ubicación y contactar al conductor.",
            'parada_prolongada' => "El vehículo {$vehicle->license_plate} ha permanecido inmóvil por más de 30 minutos. Verificar estado del vehículo y conductor.",
            'perdida_senal' => "Se ha perdido la señal GPS del vehículo {$vehicle->license_plate}. Última ubicación conocida registrada.",
            'fuera_zona' => "El vehículo {$vehicle->license_plate} se encuentra fuera de la zona de operación autorizada. Contactar inmediatamente al conductor.",
            default => "Alerta de prueba generada automáticamente para el vehículo {$vehicle->license_plate}.",
        };
    }



    /**
     * Acknowledge an alert
     */
    public function acknowledge(Alert $alert)
    {
        if ($alert->status !== 'activa') {
            return back()->with('error', 'Solo se pueden reconocer alertas activas.');
        }

        $alert->update([
            'status' => 'reconocida',
            'acknowledged_at' => now(),
            'acknowledged_by' => Auth::id(),
        ]);

        return back()->with('success', 'Alerta reconocida exitosamente.');
    }

    /**
     * Resolve an alert
     */
    public function resolve(Alert $alert)
    {
        if ($alert->status === 'resuelta') {
            return back()->with('error', 'La alerta ya está resuelta.');
        }

        $alert->update([
            'status' => 'resuelta',
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Alerta resuelta exitosamente.');
    }

    /**
     * Dismiss an alert
     */
    public function dismiss(Alert $alert)
    {
        if ($alert->status !== 'activa') {
            return back()->with('error', 'Solo se pueden descartar alertas activas.');
        }

        $alert->update([
            'status' => 'descartada',
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Alerta descartada exitosamente.');
    }

}
