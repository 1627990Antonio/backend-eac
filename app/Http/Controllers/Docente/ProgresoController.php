<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// app/Http/Controllers/Docente/ProgresoController.php
class ProgresoController extends Controller
{
    public function __invoke(EcosistemaLaboral $ecosistema): View
    {
        $esDocente = auth()->user()
            ->userRoles()
            ->where('ecosistema_laboral_id', $ecosistema->id)
            ->whereHas('role', fn($q) => $q->where('name', 'docente'))
            ->exists();

        abort_unless($esDocente, 403);

        $ecosistema->load(['situacionesCompetencia', 'modulo']);

        $perfiles = $ecosistema->perfilesHabilitacion()
            ->with(['estudiante', 'situacionesConquistadas'])
            ->get();

        return view('docente.progreso.show', compact('ecosistema', 'perfiles'));
    }
}
