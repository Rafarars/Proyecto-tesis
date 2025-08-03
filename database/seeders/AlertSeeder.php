<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alert;
use App\Models\AlertRule;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Route;

class AlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear reglas de alerta básicas
        $this->createAlertRules();

        // Crear algunas alertas de ejemplo
        $this->createSampleAlerts();
    }

    private function createAlertRules(): void
    {
        $user = User::first();

        $rules = [
            [
                'name' => 'Desvío de Ruta Crítico',
                'code' => 'AR-001',
                'type' => 'desvio_ruta',
                'description' => 'Detecta cuando un vehículo se desvía más de 200 metros de la ruta asignada',
                'conditions' => [
                    'distance_threshold' => 200,
                    'unit' => 'meters'
                ],
                'priority' => 'alta',
                'is_active' => true,
                'send_notification' => true,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Parada Prolongada',
                'code' => 'AR-002',
                'type' => 'parada_prolongada',
                'description' => 'Alerta cuando un vehículo permanece inmóvil más de 30 minutos',
                'conditions' => [
                    'time_threshold' => 30,
                    'unit' => 'minutes'
                ],
                'priority' => 'media',
                'is_active' => true,
                'send_notification' => true,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Pérdida de Señal GPS',
                'code' => 'AR-003',
                'type' => 'perdida_senal',
                'description' => 'Detecta cuando se pierde la comunicación GPS por más de 5 minutos',
                'conditions' => [
                    'time_threshold' => 5,
                    'unit' => 'minutes'
                ],
                'priority' => 'critica',
                'is_active' => true,
                'send_notification' => true,
                'escalation_time_minutes' => 10,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Fuera de Zona Autorizada',
                'code' => 'AR-004',
                'type' => 'fuera_zona',
                'description' => 'Alerta cuando un vehículo sale del área municipal autorizada',
                'conditions' => [
                    'zone_type' => 'municipal',
                    'buffer_meters' => 100
                ],
                'priority' => 'alta',
                'is_active' => true,
                'send_notification' => true,
                'created_by' => $user->id,
            ],
        ];

        foreach ($rules as $ruleData) {
            AlertRule::create($ruleData);
        }
    }

    private function createSampleAlerts(): void
    {
        $vehicles = Vehicle::limit(3)->get();
        $routes = Route::limit(2)->get();
        $user = User::first();
        $alertRules = AlertRule::all();

        if ($vehicles->isEmpty() || !$user) {
            return;
        }

        $alerts = [
            [
                'code' => 'AL-0001',
                'type' => 'desvio_ruta',
                'priority' => 'alta',
                'status' => 'abierta',
                'vehicle_id' => $vehicles->first()->id,
                'route_id' => $routes->first()?->id,
                'alert_rule_id' => $alertRules->where('type', 'desvio_ruta')->first()?->id,
                'created_by' => $user->id,
                'latitude' => 10.6700,
                'longitude' => -63.2600,
                'location_address' => 'Av. Perimetral, Carúpano',
                'title' => 'Desvío de ruta detectado - ' . $vehicles->first()->license_plate,
                'description' => 'El vehículo se ha desviado 250 metros de la ruta asignada. Verificar ubicación y contactar al conductor.',
                'metadata' => [
                    'deviation_distance' => 250,
                    'expected_location' => ['lat' => 10.6678, 'lng' => -63.2583],
                    'actual_location' => ['lat' => 10.6700, 'lng' => -63.2600]
                ],
                'detected_at' => now()->subMinutes(15),
            ],
            [
                'code' => 'AL-0002',
                'type' => 'parada_prolongada',
                'priority' => 'media',
                'status' => 'en_atencion',
                'vehicle_id' => $vehicles->skip(1)->first()->id,
                'alert_rule_id' => $alertRules->where('type', 'parada_prolongada')->first()?->id,
                'created_by' => $user->id,
                'assigned_to' => $user->id,
                'latitude' => 10.6650,
                'longitude' => -63.2550,
                'location_address' => 'Sector El Guapo, Carúpano',
                'title' => 'Parada prolongada - ' . $vehicles->skip(1)->first()->license_plate,
                'description' => 'El vehículo ha permanecido inmóvil por 45 minutos. Verificar estado del vehículo y conductor.',
                'metadata' => [
                    'stop_duration' => 45,
                    'last_movement' => now()->subMinutes(45)->toISOString()
                ],
                'detected_at' => now()->subMinutes(45),
                'acknowledged_at' => now()->subMinutes(30),
                'response_time_minutes' => 15,
            ],
            [
                'code' => 'AL-0003',
                'type' => 'perdida_senal',
                'priority' => 'critica',
                'status' => 'resuelta',
                'vehicle_id' => $vehicles->skip(2)->first()->id,
                'alert_rule_id' => $alertRules->where('type', 'perdida_senal')->first()?->id,
                'created_by' => $user->id,
                'assigned_to' => $user->id,
                'resolved_by' => $user->id,
                'latitude' => 10.6620,
                'longitude' => -63.2570,
                'location_address' => 'Zona Industrial, Carúpano',
                'title' => 'Pérdida de señal GPS - ' . $vehicles->skip(2)->first()->license_plate,
                'description' => 'Se perdió la comunicación GPS por 8 minutos. Señal restablecida automáticamente.',
                'metadata' => [
                    'signal_lost_duration' => 8,
                    'signal_restored_at' => now()->subMinutes(5)->toISOString(),
                    'last_known_location' => ['lat' => 10.6620, 'lng' => -63.2570]
                ],
                'detected_at' => now()->subMinutes(13),
                'acknowledged_at' => now()->subMinutes(10),
                'resolved_at' => now()->subMinutes(5),
                'response_time_minutes' => 3,
                'resolution_time_minutes' => 5,
            ],
        ];

        foreach ($alerts as $alertData) {
            Alert::create($alertData);
        }
    }
}
