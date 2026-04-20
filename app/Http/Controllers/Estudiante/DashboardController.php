<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// app/Http/Controllers/Estudiante/DashboardController.php
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $perfiles = auth()->user()
            ->perfilesHabilitacion()
            ->with([
                'ecosistemaLaboral.modulo',
                'ecosistemaLaboral.situacionesCompetencia',
                'situacionesConquistadas',
            ])
            ->get();

        return view('estudiante.dashboard', compact('perfiles'));
    }
}
