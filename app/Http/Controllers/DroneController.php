<?php
// ── DroneController ────────────────────────────────────────────────────────────

namespace App\Http\Controllers;

use App\Models\Drone;
use Illuminate\Http\Request;

class DroneController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            return response()->json(Drone::where('user_id', $user->id)->get());
        }

        return response()->json(Drone::with('user')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate' => 'required|unique:drones',
            'model' => 'required|string',
        ]);

        $drone = Drone::create([
            'user_id' => $request->user()->id,
            'plate'   => strtoupper($request->plate),
            'model'   => $request->model,
            'hours'   => 0,
            'status'  => 'operational',
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
}
