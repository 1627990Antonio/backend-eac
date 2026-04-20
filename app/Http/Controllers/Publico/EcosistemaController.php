<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// app/Http/Controllers/Publico/EcosistemaController.php
class EcosistemaController extends Controller
{
    public function __invoke(EcosistemaLaboral $ecosistema): View
    {
        $ecosistema->load([
            'modulo.cicloFormativo.familiaProfesional',
            'situacionesCompetencia.prerequisitos',
        ]);

        return view('publico.ecosistemas.show', compact('ecosistema'));
    }
}