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
            "estrategia" => 1.40,
            "processos" => 1.50,
            "marketing" => 1.01,
            "vendas" => 1.00,
            "financas" => 1.30,
            "equipe" => 1.21,
            "mindset" => 1,20,
            "equilibrio" => 1.20
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
        $dataResposta = [];

        $data = $request->all();

        foreach ($data['scores'] as $area => $score) {
            $deficiencia[$area] = 10 - $score;
            $scoreAlavanca[$area] =  round($deficiencia[$area] * $impacto[$area] * $pesoEstrategico[$area], 1);

            $dataResposta[] = [
                'area' => $area,
                'nota' => $score,
                'score_alavanca' => $scoreAlavanca[$area]
            ];
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

        $explicacao = $this->explicarEscolha($dataResposta, $maiorIndice);

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
            $frasesMontadas['explicacao'] = $explicacao;
        }

        return $frasesMontadas;
        //return response()->json($frasesAgrupadas);
    }

    function explicarEscolha(array $dados, string $areaEscolhida): string
    {
        /*
        Estrutura esperada em $dados:

        [
            [
                'area' => 'Processos',
                'nota' => 4,
                'score_alavanca' => 18
            ],
            ...
        ]
        */

        $menorNota = PHP_FLOAT_MAX;
        $areasCriticas = [];

        // =========================
        // DESCOBRIR MENOR NOTA
        // =========================
        foreach ($dados as $linha) {

            if (is_numeric($linha['nota'])) {

                if ($linha['nota'] < $menorNota) {
                    $menorNota = $linha['nota'];
                }

            }
        }

        // =========================
        // IDENTIFICAR ÁREAS EMPATADAS
        // =========================
        foreach ($dados as $linha) {

            if ($linha['nota'] == $menorNota) {
                $areasCriticas[] = $linha['area'];
            }

        }

        // =========================
        // MONTAR TEXTO DAS ÁREAS
        // =========================
        $listaAreas = implode(', ', $areasCriticas);

        // =========================
        // VERIFICAR SE ESCOLHIDA
        // ESTÁ ENTRE AS CRÍTICAS
        // =========================
        $escolhidaEhCritica = in_array($areaEscolhida, $areasCriticas);

        // =========================
        // TEXTO FINAL
        // =========================
        $explicacao = '';

        // CASO 1:
        // ESCOLHIDA É UMA DAS MAIS CRÍTICAS
        if ($escolhidaEhCritica) {

            // EMPATE
            if (count($areasCriticas) > 1) {

                $explicacao =
                    "As áreas de {$listaAreas} apresentaram o mesmo nível de criticidade na análise inicial. ";

                $explicacao .=
                    "Nesse contexto, a área de {$areaEscolhida} foi priorizada por apresentar maior potencial de geração de impacto sistêmico e capacidade de destravar outras dimensões do negócio.";

            } else {

                // CRÍTICA ÚNICA
                $explicacao =
                    "A área de {$areaEscolhida} foi priorizada por apresentar simultaneamente o maior nível de criticidade e elevado potencial de impacto estrutural no negócio. ";

                $explicacao .=
                    "Sua evolução tende a reduzir gargalos relevantes e fortalecer a capacidade de crescimento sustentável.";

            }

        } else {

            // CASO 2:
            // OUTRA ÁREA ERA MAIS CRÍTICA
            $explicacao =
                "Embora a área de {$listaAreas} apresente maior criticidade na avaliação inicial, a área de {$areaEscolhida} foi priorizada por representar uma alavanca mais estratégica para geração de impacto sistêmico no curto e médio prazo. ";

            $explicacao .=
                "A análise indica que avanços nessa dimensão possuem maior capacidade de influenciar positivamente outras áreas e acelerar a evolução estrutural do negócio.";

        }

        return $explicacao;
    }

}
