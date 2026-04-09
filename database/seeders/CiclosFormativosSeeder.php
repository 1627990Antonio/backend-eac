<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        if (!file_exists($path)) {
            $this->command->error("CSV no encontrado: $path");
            return;
        }

        // Leer todas las líneas y parsear con str_getcsv
        $rows = array_map('str_getcsv', file($path));

        // El primer registro es la cabecera
        $header = array_map('trim', array_shift($rows));

        $data = [];
        foreach ($rows as $row) {
            // Ignorar filas vacías o mal formadas
            if (count($row) < count($header)) {
                continue;
            }

            $rec = array_combine($header, $row);

            $data[] = [
                'familia'=>trim($rec['familia'] ?? ''),
                'nivel'=>trim($rec['nivel'] ?? ''),
                'cod_ciclo'=>trim($rec['cod_ciclo'] ?? ''),
                'nombre'=>trim($rec['nombre'] ?? ''),

            ];
        }

        // Insertar/actualizar usando upsert para evitar duplicados por 'codigo'
        DB::transaction(function () use ($data) {
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('ciclos_formativos')->upsert(
                    $chunk,
                    ['familia', 'nivel', 'cod_ciclo', 'nombre']
                );
            }
        });
    }
}
