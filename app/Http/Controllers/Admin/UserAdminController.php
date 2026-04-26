<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        // Only admin can view all users
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem ver utilizadores.');
        }

        $role = $request->query('role');

        $query = User::with(['driver', 'restaurants'])->latest();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->paginate(30);

        return view('admin.users.index', compact('users', 'role'));
    }

    public function updateStatus(Request $request, User $user)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem alterar o status.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:active,suspended,banned',
        ]);

        $user->update([
            'status'            => $validated['status'],
            'status_updated_by' => $request->user()->id,
            'status_updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status do utilizador atualizado com sucesso.');
    }
}
