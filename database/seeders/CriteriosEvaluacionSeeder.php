<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        if (!file_exists($path)) {
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

            $data[] = [
                'cod_modulo' => trim($rec['cod_modulo'] ?? ''),
                'id_ra' => trim($rec['id_ra'] ?? ''),
                'id_criterio' => trim($rec['id_criterio'] ?? ''),
                'definicion' => trim($rec['definicion'] ?? ''),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($data) {
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('resultados_aprendizaje')->upsert(
                    $chunk,
                    ['cod_modulo', 'id_ra', 'id_criterio'],
                    ['definicion', 'updated_at']
                );
            }
        });
    }
}
