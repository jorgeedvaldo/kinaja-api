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

        $query = User::with(['driver', 'restaurants'])->latest();

        $query->when($request->filled('role'), fn($q) => $q->where('role', $request->role))
              ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
              ->when($request->filled('created_from'), fn($q) => $q->whereDate('created_at', '>=', $request->created_from))
              ->when($request->filled('created_to'), fn($q) => $q->whereDate('created_at', '<=', $request->created_to));

        $users = $query->paginate(30)->withQueryString();

        return view('admin.users.index', compact('users'));
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
