<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    /**
     * Display a listing of drivers.
     */
    public function index(Request $request)
    {
        $query = Driver::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('license_status')) {
            $status = $request->license_status;
            if ($status === 'vencida') {
                $query->where('license_expiry', '<=', now());
            } elseif ($status === 'por_vencer') {
                $query->whereBetween('license_expiry', [now(), now()->addDays(30)]);
            } elseif ($status === 'vigente') {
                $query->where('license_expiry', '>', now()->addDays(30));
            }
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'first_name');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Paginación con relaciones
        $drivers = $query->with(['vehicles' => function($query) {
            $query->where('status', 'activo');
        }])->paginate(15)->withQueryString();

        // Estadísticas para el dashboard
        $stats = [
            'total' => Driver::count(),
            'active' => Driver::where('status', 'activo')->where('is_active', true)->count(),
            'expired_licenses' => Driver::where('license_expiry', '<=', now())->count(),
            'expired_medical' => Driver::whereNotNull('medical_exam_expiry')
                                     ->where('medical_exam_expiry', '<=', now())->count(),
        ];



        return Inertia::render('drivers/Index', [
            'drivers' => $drivers,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status', 'shift', 'license_status']),
            'sort' => ['field' => $sortField, 'direction' => $sortDirection],
        ]);
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create()
    {
        // Obtener vehículos disponibles para asignar
        $availableVehicles = Vehicle::whereNull('driver_id')
                                   ->where('status', 'activo')
                                   ->where('is_active', true)
                                   ->get(['id', 'license_plate', 'code', 'brand', 'model', 'type']);

        return Inertia::render('drivers/Create', [
            'availableVehicles' => $availableVehicles
        ]);
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Información personal
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_type' => 'required|string|in:cedula,pasaporte,extranjeria',
            'document_number' => 'required|string|max:255|unique:drivers',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:masculino,femenino,otro',

            // Información de contacto
            'phone' => 'nullable|string|max:255',
            'phone_emergency' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',

            // Información laboral
            'hire_date' => 'required|date',
            'status' => 'required|in:activo,inactivo,vacaciones,licencia,suspendido',
            'shift' => 'required|in:mañana,tarde,noche,rotativo',
            'salary' => 'nullable|numeric|min:0',

            // Licencias y certificaciones
            'license_number' => 'required|string|max:255',
            'license_type' => 'required|in:A,B,C,D,E',
            'license_expiry' => 'required|date|after:today',
            'has_hazmat_cert' => 'boolean',
            'hazmat_expiry' => 'nullable|date|after:today',

            // Información médica
            'medical_exam_date' => 'nullable|date',
            'medical_exam_expiry' => 'nullable|date|after:today',
            'medical_restrictions' => 'nullable|string',
            'emergency_contact' => 'nullable|string',

            // Auditoría
            'notes' => 'nullable|string',
            'is_active' => 'boolean',

            // Asignación de vehículo
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ]);

        // Generar código de empleado automáticamente
        $validated['employee_code'] = $this->generateEmployeeCode();

        $driver = Driver::create($validated);

        // Si se asignó un vehículo, actualizar el vehículo
        if (isset($validated['vehicle_id']) && $validated['vehicle_id']) {
            Vehicle::where('id', $validated['vehicle_id'])->update(['driver_id' => $driver->id]);
        }

        return redirect()->route('drivers.index')
                        ->with('success', 'Conductor creado exitosamente.');
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver)
    {
        $driver->load(['vehicles']);
        
        // Obtener vehículo actual asignado
        $currentVehicle = $driver->currentVehicle();
        
        // Obtener vehículos disponibles para asignar
        $availableVehicles = Vehicle::whereNull('driver_id')
                                   ->where('status', 'activo')
                                   ->where('is_active', true)
                                   ->get();

        return Inertia::render('drivers/Show', [
            'driver' => $driver,
            'currentVehicle' => $currentVehicle,
            'availableVehicles' => $availableVehicles,
        ]);
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver)
    {
        return Inertia::render('drivers/Edit', [
            'driver' => $driver,
        ]);
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            // Información personal
            'employee_code' => ['required', 'string', 'max:255', Rule::unique('drivers')->ignore($driver->id)],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_type' => 'required|string|in:cedula,pasaporte,extranjeria',
            'document_number' => ['required', 'string', 'max:255', Rule::unique('drivers')->ignore($driver->id)],
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:masculino,femenino,otro',

            // Información de contacto
            'phone' => 'nullable|string|max:255',
            'phone_emergency' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',

            // Información laboral
            'hire_date' => 'required|date',
            'status' => 'required|in:activo,inactivo,vacaciones,licencia,suspendido',
            'shift' => 'required|in:mañana,tarde,noche,rotativo',
            'salary' => 'nullable|numeric|min:0',

            // Licencias y certificaciones
            'license_number' => 'required|string|max:255',
            'license_type' => 'required|in:A,B,C,D,E',
            'license_expiry' => 'required|date|after:today',
            'has_hazmat_cert' => 'boolean',
            'hazmat_expiry' => 'nullable|date|after:today',

            // Información médica
            'medical_exam_date' => 'nullable|date',
            'medical_exam_expiry' => 'nullable|date|after:today',
            'medical_restrictions' => 'nullable|string',
            'emergency_contact' => 'nullable|string',

            // Auditoría
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $driver->update($validated);

        return redirect()->route('drivers.index')
                        ->with('success', 'Conductor actualizado exitosamente.');
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(Driver $driver)
    {
        // Verificar si el conductor tiene vehículos asignados
        if ($driver->vehicles()->exists()) {
            return redirect()->route('drivers.index')
                            ->with('error', 'No se puede eliminar el conductor porque tiene vehículos asignados.');
        }

        $driver->delete();

        return redirect()->route('drivers.index')
                        ->with('success', 'Conductor eliminado exitosamente.');
    }

    /**
     * Assign a vehicle to a driver.
     */
    public function assignVehicle(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        // Verificar que el vehículo no esté asignado a otro conductor
        if ($vehicle->driver_id && $vehicle->driver_id !== $driver->id) {
            return redirect()->back()
                            ->with('error', 'El vehículo ya está asignado a otro conductor.');
        }

        // Desasignar vehículo anterior del conductor si existe
        Vehicle::where('driver_id', $driver->id)->update(['driver_id' => null]);

        // Asignar nuevo vehículo
        $vehicle->update(['driver_id' => $driver->id]);

        return redirect()->back()
                        ->with('success', 'Vehículo asignado exitosamente.');
    }

    /**
     * Unassign vehicle from driver.
     */
    public function unassignVehicle(Driver $driver)
    {
        Vehicle::where('driver_id', $driver->id)->update(['driver_id' => null]);

        return redirect()->back()
                        ->with('success', 'Vehículo desasignado exitosamente.');
    }

    /**
     * Generate a unique employee code.
     */
    private function generateEmployeeCode()
    {
        $year = date('Y');
        $prefix = 'EMP-' . $year . '-';

        // Buscar el último código del año actual
        $lastDriver = Driver::where('employee_code', 'like', $prefix . '%')
                           ->orderBy('employee_code', 'desc')
                           ->first();

        if ($lastDriver) {
            // Extraer el número del último código
            $lastNumber = (int) str_replace($prefix, '', $lastDriver->employee_code);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
