<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Frase;
use Illuminate\Http\Request;

class Controller
{
    public function responseSimulator(Request $request)
    {
        $impacto = [
            "estrategia" => 3,
            "processos" => 3,
            "marketing" => 1,
            "vendas" => 1,
            "financas" => 2,
            "equipe" => 2,
            "mindset" => 3,
            "equilibrio" => 2
        ];

        $pesoEstrategico = [
            "estrategia" => 1.4,
            "processos" => 1.5,
            "marketing" => 1,
            "vendas" => 1,
            "financas" => 1.3,
            "equipe" => 1.2,
            "mindset" => 1,2,
            "equilibrio" => 1.2
        ];

        $deficiencia = [];
        $scoreAlavanca = [];

        $data = $request->all();

        foreach ($data['scores'] as $area => $score) {
            $deficiencia[$area] = 10 - $score;
            $scoreAlavanca[$area] =  round($deficiencia[$area] * $impacto[$area] * $pesoEstrategico[$area], 1);
        }

        $maiorValor = max($scoreAlavanca);
        $maiorIndice = array_search($maiorValor, $scoreAlavanca);
        $areaId = Area::whereRaw('LOWER(nome) = ?', [strtolower($maiorIndice)])->first();

        $frasesMontadas = [];

        if ($areaId) {
            $frases = Frase::where('area_id', $areaId->id)->get();
            $frasesAgrupadas = $frases->groupBy('tipo');

            foreach ($frasesAgrupadas as $tipo => $frases) {
                // frases tipo Base, impacto e direcionamento, vai mostrar todas

                if ($tipo == 'base' || $tipo == 'impacto' || $tipo == 'direcionamentos') {
                    $frasesMontadas[$tipo] = $frases;
                    continue;
                }

                $frasesMontadas[$tipo] = $frases->random();
            }
        }



        return $frasesMontadas;
        //return response()->json($frasesAgrupadas);
    }
}
