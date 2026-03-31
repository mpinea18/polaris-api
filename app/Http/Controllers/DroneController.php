<?php

namespace App\Http\Controllers;

use App\Models\Drone;
use Illuminate\Http\Request;

class DroneController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            return response()->json(
                Drone::where('user_id', $user->id)
                    ->with('droneModel')
                    ->get()
            );
        }

        return response()->json(Drone::with(['user','droneModel'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_serie' => 'required|unique:drones,numero_serie',
            'tipo_uas'     => 'required|string',
            'color'        => 'required|string',
            'peso_real'    => 'required|numeric',
        ]);

        $drone = Drone::create([
            'user_id'              => $request->user()->id,
            'plate'                => strtoupper($request->numero_serie),
            'model'                => $request->model ?? ($request->marca . ' ' . ($request->modelo ?? '')),
            'marca'                => $request->marca,
            'drone_model_id'       => $request->drone_model_id,
            'numero_serie'         => strtoupper($request->numero_serie),
            'tipo_uas'             => $request->tipo_uas,
            'num_motores'          => $request->num_motores,
            'color'                => $request->color,
            'peso_real'            => $request->peso_real,
            'tipo_registro'        => $request->tipo_registro ?? 'Primera vez',
            'sistemas'             => $request->sistemas ?? [],
            'poliza'               => $request->poliza,
            'sistema_recuperacion' => $request->sistema_recuperacion,
            'equipo_fabrica'       => $request->equipo_fabrica,
            'hours'                => 0,
            'status'               => 'operational',
        ]);

        return response()->json($drone, 201);
    }

    public function updateStatus(Request $request, Drone $drone)
    {
        $request->validate([
            'status' => 'required|in:operational,maintenance,critical',
        ]);

        $drone->update([
            'status'       => $request->status,
            'last_service' => now()->toDateString(),
        ]);

        return response()->json($drone);
    }

    public function destroy(Request $request, $id)
    {
        $drone = Drone::findOrFail($id);
        if ($request->user()->role === 'client' && $drone->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        $drone->delete();
        return response()->json(['message' => 'Aeronave eliminada']);
    }
}