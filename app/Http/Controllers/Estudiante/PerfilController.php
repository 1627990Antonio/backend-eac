<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// app/Http/Controllers/Estudiante/PerfilController.php
class PerfilController extends Controller
{
    public function __invoke(PerfilHabilitacion $perfil): View
    {
        abort_unless($perfil->estudiante_id === auth()->id(), 403);

        $perfil->load([
            'ecosistemaLaboral.modulo',
            'ecosistemaLaboral.situacionesCompetencia.prerequisitos',
            'situacionesConquistadas',
        ]);

        return view('estudiante.perfil.show', compact('perfil'));
    }
}
