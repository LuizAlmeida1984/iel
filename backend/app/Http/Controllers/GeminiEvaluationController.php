<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Throwable;
use GuzzleHttp\Client;


class GeminiEvaluationController extends Controller
{
    /**
     * @var array<string, string>
     */
    private array $categories = [
        'estrategia' => 'Estratégia e visão',
        'processos' => 'Processos e operação',
        'marketing' => 'Marketing e posicionamento',
        'vendas' => 'Vendas e faturamento',
        'financas' => 'Finanças e lucratividade',
        'equipe' => 'Liderança e equipe',
        'mindset' => 'Mindset e resiliência',
        'equilibrio' => 'Equilíbrio vida-trabalho',
    ];

    /**
     * @var string[]
     */
    private array $models = [
        'gemini-2.5-flash',
    ];

    public function __invoke(Request $request)
    {
        $payload = $request->validate([
            'mentee_name' => ['required', 'string'],
            'scores' => ['required', 'array'],
            'scores.*' => ['required', 'numeric', 'between:1,10'],
        ]);

        if (array_diff(array_keys($this->categories), array_keys($payload['scores']))) {
            return response()->json([
                'message' => 'As pontuações enviadas não contêm todas as áreas esperadas.',
            ], 422);
        }

        $apiKey = env('GEMINI_API_KEY', '');

        $systemInstruction = $this->buildSystemInstruction();
        $prompt = $this->buildPrompt($payload['mentee_name'], $payload['scores']);

        #dd($prompt, $apiKey);

        if ($apiKey === '') {
            return response()->json([
                'message' => 'Gemini não configurado no backend. Defina GEMINI_API_KEY no .env do Laravel.',
            ], 500);
        }

        if ($systemInstruction === '') {
            return response()->json([
                'message' => 'Rubrica não encontrada no backend.',
            ], 500);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent";
        $data = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $prompt
                        ]
                    ]
                ]
            ]
        ];

        $client = new Client(['verify' => false, 'timeout' => 60, 'connect_timeout' => 15  ]);

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $apiKey,
            ],
            'json' => $data
        ]);

        #echo $response->getBody();

        $data = json_decode($response->getBody(), true);
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        //dd($text);

        if (!$text) {
            return response()->json(['error' => 'Resposta inválida da API de IA.'], 502);
        }
        return $text;
        $responseArray = json_decode($text, true);

        #dd($responseArray);

        $responseArray["comentarios_pilares"][] = ["key"=>"Estratégia e visão", "value"=>$responseArray["comentarios_pilares"]["Estratégia e visão"]];
        unset($responseArray["comentarios_pilares"]["Estratégia e visão"]);

        $responseArray["comentarios_pilares"][] = ["key"=>"Processos e operação", "value"=>$responseArray["comentarios_pilares"]["Processos e operação"]];
        unset($responseArray["comentarios_pilares"]["Processos e operação"]);

        $responseArray["comentarios_pilares"][] = ["key"=>"Marketing e posicionamento", "value"=>$responseArray["comentarios_pilares"]["Marketing e posicionamento"]];
        unset($responseArray["comentarios_pilares"]["Marketing e posicionamento"]);

        $responseArray["comentarios_pilares"][] = ["key"=>"Vendas e faturamento", "value"=>$responseArray["comentarios_pilares"]["Vendas e faturamento"]];
        unset($responseArray["comentarios_pilares"]["Vendas e faturamento"]);

        $responseArray["comentarios_pilares"][] = ["key"=>"Finanças e lucratividade", "value"=>$responseArray["comentarios_pilares"]["Finanças e lucratividade"]];
        unset($responseArray["comentarios_pilares"]["Finanças e lucratividade"]);

        $responseArray["comentarios_pilares"][] = ["key"=>"Liderança e equipe", "value"=>$responseArray["comentarios_pilares"]["Liderança e equipe"]];
        unset($responseArray["comentarios_pilares"]["Liderança e equipe"]);

        $responseArray["comentarios_pilares"][] = ["key"=>"Mindset e resiliência", "value"=>$responseArray["comentarios_pilares"]["Mindset e resiliência"]];
        unset($responseArray["comentarios_pilares"]["Mindset e resiliência"]);

        $responseArray["comentarios_pilares"][] = ["key"=>"Equilíbrio vida-trabalho", "value"=>$responseArray["comentarios_pilares"]["Equilíbrio vida-trabalho"]];
        unset($responseArray["comentarios_pilares"]["Equilíbrio vida-trabalho"]);

        if(isset($responseArray["plano_smart"]["alcancavel"])) {
            $responseArray["plano_smart"][] = ["key"=>"alcancavel", "value"=>$responseArray["plano_smart"]["alcancavel"]];
            unset($responseArray["plano_smart"]["alcancavel"]);
        }

        if(isset($responseArray["plano_smart"]["especifico"])) {
            $responseArray["plano_smart"][] = ["key"=>"especifico", "value"=>$responseArray["plano_smart"]["especifico"]];
            unset($responseArray["plano_smart"]["especifico"]);
        }

        if(isset($responseArray["plano_smart"]["mensuravel"])) {
            $responseArray["plano_smart"][] = ["key"=>"mensuravel", "value"=>$responseArray["plano_smart"]["mensuravel"]];
            unset($responseArray["plano_smart"]["mensuravel"]);
        }

        if(isset($responseArray["plano_smart"]["prazo"])) {
            $responseArray["plano_smart"][] = ["key"=>"prazo", "value"=>$responseArray["plano_smart"]["prazo"]];
            unset($responseArray["plano_smart"]["prazo"]);
        }

        if(isset($responseArray["plano_smart"]["relevante"])) {
            $responseArray["plano_smart"][] = ["key"=>"relevante", "value"=>$responseArray["plano_smart"]["relevante"]];
            unset($responseArray["plano_smart"]["relevante"]);
        }

        #dd($responseArray);
        #dd($text);
        #dd(json_encode($responseArray)  );

        #dd(response()->json(['analysis' => $text]));
        return response()->json(['analysis' => $responseArray]);
    }

    public function __invokeGrok(Request $request): JsonResponse
    {

        #dd($request);

        $payload = $request->validate([
            'mentee_name' => ['required', 'string'],
            'scores' => ['required', 'array'],
            'scores.*' => ['required', 'numeric', 'between:1,10'],
        ]);

        if (array_diff(array_keys($this->categories), array_keys($payload['scores']))) {
            return response()->json([
                'message' => 'As pontuações enviadas não contêm todas as áreas esperadas.',
            ], 422);
        }

        $apiKey = trim((string) env('GROQ_API_KEY', ''));
        $systemInstruction = $this->buildSystemInstruction();
        $prompt = $this->buildPrompt($payload['mentee_name'], $payload['scores']);

        #dd( $prompt );

        if ($apiKey === '') {
            return response()->json([
                'message' => 'Groq não configurado no backend. Defina GROQ_API_KEY no .env do Laravel.',
            ], 500);
        }

        if ($systemInstruction === '') {
            return response()->json([
                'message' => 'Rubrica não encontrada no backend.',
            ], 500);
        }

        $lastError = 'Sem detalhes';

        $requestBuilder = Http::withOptions([
            'timeout' => 60,
            'connect_timeout' => 15,
        ])->acceptJson()->withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ]);

        // modelos comuns do Groq (você pode ajustar)
        $models = [
            'llama-3.1-8b-instant',
        ];

        foreach ($models as $model) {
            try {
                $response = $requestBuilder->post(
                    "https://api.groq.com/openai/v1/chat/completions",
                    [
                        'model' => $model,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $systemInstruction,
                            ],
                            [
                                'role' => 'user',
                                'content' => $prompt,
                            ],
                        ],
                        'temperature' => 0.2,
                    ]
                );
            } catch (Throwable $exception) {
                return response()->json([
                    'message' => 'Falha de conexão do backend com o Groq.',
                    'details' => $exception->getMessage(),
                ], 502);
            }

            if (! $response->successful()) {
                $lastError = sprintf('[%s] %s', $response->status(), $response->body());

                if (! in_array($response->status(), [404, 429, 503], true)) {
                    return response()->json([
                        'message' => "Falha Groq ({$model}).",
                        'details' => $response->json() ?: $response->body(),
                    ], $response->status());
                }

                continue;
            }

            $responseData = $response->json();

            $analysis = data_get($responseData, 'choices.0.message.content');

            if (!is_string($analysis) || trim($analysis) === '') {
                return response()->json([
                    'message' => 'Groq respondeu sem conteúdo de análise.',
                    'details' => $responseData,
                ], 502);
            }

            /**
             * 1. Remove markdown code block
             */
            $clean = preg_replace('/```json|```/', '', $analysis);

            /**
             * 2. Remove BOM e caracteres invisíveis problemáticos
             */
            $clean = trim($clean);
            $clean = preg_replace('/^\xEF\xBB\xBF/', '', $clean); // BOM UTF-8

            /**
             * 3. Tenta garantir UTF-8 válido
             */
            $clean = mb_convert_encoding($clean, 'UTF-8', 'UTF-8');

            /**
             * 4. Decode com flags mais permissivas
             */
            $decoded = json_decode($clean, true, 512, JSON_INVALID_UTF8_IGNORE);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'message' => 'Groq retornou JSON inválido.',
                    'error' => json_last_error_msg(),
                    'raw' => $analysis,
                    'clean' => $clean,
                ], 502);
            }

            return response()->json([
                'analysis' => $decoded,
            ]);
        }

        return response()->json([
            'message' => 'Groq indisponível após fallback de modelos.',
            'details' => $lastError,
        ], 502);
    }

    /* public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'mentee_name' => ['required', 'string'],
            'scores' => ['required', 'array'],
            'scores.*' => ['required', 'numeric', 'between:1,10'],
        ]);

        if (array_diff(array_keys($this->categories), array_keys($payload['scores']))) {
            return response()->json([
                'message' => 'As pontuações enviadas não contêm todas as áreas esperadas.',
            ], 422);
        }

        $apiKey = trim((string) env('GROQ_API_KEY', ''));

        if ($apiKey === '') {
            return response()->json([
                'message' => 'Groq não configurado no backend. Defina GROQ_API_KEY no .env do Laravel.',
            ], 500);
        }

        $systemInstruction = $this->buildSystemInstruction();
        $prompt = $this->buildPrompt($payload['mentee_name'], $payload['scores']);

        if ($systemInstruction === '') {
            return response()->json([
                'message' => 'Rubrica não encontrada no backend.',
            ], 500);
        }

        $lastError = 'Sem detalhes';

        $requestBuilder = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(60)
            ->connectTimeout(15);

        foreach ($this->modelsGroq as $model) {
            try {
                $response = $requestBuilder->post(
                    "https://api.groq.com/openai/v1/chat/completions",
                    [
                        'model' => $model,
                        'temperature' => 0.2,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $systemInstruction,
                            ],
                            [
                                'role' => 'user',
                                'content' => $prompt,
                            ],
                        ],
                    ]
                );
            } catch (Throwable $exception) {
                return response()->json([
                    'message' => 'Falha de conexão do backend com a Groq.',
                    'details' => $exception->getMessage(),
                ], 502);
            }

            if (! $response->successful()) {
                $lastError = sprintf('[%s] %s', $response->status(), $response->body());

                if (! in_array($response->status(), [404, 429, 503], true)) {
                    return response()->json([
                        'message' => "Falha Groq ({$model}).",
                        'details' => $response->json() ?: $response->body(),
                    ], $response->status());
                }

                continue;
            }

            $responseData = $response->json();

            $analysis = data_get($responseData, 'choices.0.message.content');

            if (! is_string($analysis) || trim($analysis) === '') {
                return response()->json([
                    'message' => 'Groq respondeu sem conteúdo de análise.',
                    'details' => $responseData,
                ], 502);
            }

            //json_decode($analysis, true);

            //if (json_last_error() !== JSON_ERROR_NONE) {
            //    return response()->json([
            //        'message' => 'Groq não retornou JSON válido.',
            //        'analysis' => $analysis,
            //    ], 502);
            //}

            $clean = preg_replace('/```json|```/', '', $analysis);
            $clean = trim($clean);

            // captura todos os objetos JSON separados
            preg_match_all('/\{(?:[^{}]|(?R))*\}/s', $clean, $matches);

            if (empty($matches[0])) {
                return response()->json([
                    'message' => 'Groq não retornou JSON válido.',
                    'analysis' => $analysis,
                ], 502);
            }

            $data = [];

            foreach ($matches[0] as $json) {
                $decoded = json_decode($json, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[] = $decoded;
                }
            }

            if (empty($data)) {
                return response()->json([
                    'message' => 'Nenhum JSON válido foi extraído.',
                    'analysis' => $analysis,
                ], 502);
            }

            return response()->json([
                'analysis' => $data,
            ]);
        }

        return response()->json([
            'message' => 'Groq indisponível após fallback de modelos.',
            'details' => $lastError,
        ], 502);
    } */

    private function buildSystemInstruction(): string
    {
        $rubricPath = resource_path('prompts/rubrica-avaliacao.md');

        if (! File::exists($rubricPath)) {
            return '';
        }

        $rubricText = trim((string) File::get($rubricPath));

        if ($rubricText === '') {
            return '';
        }

        return implode("\n", [
            'Você é uma avaliadora estratégica do programa de mentoria para mulheres do IEL.',
            'Use exclusivamente a rubrica abaixo para interpretar as notas informadas.',
            'Não invente critérios, escalas, pesos, áreas ou regras fora da rubrica.',
            'Responda apenas com JSON válido.',
            'Não use Markdown.',
            'Não escreva texto antes ou depois do JSON.',
            '',
            'Rubrica de avaliação:',
            $rubricText,
        ]);
    }

    /**
     * @param array<string, int|float|string> $scores
     */
    private function buildPrompt(string $menteeName, array $scores): string
    {
        $normalizedScores = [];

        foreach ($this->categories as $key => $label) {
            $normalizedScores[$label] = (float) ($scores[$key] ?? 0);
        }

        /* return implode("\n", [
            'Mentorada: ' . trim($menteeName),
            'Tarefa: interpretar as notas da Roda da Vida com base exclusivamente na rubrica carregada.',
            'Scores informados: ' . json_encode($normalizedScores, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'Objetivo:',
            // '- realizar uma anamnese detalhada, levantando possíveis causas, histórico e contexto para os resultados apresentados',
            '- comentar individualmente cada pilar, destacando pontos fortes e pontos de atenção',
            '- identificar a principal Area Alavanca',
            '- explicar o impacto sistêmico da área prioritária',
            '- sugerir oportunidades de promoção e desenvolvimento',
            '- sugerir de 1 a 3 ações práticas imediatas, com dicas concretas e exemplos aplicáveis à realidade da mentorada',
            '- indicar recursos, cursos gratuitos, redes de apoio, comunidades e eventos voltados para mulheres empreendedoras',
            '- sugerir temas para próximas sessões de mentoria e perguntas de reflexão',
            '- trazer uma história inspiradora de mulher empreendedora que superou desafios semelhantes',
            '- gerar um checklist prático de próximos passos',
            '- incluir alertas de autoconfiança, bem-estar e valorização das conquistas',
            '- sugerir ações de networking e grupos/setores para conexão',
            '- destacar as áreas em que a mentorada já se destaca, reforçando autoestima e potencial',
            '- propor um plano de ação SMART (específico, mensurável, alcançável, relevante e com prazo)',
            'Regras obrigatórias:',
            '- usar exclusivamente a rubrica recebida no systemInstruction para análise dos pilares',
            '- para dicas, sugestões, recursos e histórias, pode buscar inspiração livremente no conhecimento do Gemini, sempre considerando o contexto da mentorada',
            '- não inventar critérios ou áreas fora da rubrica para a análise principal',
            '- responder apenas com JSON válido, estruturando cada item em campos separados',
            '- não usar Markdown',
            '- não escrever texto antes ou depois do JSON',
            // '- retornar obrigatóriamente o JSON com as chaves: anamnese, comentarios_pilares, area_alavanca, impacto_sistemico, oportunidades, acoes, recursos, temas_proximas_sessoes, perguntas_reflexao, historia_inspiradora, checklist, alertas_bem_estar, networking, pontos_fortes, plano_smart',
            '- retornar obrigatóriamente o JSON com as chaves: comentarios_pilares, area_alavanca, impacto_sistemico, oportunidades, acoes, recursos, temas_proximas_sessoes, perguntas_reflexao, historia_inspiradora, checklist, alertas_bem_estar, networking, pontos_fortes, plano_smart',
            'Return ONLY valid JSON',
            'Ensure:',
            '- All keys are properly comma-separated',
            '- No trailing commas',
            '- JSON must be parseable by json_decode',
            '- Do not stop mid-JSON',
            '- Do not include markdown or explanations',
        ]); */

        return implode("\n", [
            'Mentorada: ' . trim($menteeName),
            'Tarefa: interpretar as notas da Roda da Vida com base exclusivamente na rubrica carregada.',
            'Scores informados: ' . json_encode($normalizedScores, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),

            'Objetivos:',
            '- comentar individualmente cada pilar',
            '- identificar a principal Área Alavanca',
            '- explicar o impacto sistêmico da área prioritária',
            '- gerar diagnóstico estratégico profundo',
            '- sugerir ações práticas e aplicáveis',
            '- propor direcionamentos de crescimento',
            '- destacar pontos fortes',
            '- gerar conclusão executiva inspiradora',

            'Regras obrigatórias:',
            '- usar exclusivamente a rubrica recebida no systemInstruction para análise dos pilares',
            '- não inventar critérios ou áreas fora da rubrica',
            '- manter linguagem executiva, estratégica, acolhedora e analítica',
            '- escrever como uma consultora de negócios experiente',
            '- personalizar a análise conforme os scores recebidos',
            '- evitar respostas genéricas ou superficiais',
            '- responder em texto corrido estruturado',
            '- usar exatamente os títulos e estrutura do exemplo abaixo',
            '- não usar markdown JSON',
            '- não explicar o que está fazendo',
            '- não escrever observações fora da estrutura',
            '- responder apenas com o relatório final',

            'EXEMPLO DE ESTRUTURA E TOM ESPERADO:',

            <<<'EXEMPLO'
            1. ABERTURA ESTRATÉGICA

            Observando o panorama do seu negócio, é evidente que estamos diante de uma operação com maturidade e fundamentos muito acima da média do mercado. Com pilares como Processos, Estratégia e Finanças operando em níveis de excelência e estabilidade, você construiu uma máquina com alta previsibilidade e segurança.

            O cenário atual não é de caos ou sobrevivência, mas sim de um negócio estruturado que atingiu um teto de crescimento e agora pede uma nova configuração para escalar.

            2. ÁREA ALAVANCA PRIORITÁRIA

            A área alavanca prioritária para o momento atual do negócio é Liderança e Equipe.

            Ainda que outras áreas apresentem pontuação semelhante, essa dimensão foi priorizada por representar a engrenagem central que conecta estratégia, operação e execução prática. A evolução desta área possui o maior potencial de impacto sistêmico.

            3. DIAGNÓSTICO

            A análise aponta fragilidades operacionais e desafios de execução que limitam o crescimento sustentável da empresa.

            Apesar da existência de processos estruturados e clareza estratégica, a operação ainda depende excessivamente da liderança principal para funcionar adequadamente. Isso gera sobrecarga, reduz velocidade de crescimento e impede ganhos reais de escala.

            4. IMPACTO SISTÊMICO

            A limitação nessa área cria efeitos indiretos em praticamente todos os demais pilares do negócio.

            A dificuldade de delegação compromete produtividade, crescimento comercial, inovação e equilíbrio da liderança. Além disso, a ausência de autonomia operacional reduz a capacidade de expansão segura da empresa.

            5. DIRECIONAMENTO ESTRATÉGICO

            O próximo nível de crescimento depende menos de criar novas estratégias e mais de fortalecer a capacidade de execução da operação.

            O foco agora deve ser transformar conhecimento em cultura, fortalecer autonomia da equipe e estruturar uma operação menos dependente da liderança central.

            6. AÇÕES PRÁTICAS

            - Definir responsáveis claros para cada processo estratégico
            - Implementar reuniões semanais de alinhamento operacional
            - Criar trilhas simples de treinamento interno
            - Delegar progressivamente atividades críticas
            - Desenvolver indicadores básicos de acompanhamento da equipe

            7. CONCLUSÃO EXECUTIVA

            O negócio demonstra excelente potencial de crescimento e possui bases sólidas para expansão.

            O principal desafio agora não está na estratégia, mas na capacidade operacional de sustentar o próximo ciclo de crescimento com autonomia, clareza e consistência.
            EXEMPLO,

                'IMPORTANTE:',
                '- seguir exatamente esse nível de profundidade e qualidade',
                '- adaptar completamente o conteúdo aos scores recebidos',
                '- evitar repetir frases do exemplo literalmente',
                '- manter naturalidade e personalização',
        ]);
    }
}
