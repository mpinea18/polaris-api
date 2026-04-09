<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WarrantyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            return response()->json(
                Warranty::where('user_id', $user->id)
                    ->with(['drone','tech'])
                    ->orderByDesc('created_at')
                    ->get()
            );
        }

        if ($user->role === 'tech') {
            return response()->json(
                Warranty::where('tech_id', $user->id)
                    ->with(['user','drone'])
                    ->orderByDesc('created_at')
                    ->get()
            );
        }

        return response()->json(
            Warranty::with(['user','drone','tech'])
                ->orderByDesc('created_at')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string',
            'cedula_nit'      => 'required|string',
            'direccion'       => 'required|string',
            'ciudad'          => 'required|string',
            'telefono'        => 'required|string',
            'codigo_producto' => 'required|string',
            'nombre_producto' => 'required|string',
            'numero_factura'  => 'required|string',
            'fecha_compra'    => 'required|date',
            'numero_serial'   => 'required|string',
            'falla_reportada' => 'required|string',
            'contenido'       => 'required|string',
        ]);

        // Manejar archivos adjuntos
        $adjuntos = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('warranties', 'public');
                $adjuntos[] = [
                    'nombre' => $file->getClientOriginalName(),
                    'path'   => $path,
                    'url'    => Storage::url($path),
                    'tipo'   => $file->getMimeType(),
                    'size'   => $file->getSize(),
                ];
            }
        }

        $warranty = Warranty::create([
            'user_id'          => $request->user()->id,
            'nombre'           => $request->nombre,
            'cedula_nit'       => $request->cedula_nit,
            'direccion'        => $request->direccion,
            'ciudad'           => $request->ciudad,
            'telefono'         => $request->telefono,
            'codigo_producto'  => $request->codigo_producto,
            'nombre_producto'  => $request->nombre_producto,
            'numero_factura'   => $request->numero_factura,
            'fecha_compra'     => $request->fecha_compra,
            'numero_serial'    => $request->numero_serial,
            'serial_baterias'  => $request->serial_baterias,
            'serial_control'   => $request->serial_control,
            'serial_cargador'  => $request->serial_cargador,
            'falla_reportada'  => $request->falla_reportada,
            'contenido'        => $request->contenido,
            'usuario_final'    => $request->usuario_final ?? true,
            'sufrió_accidente' => $request->sufrió_accidente ?? false,
            'observaciones'    => $request->observaciones,
            'adjuntos'         => $adjuntos,
            'status'           => 'pendiente',
        ]);

        return response()->json($warranty, 201);
    }

    public function approve(Request $request, $id)
    {
        $request->validate(['tech_id' => 'required|exists:users,id']);
        $warranty = Warranty::findOrFail($id);
        $warranty->update(['status' => 'aprobada', 'tech_id' => $request->tech_id]);
        return response()->json($warranty);
    }

    public function deny(Request $request, $id)
    {
        $request->validate(['motivo_negacion' => 'required|string']);
        $warranty = Warranty::findOrFail($id);
        $warranty->update(['status' => 'negada', 'motivo_negacion' => $request->motivo_negacion]);
        return response()->json($warranty);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pendiente,aprobada,negada,en_proceso,completada']);
        $warranty = Warranty::findOrFail($id);
        $warranty->update(['status' => $request->status]);
        return response()->json($warranty);
    }
}
