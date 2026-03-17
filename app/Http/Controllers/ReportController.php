<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Appointment;
use App\Models\Drone;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Report::with(['drone', 'appointment']);

        if ($user->role === 'tech') {
            $query->where('user_id', $user->id);
        }

        return response()->json($query->orderByDesc('created_at')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'drone_id'        => 'required|exists:drones,id',
            'condition'       => 'required|string',
            'recommendation'  => 'required|string',
            'drone_new_status'=> 'required|in:operational,maintenance,critical',
        ]);

        // Crear el reporte
        $report = Report::create([
            'drone_id'         => $request->drone_id,
            'appointment_id'   => $request->appointment_id,
            'user_id'          => $request->user()->id,
            'condition'        => $request->condition,
            'recommendation'   => $request->recommendation,
            'observations'     => $request->observations,
            'components'       => $request->components,
            'photos_count'     => $request->photos_count ?? 0,
            'drone_new_status' => $request->drone_new_status,
        ]);

        // Marcar la cita como completada
        if ($request->appointment_id) {
            Appointment::where('id', $request->appointment_id)
                ->update(['status' => 'done']);
        }

        // Actualizar estado del drone
        Drone::where('id', $request->drone_id)->update([
            'status'       => $request->drone_new_status,
            'last_service' => now()->toDateString(),
        ]);

        return response()->json($report->load(['drone', 'appointment']), 201);
    }

    public function droneHistory(Drone $drone)
    {
        return response()->json(
            Report::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->get()
        );
    }
}
