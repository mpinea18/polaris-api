<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar todos los usuarios (solo superadmin/admin)
    public function index()
    {
        return response()->json(User::orderBy('created_at', 'desc')->get());
    }

    // Cambiar rol
    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:client,tech,admin,superadmin',
        ]);

        $user->update(['role' => $request->role]);
        return response()->json($user);
    }

    // Activar / desactivar
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);
        return response()->json($user);
    }

    // Eliminar usuario
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado.']);
    }
}
