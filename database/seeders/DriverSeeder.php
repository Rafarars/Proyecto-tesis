<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear conductores de prueba para el sistema de recolección de desechos en Carúpano
        $drivers = [
            [
                'employee_code' => 'DRV-001',
                'first_name' => 'Carlos',
                'last_name' => 'Rodríguez',
                'document_type' => 'cedula',
                'document_number' => '12345678',
                'birth_date' => '1985-03-15',
                'gender' => 'masculino',
                'phone' => '+58-414-1234567',
                'phone_emergency' => '+58-424-7654321',
                'email' => 'carlos.rodriguez@carupano.gob.ve',
                'address' => 'Calle Bolívar, Casa #45, Centro, Carúpano',
                'hire_date' => '2020-01-15',
                'status' => 'activo',
                'shift' => 'mañana',
                'salary' => 450.00,
                'license_number' => 'LIC-001-2024',
                'license_type' => 'C',
                'license_expiry' => '2026-03-15',
                'has_hazmat_cert' => true,
                'hazmat_expiry' => '2025-12-31',
                'medical_exam_date' => '2024-06-15',
                'medical_exam_expiry' => '2025-06-15',
                'medical_restrictions' => null,
                'emergency_contact' => 'María Rodríguez - Esposa - +58-416-9876543',
                'notes' => 'Conductor experimentado con 15 años en el sector',
                'is_active' => true,
            ],
            [
                'employee_code' => 'DRV-002',
                'first_name' => 'José',
                'last_name' => 'Martínez',
                'document_type' => 'cedula',
                'document_number' => '23456789',
                'birth_date' => '1990-07-22',
                'gender' => 'masculino',
                'phone' => '+58-414-2345678',
                'phone_emergency' => '+58-424-8765432',
                'email' => 'jose.martinez@carupano.gob.ve',
                'address' => 'Av. Perimetral, Edificio Los Cocos, Apto 3B, Carúpano',
                'hire_date' => '2021-03-10',
                'status' => 'activo',
                'shift' => 'mañana',
                'salary' => 420.00,
                'license_number' => 'LIC-002-2023',
                'license_type' => 'C',
                'license_expiry' => '2025-07-22',
                'has_hazmat_cert' => false,
                'hazmat_expiry' => null,
                'medical_exam_date' => '2024-03-10',
                'medical_exam_expiry' => '2025-03-10',
                'medical_restrictions' => null,
                'emergency_contact' => 'Ana Martínez - Madre - +58-414-5555555',
                'notes' => 'Conductor responsable, especializado en zona norte',
                'is_active' => true,
            ],
            [
                'employee_code' => 'DRV-003',
                'first_name' => 'Luis',
                'last_name' => 'González',
                'document_type' => 'cedula',
                'document_number' => '34567890',
                'birth_date' => '1988-11-08',
                'gender' => 'masculino',
                'phone' => '+58-414-3456789',
                'phone_emergency' => '+58-424-9876543',
                'email' => 'luis.gonzalez@carupano.gob.ve',
                'address' => 'Sector El Morro, Calle Principal, Casa #12, Carúpano',
                'hire_date' => '2019-08-20',
                'status' => 'vacaciones',
                'shift' => 'tarde',
                'salary' => 435.00,
                'license_number' => 'LIC-003-2022',
                'license_type' => 'B',
                'license_expiry' => '2025-11-08',
                'has_hazmat_cert' => false,
                'hazmat_expiry' => null,
                'medical_exam_date' => '2024-08-20',
                'medical_exam_expiry' => '2025-08-20',
                'medical_restrictions' => 'No puede levantar más de 25kg',
                'emergency_contact' => 'Carmen González - Esposa - +58-416-1111111',
                'notes' => 'Conductor de pickup, actualmente en vacaciones',
                'is_active' => true,
            ],
            [
                'employee_code' => 'DRV-004',
                'first_name' => 'Miguel',
                'last_name' => 'Hernández',
                'document_type' => 'cedula',
                'document_number' => '45678901',
                'birth_date' => '1992-05-30',
                'gender' => 'masculino',
                'phone' => '+58-414-4567890',
                'phone_emergency' => '+58-424-0987654',
                'email' => 'miguel.hernandez@carupano.gob.ve',
                'address' => 'Zona Este, Calle Costanera, Casa #78, Carúpano',
                'hire_date' => '2022-02-14',
                'status' => 'activo',
                'shift' => 'mañana',
                'salary' => 400.00,
                'license_number' => 'LIC-004-2024',
                'license_type' => 'C',
                'license_expiry' => '2026-05-30',
                'has_hazmat_cert' => true,
                'hazmat_expiry' => '2025-08-15',
                'medical_exam_date' => '2024-02-14',
                'medical_exam_expiry' => '2025-02-14',
                'medical_restrictions' => null,
                'emergency_contact' => 'Rosa Hernández - Madre - +58-414-2222222',
                'notes' => 'Conductor joven y entusiasta, especializado en zona costera',
                'is_active' => true,
            ],
        ];

        foreach ($drivers as $driverData) {
            Driver::create($driverData);
        }

        $this->command->info('Conductores de prueba creados exitosamente para Carúpano.');
    }
}
