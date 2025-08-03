<?php

namespace App\Http\Controllers;

use App\Models\AlertRule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlertRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alertRules = AlertRule::orderBy('created_at', 'desc')->get();

        return Inertia::render('alerts/Index', [
            'activePanel' => 'configuracion',
            'panelData' => [
                'alert_rules' => $alertRules
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('alerts/CreateRule');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:alert_rules,code',
            'type' => 'required|string|in:desvio_ruta,parada_prolongada,velocidad_excesiva,combustible_bajo,mantenimiento_vencido,zona_restringida',
            'priority' => 'required|string|in:baja,media,alta,critica',
            'description' => 'nullable|string',
            'threshold_value' => 'nullable|numeric',
            'threshold_unit' => 'nullable|string|max:50',
            'active_from' => 'nullable|date_format:H:i',
            'active_to' => 'nullable|date_format:H:i',
            'active_days' => 'nullable|array',
            'active_days.*' => 'integer|between:0,6',
            'is_active' => 'boolean'
        ]);

        // Convertir array de días a JSON
        if (isset($validated['active_days'])) {
            $validated['active_days'] = json_encode($validated['active_days']);
        }

        AlertRule::create($validated);

        return redirect()->route('alerts.index', ['panel' => 'configuracion'])
            ->with('success', 'Regla de alerta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AlertRule $alertRule)
    {
        return Inertia::render('alerts/ShowRule', [
            'alertRule' => $alertRule
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AlertRule $alertRule)
    {
        return Inertia::render('alerts/EditRule', [
            'alertRule' => $alertRule
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AlertRule $alertRule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:alert_rules,code,' . $alertRule->id,
            'type' => 'required|string|in:desvio_ruta,parada_prolongada,velocidad_excesiva,combustible_bajo,mantenimiento_vencido,zona_restringida',
            'priority' => 'required|string|in:baja,media,alta,critica',
            'description' => 'nullable|string',
            'threshold_value' => 'nullable|numeric',
            'threshold_unit' => 'nullable|string|max:50',
            'active_from' => 'nullable|date_format:H:i',
            'active_to' => 'nullable|date_format:H:i',
            'active_days' => 'nullable|array',
            'active_days.*' => 'integer|between:0,6',
            'is_active' => 'boolean'
        ]);

        // Convertir array de días a JSON
        if (isset($validated['active_days'])) {
            $validated['active_days'] = json_encode($validated['active_days']);
        }

        $alertRule->update($validated);

        return redirect()->route('alerts.index', ['panel' => 'configuracion'])
            ->with('success', 'Regla de alerta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AlertRule $alertRule)
    {
        $alertRule->delete();

        return redirect()->route('alerts.index', ['panel' => 'configuracion'])
            ->with('success', 'Regla de alerta eliminada exitosamente.');
    }

    /**
     * Toggle the active status of the alert rule.
     */
    public function toggle(AlertRule $alertRule)
    {
        $alertRule->update([
            'is_active' => !$alertRule->is_active
        ]);

        $status = $alertRule->is_active ? 'activada' : 'desactivada';

        return redirect()->back()
            ->with('success', "Regla {$status} exitosamente.");
    }
}
