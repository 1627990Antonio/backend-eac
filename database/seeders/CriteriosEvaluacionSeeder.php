<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\ResultadoAprendizaje;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriosEvaluacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/csv/criterios_evaluacion.csv');

        if (! file_exists($path)) {
            $this->command->error("CSV no encontrado: $path");

            return;
        }

        $rows = array_map('str_getcsv', file($path));

        $header = array_map('trim', array_shift($rows));

        $data = [];
        foreach ($rows as $row) {
            if (count($row) < count($header)) {
                continue;
            }

            $rec = array_combine($header, $row);

            $moduloId = Modulo::where('codigo', trim($rec['cod_modulo'] ?? ''))->first()->id ?? null;

            if (! $moduloId) {
                $this->command->warn("Módulo no encontrado para código '{$rec['cod_modulo']}'");

                continue;
            }

            $resultadoId = ResultadoAprendizaje::where(['modulo_id' => $moduloId, 'codigo' => 'RA'.trim($rec['id_ra'] ?? '')])->first()->id ?? null;

            if (! $resultadoId) {
                $this->command->warn("Resultado de aprendizaje no encontrado para módulo '{$rec['cod_modulo']}' y RA 'RA{$rec['id_ra']}'");

                continue;
            }

            $data[] = [
                'resultado_aprendizaje_id' => $resultadoId,
                'codigo' => trim($rec['id_criterio'] ?? ''),
                'descripcion' => $rec['definicion'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($data) {
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('criterios_evaluacion')->upsert(
                    $chunk,
                    ['resultado_aprendizaje', 'codigo'],
                    ['descripcion', 'updated_at']
                );
            }
        });
    }
}
