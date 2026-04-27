<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SituacionCompetencia extends Model
{
    protected $fillable = [
        'ecosistema_laboral_id',
        'codigo',
        'titulo',
        'descripcion',
        'umbral_maestria',
        'nivel_complejidad',
        'activa',
    ];

    protected $table = 'situaciones_competencia';

    protected $casts = [
        'umbral_maestria' => 'decimal:2',
        'activa' => 'boolean',
    ];

    public function ecosistemaLaboral(): BelongsTo
    {
        return $this->belongsTo(EcosistemaLaboral::class);
    }

    public function nodoRequisito(): HasMany
    {
        return $this->hasMany(NodoRequisito::class);
    }

    public function prerequisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            SituacionCompetencia::class,
            'sc_precedencia',
            'sc_id',
            'sc_requisito_id'
        );
    }

    public function dependientes(): BelongsToMany
    {
        return $this->belongsToMany(
            SituacionCompetencia::class,
            'sc_precedencia',
            'sc_requisito_id',
            'sc_id'
        );
    }

    public function criteriosEvaluacion(): BelongsToMany
    {
        return $this->belongsToMany(
            CriterioEvaluacion::class,
            'sc_criterios_evaluacion',
            'situacion_competencia_id',
            'criterio_evaluacion_id'
        )->withPivot('peso_en_sc');
    }

    public function perfilesHabilitacion(): HasMany
    {
        return $this->hasMany(PerfilSituacion::class, 'situacion_competencia_id');
    }
}
