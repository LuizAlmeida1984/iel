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

        $mapAreas = [
            'estrategia' => 'Estratégia',
            'processos' => 'Processos',
            'marketing' => 'Marketing',
            'vendas' => 'Vendas',
            'financas' => 'Finanças',
            'equipe' => 'Equipe',
            'mindset' => 'Mindset',
            'equilibrio' => 'Equilíbrio',
        ];

        $mapLabels = [
            'estrategia' => 'Estratégia e visão',
            'processos' => 'Processos e operação',
            'marketing' => 'Marketing e posicionamento',
            'vendas' => 'Vendas e faturamento',
            'financas' => 'Finanças e lucratividade',
            'equipe' => 'Liderança e equipe',
            'mindset' => 'Mindset e resiliência',
            'equilibrio' => 'Equilíbrio vida-trabalho',
        ];


        $deficiencia = [];
        $scoreAlavanca = [];
        $frasesMontadas = [];

        $data = $request->all();

        foreach ($data['scores'] as $area => $score) {
            $deficiencia[$area] = 10 - $score;
            $scoreAlavanca[$area] =  round($deficiencia[$area] * $impacto[$area] * $pesoEstrategico[$area], 1);

        }

        $frasesMontadas['scoreAlavancaMedia'] = $scoreAlavanca;

        $menorValor = min($data['scores']);
        $menorIndice = array_search($menorValor, $data['scores']);
        $nomeAreaMenor = $mapAreas[$menorIndice] ?? null;
        $areaIdMenor = Area::where('nome', $nomeAreaMenor)->first();

        $maiorValor = max($scoreAlavanca);
        $maiorIndice = array_search($maiorValor, $scoreAlavanca);
        $nomeAreaMaior = $mapAreas[$maiorIndice] ?? null;
        $areaId = Area::where('nome', $nomeAreaMaior)->first();

        if ($areaId) {
            $frases = Frase::where('area_id', $areaId->id)->get();
            $frasesAgrupadas = $frases->groupBy('tipo');

            foreach ($frasesAgrupadas as $tipo => $frases) {
                // frases tipo Base, impacto e direcionamento, vai mostrar todas

                if ($tipo == 'base' || $tipo == 'impacto' || $tipo == 'direcionamentos') {
                    $frasesMontadas['frases'][$tipo] = $frases;
                    continue;
                }

                $frasesMontadas['frases'][$tipo] = $frases->random();
            }

            //$frasesMontadas['scoreAlavancaArea'] = $areaId;
            //$frasesMontadas['menorNotaArea'] = $areaIdMenor;
            $frasesMontadas['scoreAlavancaArea'] = $mapLabels[$maiorIndice];
            $frasesMontadas['menorNotaArea'] = $mapLabels[$menorIndice];
            $frasesMontadas['menorNota'] = $menorValor;
            $frasesMontadas['tabelaScoreAlavanca'] = $menorValor;
        }

        return $frasesMontadas;
        //return response()->json($frasesAgrupadas);
    }
}
