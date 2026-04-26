<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $role = $request->role ?? 'client';

        if ($role === 'driver') {
            $request->validate([
                'name'  => 'required|string|max:255',
                'phone' => 'required|string|unique:users,phone|unique:driver_applications,phone',
                'email' => 'nullable|email|unique:users,email|unique:driver_applications,email',
                'owns_motorcycle' => 'nullable|boolean',
            ]);

            $application = \App\Models\DriverApplication::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'owns_motorcycle' => $request->boolean('owns_motorcycle'),
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Candidatura de entregador enviada com sucesso! Aguarde o nosso contacto.',
                'application' => $application,
            ], 201);
        }

        if ($role === 'restaurant_owner') {
            $request->validate([
                'name'    => 'required|string|max:255',
                'phone'   => 'required|string|unique:users,phone|unique:restaurant_applications,phone',
                'email'   => 'required|email|unique:users,email|unique:restaurant_applications,email',
                'address' => 'required|string|max:255',
                'nif'     => 'required|string|max:255',
            ]);

            $application = \App\Models\RestaurantApplication::create([
                'name'    => $request->name,
                'phone'   => $request->phone,
                'email'   => $request->email,
                'address' => $request->address,
                'nif'     => $request->nif,
                'status'  => 'pending',
            ]);

            return response()->json([
                'message' => 'Candidatura de restaurante enviada com sucesso! Aguarde o nosso contacto.',
                'application' => $application,
            ], 201);
        }

        // Default: Client registration
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'email'    => 'nullable|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'client',
            'status'   => 'active',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login user and create token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ]);

        $identifier = $request->identifier;

        $user = User::where('phone', $identifier)
                    ->orWhere('email', $identifier)
                    ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'E-mail, número ou senha incorrectos.',
            ], 401);
        }

        if ($user->status === 'suspended') {
            return response()->json(['message' => 'Sua conta foi suspensa por um administrador.'], 403);
        }
        if ($user->status === 'banned') {
            return response()->json(['message' => 'A sua conta foi banida permanentemente.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout user (Revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get the authenticated user.
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
