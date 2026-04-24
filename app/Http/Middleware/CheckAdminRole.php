<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     * Only allows admin and restaurant_owner roles to access admin panel.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !in_array($request->user()->role, ['admin', 'restaurant_owner'])) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acesso não autorizado.'], 403);
            }
            return redirect()->route('admin.login')->withErrors([
                'identifier' => 'Acesso restrito a administradores e donos de restaurantes.',
            ]);
        }

        return $next($request);
    }
}
