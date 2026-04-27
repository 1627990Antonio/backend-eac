<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use Illuminate\Contracts\View\View;

// app/Http/Controllers/Publico/PortadaController.php
class PortadaController extends Controller
{
    public function __invoke(): View
    {
        $modulos = Modulo::with([
            'cicloFormativo.familiaProfesional',
            'ecosistemasLaborales' => fn ($q) => $q->where('activo', true),
        ])
            ->whereHas('ecosistemasLaborales', fn ($q) => $q->where('activo', true))
            ->take(6)->get();

        return view('publico.portada', compact('modulos'));
    }
}
