<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use MatanYadaev\EloquentSpatial\Objects\Point;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear vehículos de prueba para el sistema de recolección de desechos en Carúpano
        $vehicles = [
            [
                'license_plate' => 'CAR-001',
                'brand' => 'Chevrolet',
                'model' => 'NPR',
                'year' => 2020,
                'type' => 'compactor',
                'capacity' => 8.5,
                'status' => 'active',
                'current_location' => new Point(10.6678, -63.2583), // Centro de Carúpano
            ],
            [
                'license_plate' => 'CAR-002',
                'brand' => 'Ford',
                'model' => 'F-350',
                'year' => 2019,
                'type' => 'truck',
                'capacity' => 5.0,
                'status' => 'active',
                'current_location' => new Point(10.6700, -63.2600), // Norte de Carúpano
            ],
            [
                'license_plate' => 'CAR-003',
                'brand' => 'Toyota',
                'model' => 'Hilux',
                'year' => 2021,
                'type' => 'pickup',
                'capacity' => 2.0,
                'status' => 'active',
                'current_location' => new Point(10.6650, -63.2550), // Sur de Carúpano
            ],
            [
                'license_plate' => 'CAR-004',
                'brand' => 'Iveco',
                'model' => 'Daily',
                'year' => 2018,
                'type' => 'van',
                'capacity' => 3.5,
                'status' => 'maintenance',
                'current_location' => new Point(10.6690, -63.2570), // Este de Carúpano
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }

        $this->command->info('Vehículos de prueba creados exitosamente para Carúpano.');
    }
}
