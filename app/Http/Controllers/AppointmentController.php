<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Listar citas según rol
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Appointment::with(['user', 'drone']);

        if ($user->role === 'client') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'tech') {
            $query->where('tech_name', $user->name);
        }
        // admin y superadmin ven todas

        return response()->json($query->orderByDesc('created_at')->get());
    }

    // Crear cita
    public function store(Request $request)
    {
        $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'tech_name' => 'required|string',
            'service'   => 'required|string',
            'date'      => 'required|date|after:today',
            'time'      => 'required',
        ]);

        // Verificar que no haya cruce de horarios
        $conflict = Appointment::where('tech_name', $request->tech_name)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->whereNotIn('status', ['done', 'cancelled'])
            ->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'Ese técnico ya tiene una cita en ese horario.',
            ], 422);
        }

        $appointment = Appointment::create([
            'user_id'   => $request->user()->id,
            'drone_id'  => $request->drone_id,
            'tech_name' => $request->tech_name,
            'service'   => $request->service,
            'date'      => $request->date,
            'time'      => $request->time,
            'status'    => 'confirmed',
        ]);

        return response()->json($appointment->load(['user', 'drone']), 201);
    }

    // Actualizar estado
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:confirmed,pending,in_progress,done,cancelled',
        ]);

        $appointment->update(['status' => $request->status]);

        return response()->json($appointment);
    }
}
