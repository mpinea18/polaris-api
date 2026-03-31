<?php

namespace App\Http\Controllers;

use App\Models\DroneModel;
use Illuminate\Http\Request;

class DroneModelController extends Controller
{
    // Listar todos (con búsqueda por marca/modelo)
    public function index(Request $request)
    {
        $query = DroneModel::where('activo', true);

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%");
            });
        }

        if ($request->has('marca') && $request->marca !== '') {
            $query->where('marca', $request->marca);
        }

        return response()->json($query->orderBy('marca')->orderBy('modelo')->get());
    }

    // Ver uno
    public function show($id)
    {
        $model = DroneModel::findOrFail($id);
        return response()->json($model);
    }

    // Crear (solo superadmin)
    public function store(Request $request)
    {
        $request->validate([
            'marca'    => 'required|string',
            'modelo'   => 'required|string',
            'tipo_uas' => 'required|string',
        ]);

        $droneModel = DroneModel::create($request->all());
        return response()->json($droneModel, 201);
    }

    // Actualizar (solo superadmin)
    public function update(Request $request, $id)
    {
        $droneModel = DroneModel::findOrFail($id);
        $droneModel->update($request->all());
        return response()->json($droneModel);
    }

    // Desactivar (solo superadmin)
    public function destroy($id)
    {
        $droneModel = DroneModel::findOrFail($id);
        $droneModel->update(['activo' => false]);
        return response()->json(['message' => 'Modelo desactivado']);
    }

    // Listar marcas únicas
    public function marcas()
    {
        $marcas = DroneModel::where('activo', true)
            ->distinct()
            ->orderBy('marca')
            ->pluck('marca');
        return response()->json($marcas);
    }
}