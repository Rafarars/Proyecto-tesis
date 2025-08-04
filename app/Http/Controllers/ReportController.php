<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Vehicle;
use App\Models\Route;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the main reports dashboard with subpanels
     */
    public function index(Request $request)
    {
        $activePanel = $request->get('panel', 'dashboard');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->endOfMonth()->format('Y-m-d'));

        // Estadísticas generales para el dashboard principal
        $generalStats = $this->getGeneralStats($dateFrom, $dateTo);

        // Datos específicos según el panel activo
        $panelData = $this->getPanelData($activePanel, $dateFrom, $dateTo);

        return Inertia::render('reports/Index', [
            'activePanel' => $activePanel,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'generalStats' => $generalStats,
            'panelData' => $panelData,
        ]);
    }

    /**
     * Get general statistics for the main dashboard
     */
    private function getGeneralStats(string $dateFrom, string $dateTo): array
    {
        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();

        return [
            'total_vehicles' => Vehicle::count(),
            'active_vehicles' => Vehicle::active()->count(),
            'total_routes' => Route::count(),
            'completed_routes' => Route::where('status', 'completada')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->count(),
            'total_alerts' => Alert::whereBetween('detected_at', [$startDate, $endDate])->count(),
            'resolved_alerts' => Alert::resolved()
                ->whereBetween('resolved_at', [$startDate, $endDate])
                ->count(),
            'avg_response_time' => Alert::whereNotNull('response_time_minutes')
                ->whereBetween('detected_at', [$startDate, $endDate])
                ->avg('response_time_minutes'),
            'efficiency_rate' => $this->calculateEfficiencyRate($startDate, $endDate),
        ];
    }

    /**
     * Get data specific to each panel
     */
    private function getPanelData(string $panel, string $dateFrom, string $dateTo): array
    {
        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();

        switch ($panel) {
            case 'vehicles':
                return $this->getVehicleReportData($startDate, $endDate);
            case 'routes':
                return $this->getRouteReportData($startDate, $endDate);
            case 'alerts':
                return $this->getAlertReportData($startDate, $endDate);
            case 'temporal':
                return $this->getTemporalAnalysisData($startDate, $endDate);
            default:
                return $this->getDashboardData($startDate, $endDate);
        }
    }

    /**
     * Get dashboard overview data
     */
    private function getDashboardData(Carbon $startDate, Carbon $endDate): array
    {
        // KPIs principales
        $kpis = [
            'fleet_utilization' => $this->calculateFleetUtilization($startDate, $endDate),
            'route_efficiency' => $this->calculateOverallRouteEfficiency($startDate, $endDate),
            'alert_resolution_rate' => $this->calculateAlertResolutionRate($startDate, $endDate),
            'operational_cost_savings' => $this->calculateCostSavings($startDate, $endDate),
        ];

        // Gráfico de tendencias diarias
        $dailyTrends = $this->getDailyTrends($startDate, $endDate);

        // Top vehículos por rendimiento
        $topVehicles = $this->getTopPerformingVehicles($startDate, $endDate);

        // Distribución de alertas por tipo
        $alertDistribution = Alert::select('type', DB::raw('count(*) as count'))
            ->whereBetween('detected_at', [$startDate, $endDate])
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->count];
            });

        return [
            'kpis' => $kpis,
            'daily_trends' => $dailyTrends,
            'top_vehicles' => $topVehicles,
            'alert_distribution' => $alertDistribution,
        ];
    }

    /**
     * Get vehicle report data
     */
    private function getVehicleReportData(Carbon $startDate, Carbon $endDate): array
    {
        $vehicles = Vehicle::with(['routes' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }, 'alerts' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('detected_at', [$startDate, $endDate]);
        }])->get();

        $vehicleStats = $vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'type' => $vehicle->type,
                'status' => $vehicle->status,
                'routes_completed' => $vehicle->routes->where('status', 'completada')->count(),
                'total_distance' => $vehicle->routes->sum('total_distance_km') ?? 0,
                'alerts_count' => $vehicle->alerts->count(),
                'efficiency_score' => $this->calculateVehicleEfficiency($vehicle),
                'maintenance_status' => $vehicle->maintenance_status ?? 'al_dia',
            ];
        });

        // Estadísticas por tipo de vehículo
        $vehiclesByType = $vehicles->groupBy('type')->map(function ($group, $type) {
            return [
                'type' => $type,
                'count' => $group->count(),
                'active' => $group->where('status', 'activo')->count(),
                'avg_efficiency' => $group->avg(function ($vehicle) {
                    return $this->calculateVehicleEfficiency($vehicle);
                }),
            ];
        })->values()->toArray();

        // Análisis de mantenimiento
        $maintenanceAnalysis = [
            'al_dia' => $vehicles->where('maintenance_status', 'al_dia')->count(),
            'proximo' => $vehicles->where('maintenance_status', 'proximo')->count(),
            'vencido' => $vehicles->where('maintenance_status', 'vencido')->count(),
        ];

        return [
            'vehicle_stats' => $vehicleStats,
            'vehicles_by_type' => $vehiclesByType,
            'maintenance_analysis' => $maintenanceAnalysis,
        ];
    }

    /**
     * Get route report data
     */
    private function getRouteReportData(Carbon $startDate, Carbon $endDate): array
    {
        $routes = Route::with(['assignedVehicle', 'alerts'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $routeStats = $routes->map(function ($route) {
            return [
                'id' => $route->id,
                'name' => $route->name,
                'code' => $route->code,
                'status' => $route->status,
                'vehicle' => $route->assignedVehicle ? [
                    'license_plate' => $route->assignedVehicle->license_plate,
                    'type' => $route->assignedVehicle->type,
                ] : null,
                'total_distance' => $route->total_distance_km ?? 0,
                'estimated_duration' => $route->estimated_duration_minutes ?? 0,
                'actual_duration' => $route->estimated_duration_minutes ?? 0, // Usar estimated como placeholder
                'collection_points' => $route->collection_points_count ?? 0,
                'efficiency_rating' => $this->calculateSingleRouteEfficiency($route),
                'alerts_count' => $route->alerts->count(),
            ];
        });

        // Análisis de eficiencia de rutas
        $efficiencyAnalysis = [
            'avg_distance' => $routes->avg('total_distance_km') ?? 0,
            'avg_duration' => $routes->avg('estimated_duration_minutes') ?? 0,
            'completion_rate' => $routes->where('status', 'completada')->count() / max($routes->count(), 1) * 100,
            'optimization_savings' => $this->calculateOptimizationSavings($routes),
        ];

        // Rutas por estado
        $routesByStatus = $routes->groupBy('status')->map(function ($group, $status) use ($routes) {
            return [
                'status' => $status,
                'count' => $group->count(),
                'percentage' => $group->count() / max($routes->count(), 1) * 100,
            ];
        })->values()->toArray();

        return [
            'route_stats' => $routeStats,
            'efficiency_analysis' => $efficiencyAnalysis,
            'routes_by_status' => $routesByStatus,
        ];
    }

    /**
     * Get alert report data
     */
    private function getAlertReportData(Carbon $startDate, Carbon $endDate): array
    {
        $alerts = Alert::with(['vehicle', 'route'])
            ->whereBetween('detected_at', [$startDate, $endDate])
            ->get();

        $alertStats = $alerts->map(function ($alert) {
            return [
                'id' => $alert->id,
                'type' => $alert->type,
                'severity' => $alert->severity,
                'status' => $alert->status,
                'detected_at' => $alert->detected_at->format('d/m/Y H:i'),
                'resolved_at' => $alert->resolved_at?->format('d/m/Y H:i'),
                'response_time' => $alert->response_time_minutes,
                'resolution_time' => $alert->resolution_time_minutes,
                'vehicle' => $alert->vehicle ? [
                    'license_plate' => $alert->vehicle->license_plate,
                    'type' => $alert->vehicle->type,
                ] : null,
                'route' => $alert->route ? [
                    'name' => $alert->route->name,
                    'code' => $alert->route->code,
                ] : null,
            ];
        });

        // Análisis de tiempos de respuesta
        $responseAnalysis = [
            'avg_response_time' => $alerts->whereNotNull('response_time_minutes')->avg('response_time_minutes') ?? 0,
            'avg_resolution_time' => $alerts->whereNotNull('resolution_time_minutes')->avg('resolution_time_minutes') ?? 0,
            'resolution_rate' => $alerts->where('status', 'resuelta')->count() / max($alerts->count(), 1) * 100,
            'critical_alerts' => $alerts->where('severity', 'critica')->count(),
        ];

        // Alertas por tipo y severidad
        $alertsByType = $alerts->groupBy('type')->map(function ($group, $type) {
            return [
                'type' => $type,
                'count' => $group->count(),
                'critical' => $group->where('severity', 'critica')->count(),
                'avg_response_time' => $group->whereNotNull('response_time_minutes')->avg('response_time_minutes') ?? 0,
            ];
        })->values()->toArray();

        $alertsBySeverity = $alerts->groupBy('severity')->map(function ($group, $severity) use ($alerts) {
            return [
                'severity' => $severity,
                'count' => $group->count(),
                'percentage' => $group->count() / max($alerts->count(), 1) * 100,
            ];
        })->values()->toArray();

        return [
            'alert_stats' => $alertStats,
            'response_analysis' => $responseAnalysis,
            'alerts_by_type' => $alertsByType,
            'alerts_by_severity' => $alertsBySeverity,
        ];
    }

    /**
     * Get temporal analysis data
     */
    private function getTemporalAnalysisData(Carbon $startDate, Carbon $endDate): array
    {
        // Análisis por horas del día
        $hourlyAnalysis = $this->getHourlyAnalysis($startDate, $endDate);

        // Análisis por días de la semana
        $weeklyAnalysis = $this->getWeeklyAnalysis($startDate, $endDate);

        // Tendencias mensuales (si el rango es mayor a un mes)
        $monthlyTrends = $this->getMonthlyTrends($startDate, $endDate);

        // Comparativa con período anterior
        $previousPeriodComparison = $this->getPreviousPeriodComparison($startDate, $endDate);

        return [
            'hourly_analysis' => $hourlyAnalysis,
            'weekly_analysis' => $weeklyAnalysis,
            'monthly_trends' => $monthlyTrends,
            'previous_period_comparison' => $previousPeriodComparison,
        ];
    }

    // Helper methods for calculations
    private function calculateEfficiencyRate(Carbon $startDate, Carbon $endDate): float
    {
        $totalRoutes = Route::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedRoutes = Route::where('status', 'completada')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        return $totalRoutes > 0 ? ($completedRoutes / $totalRoutes) * 100 : 0;
    }

    private function calculateFleetUtilization(Carbon $startDate, Carbon $endDate): float
    {
        $totalVehicles = Vehicle::count();
        $activeVehicles = Vehicle::active()->count();

        return $totalVehicles > 0 ? ($activeVehicles / $totalVehicles) * 100 : 0;
    }

    private function calculateOverallRouteEfficiency(Carbon $startDate, Carbon $endDate): float
    {
        $routes = Route::whereBetween('created_at', [$startDate, $endDate])->get();

        if ($routes->isEmpty()) return 0;

        $totalEfficiency = $routes->sum(function ($route) {
            return $this->calculateSingleRouteEfficiency($route);
        });

        return $totalEfficiency / $routes->count();
    }

    private function calculateAlertResolutionRate(Carbon $startDate, Carbon $endDate): float
    {
        $totalAlerts = Alert::whereBetween('detected_at', [$startDate, $endDate])->count();
        $resolvedAlerts = Alert::resolved()
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->count();

        return $totalAlerts > 0 ? ($resolvedAlerts / $totalAlerts) * 100 : 0;
    }

    private function calculateCostSavings(Carbon $startDate, Carbon $endDate): float
    {
        // Cálculo simplificado de ahorro en costos operativos
        // Basado en rutas optimizadas vs rutas tradicionales
        return rand(5000, 15000); // Placeholder - implementar lógica real
    }

    private function getDailyTrends(Carbon $startDate, Carbon $endDate): array
    {
        $trends = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $dayStart = $current->copy()->startOfDay();
            $dayEnd = $current->copy()->endOfDay();

            $trends[] = [
                'date' => $current->format('Y-m-d'),
                'routes_completed' => Route::where('status', 'completada')
                    ->whereBetween('updated_at', [$dayStart, $dayEnd])
                    ->count(),
                'alerts_generated' => Alert::whereBetween('detected_at', [$dayStart, $dayEnd])->count(),
                'vehicles_active' => Vehicle::active()->count(), // Simplificado
            ];

            $current->addDay();
        }

        return $trends;
    }

    private function getTopPerformingVehicles(Carbon $startDate, Carbon $endDate): array
    {
        return Vehicle::with(['routes' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])
        ->get()
        ->map(function ($vehicle) {
            return [
                'license_plate' => $vehicle->license_plate,
                'type' => $vehicle->type,
                'routes_completed' => $vehicle->routes->where('status', 'completada')->count(),
                'efficiency_score' => $this->calculateVehicleEfficiency($vehicle),
            ];
        })
        ->sortByDesc('efficiency_score')
        ->take(5)
        ->values()
        ->toArray();
    }

    private function calculateVehicleEfficiency($vehicle): float
    {
        // Cálculo simplificado de eficiencia del vehículo
        $routesCompleted = $vehicle->routes->where('status', 'completada')->count();
        $totalRoutes = $vehicle->routes->count();
        $alertsCount = $vehicle->alerts->count();

        if ($totalRoutes === 0) return 0;

        $completionRate = ($routesCompleted / $totalRoutes) * 100;
        $alertPenalty = $alertsCount * 5; // 5% penalty per alert

        return max(0, $completionRate - $alertPenalty);
    }

    private function calculateSingleRouteEfficiency($route): float
    {
        if (!$route->estimated_duration_minutes) return 0;

        // Usar una eficiencia simulada basada en el estado de la ruta
        $baseEfficiency = match($route->status) {
            'completada' => 85,
            'activa' => 75,
            'en_planificacion' => 60,
            default => 50
        };

        // Agregar variación aleatoria para simular datos reales
        $variation = rand(-15, 15);
        $efficiency = $baseEfficiency + $variation;

        return min(100, max(0, $efficiency));
    }

    private function calculateOptimizationSavings($routes): float
    {
        // Cálculo simplificado de ahorros por optimización
        return $routes->sum(function ($route) {
            // Simular ahorro basado en la distancia de la ruta
            $distance = $route->total_distance_km ?? 0;
            return $distance * 2.5; // 2.5 unidades de ahorro por km
        });
    }

    private function getHourlyAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        $hourlyData = [];
        
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyData[] = [
                'hour' => $hour,
                'alerts' => Alert::whereBetween('detected_at', [$startDate, $endDate])
                    ->whereRaw('HOUR(detected_at) = ?', [$hour])
                    ->count(),
                'routes_started' => Route::whereBetween('created_at', [$startDate, $endDate])
                    ->whereRaw('HOUR(created_at) = ?', [$hour])
                    ->count(),
            ];
        }

        return $hourlyData;
    }

    private function getWeeklyAnalysis(Carbon $startDate, Carbon $endDate): array
    {
        $weeklyData = [];
        $daysOfWeek = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        for ($day = 1; $day <= 7; $day++) {
            $weeklyData[] = [
                'day' => $daysOfWeek[$day - 1],
                'day_number' => $day,
                'alerts' => Alert::whereBetween('detected_at', [$startDate, $endDate])
                    ->whereRaw('DAYOFWEEK(detected_at) = ?', [$day])
                    ->count(),
                'routes_completed' => Route::where('status', 'completada')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->whereRaw('DAYOFWEEK(updated_at) = ?', [$day])
                    ->count(),
            ];
        }

        return $weeklyData;
    }

    private function getMonthlyTrends(Carbon $startDate, Carbon $endDate): array
    {
        $monthlyData = [];
        $current = $startDate->copy()->startOfMonth();

        while ($current <= $endDate) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            $monthlyData[] = [
                'month' => $current->format('Y-m'),
                'month_name' => $current->format('F Y'),
                'routes_completed' => Route::where('status', 'completada')
                    ->whereBetween('updated_at', [$monthStart, $monthEnd])
                    ->count(),
                'alerts_total' => Alert::whereBetween('detected_at', [$monthStart, $monthEnd])->count(),
                'efficiency_rate' => $this->calculateEfficiencyRate($monthStart, $monthEnd),
            ];

            $current->addMonth();
        }

        return $monthlyData;
    }

    private function getPreviousPeriodComparison(Carbon $startDate, Carbon $endDate): array
    {
        $periodDays = $startDate->diffInDays($endDate) + 1;
        $previousStart = $startDate->copy()->subDays($periodDays);
        $previousEnd = $startDate->copy()->subDay();

        $currentStats = $this->getGeneralStats($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        $previousStats = $this->getGeneralStats($previousStart->format('Y-m-d'), $previousEnd->format('Y-m-d'));

        return [
            'current_period' => $currentStats,
            'previous_period' => $previousStats,
            'changes' => [
                'routes_change' => $this->calculatePercentageChange($previousStats['completed_routes'], $currentStats['completed_routes']),
                'alerts_change' => $this->calculatePercentageChange($previousStats['total_alerts'], $currentStats['total_alerts']),
                'efficiency_change' => $this->calculatePercentageChange($previousStats['efficiency_rate'], $currentStats['efficiency_rate']),
            ],
        ];
    }

    private function calculatePercentageChange($previous, $current): float
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }

    /**
     * Export report data to PDF or Excel
     */
    public function export(Request $request, string $type)
    {
        $format = $request->get('format', 'pdf'); // pdf or excel
        $panel = $request->get('panel', 'dashboard');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->endOfMonth()->format('Y-m-d'));

        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();

        // Get data for the specific panel
        $data = $this->getPanelData($panel, $dateFrom, $dateTo);
        $generalStats = $this->getGeneralStats($dateFrom, $dateTo);

        $exportData = [
            'title' => $this->getReportTitle($panel),
            'period' => $dateFrom . ' - ' . $dateTo,
            'generated_at' => now()->format('d/m/Y H:i'),
            'general_stats' => $generalStats,
            'panel_data' => $data,
        ];

        if ($format === 'excel') {
            return $this->exportToExcel($exportData, $panel);
        } else {
            return $this->exportToPdf($exportData, $panel);
        }
    }

    private function getReportTitle(string $panel): string
    {
        $titles = [
            'dashboard' => 'Reporte General del Sistema',
            'vehicles' => 'Reporte de Vehículos',
            'routes' => 'Reporte de Rutas',
            'alerts' => 'Reporte de Alertas',
            'temporal' => 'Análisis Temporal',
        ];

        return $titles[$panel] ?? 'Reporte del Sistema';
    }

    private function exportToPdf(array $data, string $panel)
    {
        // Generar contenido HTML para el PDF
        $html = $this->generateReportHtml($data, $panel);

        // Por ahora, devolver el HTML como respuesta
        // En producción se usaría DomPDF o similar
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="reporte_' . $panel . '_' . date('Y-m-d') . '.html"'
        ]);
    }

    private function exportToExcel(array $data, string $panel)
    {
        // Generar CSV como alternativa a Excel
        $csv = $this->generateReportCsv($data, $panel);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reporte_' . $panel . '_' . date('Y-m-d') . '.csv"'
        ]);
    }

    private function generateReportHtml(array $data, string $panel): string
    {
        $title = $this->getReportTitle($panel);
        $date = date('d/m/Y H:i');

        $html = "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>{$title}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .kpi { display: inline-block; margin: 10px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
                .kpi-value { font-size: 24px; font-weight: bold; color: #059669; }
                .kpi-label { font-size: 14px; color: #666; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f5f5f5; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>{$title}</h1>
                <p>Generado el: {$date}</p>
            </div>";

        // Agregar KPIs si existen
        if (isset($data['panel_data']['kpis'])) {
            $html .= "<div class='kpis'>";
            foreach ($data['panel_data']['kpis'] as $key => $value) {
                $label = ucfirst(str_replace('_', ' ', $key));
                $html .= "<div class='kpi'>
                    <div class='kpi-value'>" . number_format($value, 2) . "</div>
                    <div class='kpi-label'>{$label}</div>
                </div>";
            }
            $html .= "</div>";
        }

        $html .= "</body></html>";

        return $html;
    }

    private function generateReportCsv(array $data, string $panel): string
    {
        $csv = "Reporte: " . $this->getReportTitle($panel) . "\n";
        $csv .= "Fecha: " . date('d/m/Y H:i') . "\n\n";

        // Agregar KPIs si existen
        if (isset($data['panel_data']['kpis'])) {
            $csv .= "KPIs Principales\n";
            $csv .= "Métrica,Valor\n";
            foreach ($data['panel_data']['kpis'] as $key => $value) {
                $label = ucfirst(str_replace('_', ' ', $key));
                $csv .= "\"{$label}\"," . number_format($value, 2) . "\n";
            }
            $csv .= "\n";
        }

        return $csv;
    }
}
