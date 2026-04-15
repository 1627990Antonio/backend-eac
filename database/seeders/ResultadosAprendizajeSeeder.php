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
                'cod_modulo' => trim($rec['cod_modulo'] ?? ''),
                'descripcion' => trim($rec['definicion'] ?? ''),
                'codigo' => trim($rec['tipo'] ?? ''),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($data) {
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('resultados_aprendizaje')->upsert(
                    $chunk,
                    ['cod_modulo', 'id_ra'],
                    ['definicion', 'tipo']
                );
            }
        });
    }
}
