<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultadosAprendizajeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/csv/resultados_aprendizaje.csv');

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
                'modulo_id' => DB::table('modulos')->where('codigo', trim($rec['cod_modulo'] ?? ''))->value('id'),
                'codigo' => "RA" . trim($rec['id_ra'] ?? ''),
                'descripcion' => $rec['definicion'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($data) {
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('resultados_aprendizaje')->upsert(
                    $chunk,
                    ['modulo_id', 'codigo'],
                    ['descripcion', 'updated_at']
                );
            }
        });
    }
}
