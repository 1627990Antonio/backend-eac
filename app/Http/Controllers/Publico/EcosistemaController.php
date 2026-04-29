<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\EcosistemaLaboral;
use Illuminate\View\View;

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
