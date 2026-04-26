<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantApplication;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RestaurantApplicationAdminController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $query = RestaurantApplication::latest();

        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status));

        $applications = $query->paginate(20)->withQueryString();

        return view('admin.applications.restaurants.index', compact('applications'));
    }

    public function show(Request $request, RestaurantApplication $application)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        return view('admin.applications.restaurants.show', compact('application'));
    }

    public function updateStatus(Request $request, RestaurantApplication $application)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|nullable|max:1000',
        ]);

        return DB::transaction(function () use ($request, $application, $validated) {
            // Evitar race conditions trancando o registo para leitura/escrita
            $application = RestaurantApplication::where('id', $application->id)->lockForUpdate()->first();

            if ($application->status !== 'pending') {
                return back()->with('error', 'Esta candidatura já foi processada anteriormente.');
            }

            if ($validated['status'] === 'approved') {
                if (!$application->business_license_path) {
                    return back()->with('error', 'Não é possível aprovar a candidatura sem o alvará/licença carregado no sistema.');
                }

                $exists = User::where('phone', $application->phone)
                              ->orWhere(function($query) use ($application) {
                                  if ($application->email) {
                                      $query->where('email', $application->email);
                                  }
                              })->exists();

                if ($exists) {
                    return back()->with('error', 'Ocorreu um erro: Já existe um utilizador registado com este telefone ou email na plataforma.');
                }

                $user = User::create([
                    'name'     => $application->name,
                    'phone'    => $application->phone,
                    'email'    => $application->email,
                    'password' => Hash::make(Str::random(10)),
                    'role'     => 'restaurant_owner',
                    'status'   => 'active',
                ]);

                $restaurant = Restaurant::create([
                    'user_id'      => $user->id,
                    'name'         => $application->name,
                    'cuisine_type' => 'Geral', // Tipo genérico por omissão
                ]);

                $application->update([
                    'status'        => 'approved',
                    'reviewed_by'   => $request->user()->id,
                    'reviewed_at'   => now(),
                    'user_id'       => $user->id,
                    'restaurant_id' => $restaurant->id,
                ]);

                return redirect()->route('admin.restaurant_applications.index')->with('success', 'Restaurante aprovado e conta criada com sucesso.');
            } else {
                $application->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'reviewed_by'      => $request->user()->id,
                    'reviewed_at'      => now(),
                ]);
                return redirect()->route('admin.restaurant_applications.index')->with('success', 'Candidatura rejeitada.');
            }
        });
    }

    public function uploadDocument(Request $request, RestaurantApplication $application)
    {
        if (!$request->user()->isAdmin()) abort(403);
        
        $request->validate([
            'document_type' => 'required|in:business_license',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('file')->store('applications/restaurants', 'public');

        $application->update(['business_license_path' => $path]);

        return back()->with('success', 'Alvará/Licença carregado e associado à candidatura.');
    }
}
