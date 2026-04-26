<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverApplication;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DriverApplicationAdminController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $query = DriverApplication::latest();

        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status));

        $applications = $query->paginate(20)->withQueryString();

        return view('admin.applications.drivers.index', compact('applications'));
    }

    public function show(Request $request, DriverApplication $application)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        return view('admin.applications.drivers.show', compact('application'));
    }

    public function updateStatus(Request $request, DriverApplication $application)
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
            $application = DriverApplication::where('id', $application->id)->lockForUpdate()->first();

            if ($application->status !== 'pending') {
                return back()->with('error', 'Esta candidatura já foi processada anteriormente.');
            }

            if ($validated['status'] === 'approved') {
                if (!$application->id_document_path || !$application->driver_license_path) {
                    return back()->with('error', 'Não é possível aprovar a candidatura sem os documentos obrigatórios carregados.');
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
                    'role'     => 'driver',
                    'status'   => 'active',
                ]);

                Driver::create([
                    'user_id'       => $user->id,
                    'vehicle_type'  => $application->owns_motorcycle ? 'motorcycle' : 'none',
                ]);

                $application->update([
                    'status'        => 'approved',
                    'reviewed_by'   => $request->user()->id,
                    'reviewed_at'   => now(),
                    'user_id'       => $user->id,
                ]);

                return redirect()->route('admin.driver_applications.index')->with('success', 'Motorista aprovado e conta criada com sucesso.');
            } else {
                $application->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'reviewed_by'      => $request->user()->id,
                    'reviewed_at'      => now(),
                ]);
                return redirect()->route('admin.driver_applications.index')->with('success', 'Candidatura rejeitada.');
            }
        });
    }

    public function uploadDocument(Request $request, DriverApplication $application)
    {
        if (!$request->user()->isAdmin()) abort(403);
        
        $request->validate([
            'document_type' => 'required|in:id_document,driver_license',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('file')->store('applications/drivers', 'public');

        if ($request->document_type === 'id_document') {
            $application->update(['id_document_path' => $path]);
        } else {
            $application->update(['driver_license_path' => $path]);
        }

        return back()->with('success', 'Documento carregado e associado à candidatura.');
    }
}
