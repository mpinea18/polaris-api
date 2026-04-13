<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::orderBy('created_at', 'desc')->get());
    }

    public function tecnicos()
    {
        return response()->json(
            User::where('role', 'tech')
                ->select('id', 'name', 'email')
                ->get()
        );
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:client,tech,admin,superadmin',
        ]);
        $user->update(['role' => $request->role]);
        return response()->json($user);
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado.']);
    }
}