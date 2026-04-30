<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            1 => 'Processos',
            2 => 'Estratégia',
            3 => 'Finanças',
            4 => 'Equipe',
            5 => 'Marketing',
            6 => 'Vendas',
            7 => 'Mindset',
            8 => 'Equilíbrio'
        ];

        foreach ($areas as $key => $nome) {
            Area::create([
                'id' => $key,
                'nome' => $nome
            ]);
        }
    }
}
