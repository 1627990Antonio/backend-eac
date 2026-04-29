<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use Illuminate\View\View;

class PortadaController extends Controller
{
    public function __invoke(): View
    {
        $modulos = Modulo::with([
            'cicloFormativo.familiaProfesional',
            'ecosistemasLaborales' => fn($q) => $q->where('activo', true),
        ])
            ->whereHas('ecosistemasLaborales', fn($q) => $q->where('activo', true))
            ->take(6)->get();

        return view('publico.portada', compact('modulos'));
    }
}
