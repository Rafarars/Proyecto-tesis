<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

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
                'code' => 'CAR-001',
                'brand' => 'Chevrolet',
                'model' => 'NPR',
                'year' => 2020,
                'color' => 'Blanco',
                'vin' => 'CHV123456789012345',
                'type' => 'compactador',
                'capacity_tons' => 8.5,
                'fuel_capacity_liters' => 200.0,
                'fuel_type' => 'diesel',
                'engine' => 'Isuzu 4HK1-TC 5.2L Turbo Diesel',
                'status' => 'activo',
                'odometer_km' => 45230.50,
                'last_maintenance' => '2024-12-15',
                'next_maintenance' => '2025-03-15',
                'current_lat' => 10.6678,
                'current_lng' => -63.2583,
                'current_speed' => 0,
                'location_updated_at' => now(),
                'purchase_date' => '2020-03-15',
                'purchase_price' => 85000.00,
                'insurance_policy' => 'POL-001-2024',
                'insurance_expiry' => '2025-03-15',
                'notes' => 'Vehículo principal para recolección en el centro de Carúpano',
                'is_active' => true,
            ],
            [
                'license_plate' => 'CAR-002',
                'code' => 'CAR-002',
                'brand' => 'Ford',
                'model' => 'F-350',
                'year' => 2019,
                'color' => 'Azul',
                'vin' => 'FOR987654321098765',
                'type' => 'camion',
                'capacity_tons' => 5.0,
                'fuel_capacity_liters' => 150.0,
                'fuel_type' => 'diesel',
                'engine' => 'Power Stroke 6.7L V8 Turbo Diesel',
                'status' => 'activo',
                'odometer_km' => 62150.25,
                'last_maintenance' => '2024-11-20',
                'next_maintenance' => '2025-02-20',
                'current_lat' => 10.6720,
                'current_lng' => -63.2620,
                'current_speed' => 25,
                'location_updated_at' => now(),
                'purchase_date' => '2019-08-10',
                'purchase_price' => 65000.00,
                'insurance_policy' => 'POL-002-2024',
                'insurance_expiry' => '2025-08-10',
                'notes' => 'Vehículo para zona norte de Carúpano',
                'is_active' => true,
            ],
            [
                'license_plate' => 'CAR-003',
                'code' => 'CAR-003',
                'brand' => 'Toyota',
                'model' => 'Hilux',
                'year' => 2021,
                'color' => 'Gris',
                'vin' => 'TOY456789012345678',
                'type' => 'pickup',
                'capacity_tons' => 2.0,
                'fuel_capacity_liters' => 80.0,
                'fuel_type' => 'diesel',
                'engine' => '2.8L 4-Cylinder Turbo Diesel',
                'status' => 'mantenimiento',
                'odometer_km' => 28750.75,
                'last_maintenance' => '2024-12-01',
                'next_maintenance' => '2025-06-01',
                'current_lat' => 10.6640,
                'current_lng' => -63.2540,
                'current_speed' => 0,
                'location_updated_at' => now(),
                'purchase_date' => '2021-01-20',
                'purchase_price' => 45000.00,
                'insurance_policy' => 'POL-003-2024',
                'insurance_expiry' => '2025-01-20',
                'notes' => 'En mantenimiento preventivo - cambio de aceite y filtros',
                'is_active' => true,
            ],
            [
                'license_plate' => 'CAR-004',
                'code' => 'CAR-004',
                'brand' => 'Iveco',
                'model' => 'Daily',
                'year' => 2018,
                'color' => 'Verde',
                'vin' => 'IVE789012345678901',
                'type' => 'van',
                'capacity_tons' => 3.5,
                'fuel_capacity_liters' => 90.0,
                'fuel_type' => 'diesel',
                'engine' => 'F1C 3.0L Turbo Diesel',
                'status' => 'activo',
                'odometer_km' => 78920.00,
                'last_maintenance' => '2024-10-15',
                'next_maintenance' => '2025-01-15',
                'current_lat' => 10.6700,
                'current_lng' => -63.2500,
                'current_speed' => 22,
                'location_updated_at' => now(),
                'purchase_date' => '2018-05-30',
                'purchase_price' => 38000.00,
                'insurance_policy' => 'POL-004-2024',
                'insurance_expiry' => '2025-05-30',
                'notes' => 'Vehículo para zona este y malecón de Carúpano',
                'is_active' => true,
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }

        $this->command->info('Vehículos de prueba creados exitosamente para Carúpano.');
    }
}
