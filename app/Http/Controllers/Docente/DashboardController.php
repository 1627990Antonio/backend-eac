<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Contracts\View\View;

// app/Http/Controllers/Docente/DashboardController.php
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $docenteRoleId = Role::where('name', 'docente')->value('id');

        $ecosistemas = auth()->user()
            ->userRoles()
            ->where('role_id', $docenteRoleId)
            ->with([
                'ecosistemaLaboral.modulo',
                'ecosistemaLaboral.situacionesCompetencia',
                'ecosistemaLaboral.perfilesHabilitacion',
            ])
            ->get()
            ->pluck('ecosistemaLaboral')
            ->filter();

        return view('docente.dashboard', compact('ecosistemas'));
    }
}
