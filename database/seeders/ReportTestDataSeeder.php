<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Alert;
use Carbon\Carbon;

class ReportTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear rutas de prueba
        $routes = $this->createTestRoutes();

        // Crear alertas de prueba
        $this->createTestAlerts($routes);
    }

    private function createTestRoutes(): array
    {
        $routes = [
            [
                'name' => 'Ruta Centro',
                'code' => 'RT-001',
                'description' => 'Recolección en el centro de la ciudad',
                'status' => 'completada',
                'type' => 'recoleccion_residencial',
                'assigned_vehicle_id' => 1,
                'total_distance_km' => 15.5,
                'estimated_duration_minutes' => 120,
                'start_time' => '06:00:00',
                'end_time' => '08:00:00',
                'days_of_week' => [1, 2, 3, 4, 5],
                'collection_points' => [
                    ['lat' => 10.4578, 'lng' => -64.1834, 'name' => 'Plaza Bolívar'],
                    ['lat' => 10.4589, 'lng' => -64.1845, 'name' => 'Mercado Central'],
                ],
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Ruta Norte',
                'code' => 'RT-002',
                'description' => 'Recolección zona norte',
                'status' => 'activa',
                'type' => 'recoleccion_comercial',
                'assigned_vehicle_id' => 2,
                'total_distance_km' => 22.3,
                'estimated_duration_minutes' => 180,
                'start_time' => '07:00:00',
                'end_time' => '10:00:00',
                'days_of_week' => [1, 3, 5],
                'collection_points' => [
                    ['lat' => 10.4678, 'lng' => -64.1734, 'name' => 'Urbanización Norte'],
                    ['lat' => 10.4689, 'lng' => -64.1745, 'name' => 'Centro Comercial'],
                ],
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'name' => 'Ruta Sur',
                'code' => 'RT-003',
                'description' => 'Recolección zona sur',
                'status' => 'en_planificacion',
                'type' => 'recoleccion_mixta',
                'assigned_vehicle_id' => 3,
                'total_distance_km' => 18.7,
                'estimated_duration_minutes' => 150,
                'start_time' => '08:00:00',
                'end_time' => '10:30:00',
                'days_of_week' => [2, 4, 6],
                'collection_points' => [
                    ['lat' => 10.4478, 'lng' => -64.1934, 'name' => 'Zona Industrial'],
                    ['lat' => 10.4489, 'lng' => -64.1945, 'name' => 'Puerto'],
                ],
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now(),
            ],
        ];

        $createdRoutes = [];
        foreach ($routes as $routeData) {
            $createdRoutes[] = Route::updateOrCreate(
                ['code' => $routeData['code']],
                $routeData
            );
        }

        return $createdRoutes;
    }

    private function createTestAlerts(array $routes): void
    {
        $alerts = [
            [
                'code' => 'ALT-001',
                'vehicle_id' => 1,
                'route_id' => $routes[0]->id,
                'type' => 'desvio_ruta',
                'priority' => 'media',
                'title' => 'Desvío detectado en Ruta Centro',
                'description' => 'El vehículo se desvió de la ruta programada',
                'status' => 'resuelta',
                'detected_at' => Carbon::now()->subDays(3),
                'resolved_at' => Carbon::now()->subDays(3)->addHours(2),
                'response_time_minutes' => 120,
                'latitude' => 10.4578,
                'longitude' => -64.1834,
                'metadata' => ['distance_deviation' => 500],
                'created_by' => 1,
            ],
            [
                'code' => 'ALT-002',
                'vehicle_id' => 2,
                'route_id' => $routes[1]->id,
                'type' => 'parada_prolongada',
                'priority' => 'baja',
                'title' => 'Parada prolongada detectada',
                'description' => 'El vehículo ha estado detenido por más tiempo del esperado',
                'status' => 'resuelta',
                'detected_at' => Carbon::now()->subDays(2),
                'resolved_at' => Carbon::now()->subDays(2)->addMinutes(45),
                'response_time_minutes' => 45,
                'latitude' => 10.4678,
                'longitude' => -64.1734,
                'metadata' => ['stop_duration' => 30],
                'created_by' => 1,
            ],
            [
                'code' => 'ALT-003',
                'vehicle_id' => 3,
                'route_id' => null,
                'type' => 'perdida_senal',
                'priority' => 'alta',
                'title' => 'Pérdida de señal GPS',
                'description' => 'Se perdió la comunicación con el vehículo',
                'status' => 'activa',
                'detected_at' => Carbon::now()->subHours(4),
                'resolved_at' => null,
                'response_time_minutes' => null,
                'latitude' => 10.4478,
                'longitude' => -64.1934,
                'metadata' => ['last_signal' => Carbon::now()->subHours(4)->toISOString()],
                'created_by' => 1,
            ],
        ];

        foreach ($alerts as $alertData) {
            Alert::updateOrCreate(
                ['code' => $alertData['code']],
                $alertData
            );
        }
    }
}
