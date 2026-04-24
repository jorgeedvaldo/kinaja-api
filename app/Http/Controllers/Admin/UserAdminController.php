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
}
