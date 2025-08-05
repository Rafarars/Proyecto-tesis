<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Route;
use App\Models\Alert;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas principales
        $stats = [
            'vehiculosActivos' => Vehicle::where('status', 'activo')->where('is_active', true)->count(),
            'rutasCompletadas' => Route::where('status', 'completada')->whereDate('created_at', today())->count(),
            'alertasActivas' => Alert::where('status', 'activa')->count(),
            'eficienciaPromedio' => $this->calculateEfficiency()
        ];

        // Vehículos recientes con datos reales
        $vehiculosRecientes = Vehicle::with(['driver', 'currentRoute'])
            ->where('status', 'activo')
            ->where('is_active', true)
            ->orderBy('location_updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($vehicle) {
                return [
                    'id' => $vehicle->code,
                    'conductor' => $vehicle->driver ? $vehicle->driver->full_name : 'Sin asignar',
                    'ruta' => $vehicle->currentRoute ? $vehicle->currentRoute->name : 'Sin ruta',
                    'estado' => $this->getVehicleStatus($vehicle),
                    'ultimaActualizacion' => $vehicle->location_updated_at ? 
                        Carbon::parse($vehicle->location_updated_at)->format('H:i A') : 'N/A'
                ];
            });

        // Alertas recientes con datos reales
        $alertasRecientes = Alert::where('status', 'activa')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($alert) {
                return [
                    'tipo' => $alert->type,
                    'mensaje' => $alert->message,
                    'tiempo' => Carbon::parse($alert->created_at)->diffForHumans(),
                    'severidad' => $alert->severity
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'vehiculosRecientes' => $vehiculosRecientes,
            'alertasRecientes' => $alertasRecientes
        ]);
    }

    private function calculateEfficiency()
    {
        $totalVehicles = Vehicle::where('is_active', true)->count();
        $activeVehicles = Vehicle::where('status', 'activo')->where('is_active', true)->count();
        
        if ($totalVehicles === 0) return 0;
        
        return round(($activeVehicles / $totalVehicles) * 100);
    }

    private function getVehicleStatus($vehicle)
    {
        if (!$vehicle->location_updated_at) {
            return 'sin_datos';
        }

        $lastUpdate = Carbon::parse($vehicle->location_updated_at);
        $minutesSinceUpdate = $lastUpdate->diffInMinutes(now());

        if ($minutesSinceUpdate > 30) {
            return 'inactivo';
        } elseif ($vehicle->current_speed > 0) {
            return 'activo';
        } else {
            return 'detenido';
        }
    }
}
