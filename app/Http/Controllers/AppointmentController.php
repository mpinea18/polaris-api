<?php
namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Appointment::with(['user', 'drone', 'tech']);

        if ($user->role === 'client') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'tech') {
            $query->where('tecnico_id', $user->id);
        }

        return response()->json($query->orderByDesc('created_at')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'drone_id'   => 'required|exists:drones,id',
            'tecnico_id' => 'required|exists:users,id',
            'service'    => 'required|string',
            'date'       => 'required|date',
            'time'       => 'required|string',
        ]);

        $conflict = Appointment::where('tecnico_id', $request->tecnico_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->whereNotIn('status', ['done', 'cancelled'])
            ->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'Ese técnico ya tiene una cita en ese horario.',
            ], 422);
        }

        $tech = User::find($request->tecnico_id);

        $appointment = Appointment::create([
            'user_id'    => $request->user()->id,
            'drone_id'   => $request->drone_id,
            'tecnico_id' => $request->tecnico_id,
            'empresa_id' => $tech->empresa_id ?? null,
            'tech_name'  => $tech->name,
            'service'    => $request->service,
            'tipo'       => $request->service,
            'date'       => $request->date,
            'fecha'      => $request->date,
            'time'       => $request->time,
            'status'     => 'pending',
        ]);

        return response()->json($appointment->load(['user', 'drone', 'tech']), 201);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:confirmed,pending,in_progress,done,cancelled',
        ]);
        $appointment->update(['status' => $request->status]);
        return response()->json($appointment->load(['user', 'drone', 'tech']));
    }
}