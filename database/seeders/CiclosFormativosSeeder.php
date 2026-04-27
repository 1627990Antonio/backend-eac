<?php

namespace Database\Seeders;

use App\Models\FamiliaProfesional;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CiclosFormativosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $path = database_path('seeders/csv/ciclos.csv');

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

            $data[] = [
                'familia_profesional_id' => FamiliaProfesional::where('codigo', trim($rec['familia'] ?? ''))->first()->id,
                'nombre' => trim($rec['nombre'] ?? ''),
                'codigo' => trim($rec['cod_ciclo'] ?? ''),
                'grado' => trim($rec['nivel'] ?? ''),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($data) {
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('ciclos_formativos')->upsert(
                    $chunk,
                    ['codigo'],
                    ['grado', 'nombre', 'updated_at']
                );
            }
        });
    }
}
