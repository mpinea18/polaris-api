<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DroneHistoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'drone_id'            => 'required|exists:drones,id',
            'tipo'                => 'required|in:garantia,mantenimiento,reparacion',
            'descripcion'         => 'required|string',
            'partes_reemplazadas' => 'nullable|string',
            'seriales_nuevos'     => 'nullable|string',
            'resultado'           => 'required|string',
            'horas_trabajo'       => 'nullable|integer',
            'appointment_id'      => 'nullable|exists:appointments,id',
            'warranty_id'         => 'nullable|exists:warranties,id',
        ]);

        $user = $request->user();

        $history = DB::table('drone_history')->insertGetId([
            'drone_id'            => $data['drone_id'],
            'empresa_id'          => $user->empresa_id,
            'tecnico_id'          => $user->id,
            'tipo'                => $data['tipo'],
            'descripcion'         => $data['descripcion'],
            'partes_reemplazadas' => $data['partes_reemplazadas'] ?? null,
            'seriales_nuevos'     => $data['seriales_nuevos'] ?? null,
            'resultado'           => $data['resultado'],
            'horas_trabajo'       => $data['horas_trabajo'] ?? null,
            'appointment_id'      => $data['appointment_id'] ?? null,
            'warranty_id'         => $data['warranty_id'] ?? null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return response()->json(['id' => $history, 'message' => 'Historia clínica guardada'], 201);
    }

    public function getByDrone($droneId)
    {
        $history = DB::table('drone_history')
            ->join('users', 'drone_history.tecnico_id', '=', 'users.id')
            ->join('empresas', 'drone_history.empresa_id', '=', 'empresas.id')
            ->where('drone_history.drone_id', $droneId)
            ->orderBy('drone_history.created_at', 'desc')
            ->select(
                'drone_history.*',
                'users.name as tecnico_nombre',
                'empresas.nombre as empresa_nombre'
            )
            ->get();

        return response()->json($history);
    }
}