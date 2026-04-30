<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Frase;
use App\Models\Area;

class FrasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *  1 => 'Processos',
        2 => 'Estratégia',
        3 => 'Finanças',
        4 => 'Equipe',
        5 => 'Marketing',
        6 => 'Vendas',
        7 => 'Mindset',
        8 => 'Equilíbrio'
     */
    public function run(): void
    {
        $aberturas = [
            1 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
            3 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
            2 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
            5 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
            6 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
            4 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
            7 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
            8 => [
                1 => 'A análise integrada das dimensões do negócio revela um cenário com assimetrias relevantes.',
                2 => 'A leitura sistêmica dos dados evidencia potencial de crescimento condicionado por fragilidades.',
                3 => 'Os dados analisados indicam um negócio em evolução, no qual capacidades já desenvolvidas convivem com limitações que impedem o avanço consistente.',
            ],
        ];

        foreach ($aberturas as $areaId => $frases) {
            foreach ($frases as $ordem => $texto) {
                Frase::create([
                    'area_id' => $areaId,
                    'ordem' => $ordem,
                    'texto' => $texto,
                    'tipo' => 'aberturas',
                ]);
            }
        }

        $base = [
            1 => [
                1 => 'A análise indica que a principal limitação do negócio não está na capacidade de execução em si, mas na forma como essa execução está estruturada.',
                2 => 'A ausência de processos definidos e padronizados faz com que as atividades ocorram de maneira inconsistente, exigindo intervenção constante da liderança para garantir o funcionamento da operação.',
                3 => 'Esse cenário revela um modelo operacional frágil, com baixa previsibilidade, elevado retrabalho e dificuldade de replicação das rotinas.',
            ],
            3 => [
                1 => 'A análise evidencia que a estrutura financeira do negócio não acompanha o nível de atividade operacional, indicando fragilidade na gestão econômica.',
                2 => 'A ausência de controles consistentes sobre fluxo de caixa, custos e margens compromete a visibilidade sobre a real performance financeira.',
                3 => 'Esse cenário indica que decisões estratégicas podem estar sendo tomadas sem base em dados financeiros confiáveis, aumentando o risco do negócio.',
            ],
            2 => [
                1 => 'O diagnóstico aponta que o principal limitador do negócio está na ausência de um direcionamento estratégico claro e estruturado.',
                2 => 'Sem definição consistente de prioridades, posicionamento e objetivos, o negócio tende a operar de forma reativa, respondendo a demandas imediatas.',
                3 => 'Esse padrão compromete a eficiência das ações, gera dispersão de esforços e reduz a capacidade de crescimento consistente.',
            ],
            5 => [
                1 => 'A análise indica fragilidades na capacidade do negócio de gerar demanda de forma estruturada e previsível.',
                2 => 'A ausência de uma estratégia clara de marketing limita a atração de novos clientes e reduz a consistência no fluxo de oportunidades.',
                3 => 'Esse cenário tende a gerar crescimento instável, dependente de ações pontuais e pouco escaláveis.',
            ],
            6 => [
                1 => 'O diagnóstico evidencia limitações na conversão de oportunidades em receita, indicando fragilidade na estrutura comercial.',
                2 => 'A ausência de processos comerciais bem definidos compromete a eficiência do funil de vendas e reduz a taxa de conversão.',
                3 => 'Esse cenário limita diretamente o crescimento do negócio, mesmo quando há geração de demanda.',
            ],
            4 => [
                1 => 'A análise aponta fragilidades na estrutura e na gestão da equipe, impactando diretamente a execução operacional.',
                2 => 'A falta de clareza de papéis, capacitação e alinhamento compromete a produtividade e a consistência das entregas.',
                3 => 'Esse cenário tende a gerar sobrecarga na liderança e reduzir a eficiência global do negócio.',
            ],
            7 => [
                1 => 'O diagnóstico evidencia que a principal limitação está relacionada à mentalidade e à forma como decisões são tomadas no negócio.',
                2 => 'A presença de crenças limitantes, resistência a mudanças e baixa orientação estratégica compromete a evolução do negócio.',
                3 => 'Esse cenário reduz a capacidade de adaptação, inovação e execução consistente ao longo do tempo.',
            ],
            8 => [
                1 => 'A análise evidencia um desequilíbrio entre a vida pessoal e as demandas do negócio, impactando diretamente a atuação da empresária.',
                2 => 'A sobrecarga operacional e a dificuldade de estabelecer limites entre trabalho e vida pessoal tendem a comprometer a qualidade das decisões e a consistência da execução.',
                3 => 'Esse cenário indica risco de desgaste, perda de clareza estratégica e redução da capacidade de crescimento sustentável ao longo do tempo.',
            ],
        ];

        foreach ($base as $areaId => $frases) {
            foreach ($frases as $ordem => $texto) {
                Frase::create([
                    'area_id' => $areaId,
                    'ordem' => $ordem,
                    'texto' => $texto,
                    'tipo' => 'base',
                ]);
            }
        }

        $impacto = [
            1 => [
                1 => 'Fragilidades nos processos impactam diretamente a eficiência operacional, gerando retrabalho, inconsistência nas entregas e baixa previsibilidade.',
                2 => 'Esse cenário tende a sobrecarregar a liderança, que passa a atuar de forma operacional, reduzindo sua capacidade estratégica.',
                3 => 'Com o aumento da demanda, essas ineficiências se ampliam, comprometendo a escalabilidade do negócio.',
            ],
            2 => [
                1 => 'A ausência de direcionamento estratégico claro gera dispersão de esforços e utilização ineficiente dos recursos disponíveis.',
                2 => 'As decisões passam a ser reativas, reduzindo a consistência dos resultados ao longo do tempo.',
                3 => 'Mesmo iniciativas bem executadas tendem a ter impacto limitado devido à falta de alinhamento estratégico.',
            ],
            3 => [
                1 => 'Fragilidades na gestão financeira comprometem a sustentabilidade econômica do negócio, impactando diretamente sua capacidade de investimento e crescimento.',
                2 => 'A falta de controle financeiro aumenta o risco de decisões inadequadas, com possíveis efeitos sobre caixa, margem e rentabilidade.',
                3 => 'Esse cenário pode gerar instabilidade operacional e limitar a continuidade do negócio no médio prazo.',
            ],
            5 => [
                1 => 'Limitações em marketing impactam diretamente a geração de demanda, reduzindo a entrada de novas oportunidades no negócio.',
                2 => 'Esse cenário compromete a previsibilidade de crescimento e aumenta a dependência de ações pontuais ou indicações.',
                3 => 'A ausência de um fluxo consistente de leads tende a afetar toda a cadeia comercial.',
            ],
            6 => [
                1 => 'Fragilidades na área comercial impactam diretamente a conversão de oportunidades em receita, reduzindo a eficiência do funil de vendas.',
                2 => 'Esse cenário gera perda de oportunidades e limita o retorno sobre os esforços de marketing.',
                3 => 'A inconsistência nas vendas compromete a previsibilidade financeira e o crescimento do negócio.',
            ],
            4 => [
                1 => 'Limitações na equipe impactam diretamente a execução das atividades, reduzindo produtividade e qualidade das entregas.',
                2 => 'A falta de alinhamento e capacitação tende a gerar retrabalho e dependência excessiva da liderança.',
                3 => 'Esse cenário compromete a eficiência operacional e dificulta a expansão do negócio.',
            ],
            7 => [
                1 => 'Fragilidades no mindset impactam diretamente a capacidade de tomada de decisão, adaptação e evolução do negócio.',
                2 => 'Esse cenário tende a gerar resistência a mudanças, limitações na execução estratégica e dificuldade em sustentar crescimento ao longo do tempo.',
                3 => 'Como consequência, o negócio pode permanecer estagnado mesmo diante de oportunidades de evolução.',
            ],
            8 => [
                1 => 'O desequilíbrio entre vida pessoal e trabalho impacta diretamente a capacidade de liderança, tomada de decisão e visão estratégica do negócio.',
                2 => 'Com o aumento da sobrecarga, há tendência de decisões reativas, perda de foco e redução da eficiência nas demais áreas.',
                3 => 'No médio prazo, esse cenário pode comprometer não apenas o desempenho do negócio, mas também a continuidade e sustentabilidade da operação.',
            ],
        ];

        foreach ($impacto as $areaId => $frases) {
            foreach ($frases as $ordem => $texto) {
                Frase::create([
                    'area_id' => $areaId,
                    'ordem' => $ordem,
                    'texto' => $texto,
                    'tipo' => 'impacto',
                ]);
            }
        }

        $direcionamentos = [
            1 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
            2 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
            3 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
            5 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
            6 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
            4 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
            7 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
            8 => [
                1 => 'Diante desse cenário, o principal vetor de evolução do negócio está no fortalecimento dessa dimensão, criando base para crescimento estruturado.',
                2 => 'O avanço do negócio depende diretamente da correção dessa fragilidade estrutural.',
                3 => 'A priorização dessa área tende a gerar ganhos sistêmicos, impactando positivamente as demais dimensões.',
            ],
        ];

        foreach ($direcionamentos as $areaId => $frases) {
            foreach ($frases as $ordem => $texto) {
                Frase::create([
                    'area_id' => $areaId,
                    'ordem' => $ordem,
                    'texto' => $texto,
                    'tipo' => 'direcionamentos',
                ]);
            }
        }

        $conclusoes = [
            1 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
            3 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
            2 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
            5 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
            6 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
            4 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
            7 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
            8 => [
                1 => 'A análise indica que o crescimento sustentável está condicionado à organização interna do negócio.',
                2 => 'O negócio apresenta potencial relevante, porém sua evolução depende da consolidação de suas bases estruturais.',
            ],
        ];

        foreach ($conclusoes as $areaId => $frases) {
            foreach ($frases as $ordem => $texto) {
                Frase::create([
                    'area_id' => $areaId,
                    'ordem' => $ordem,
                    'texto' => $texto,
                    'tipo' => 'conclusoes',
                ]);
            }
        }
    }
}
