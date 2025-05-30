<?php

require_once __DIR__ . '/../models/Treino.class.php';

/**
 * Serviço responsável por operações relacionadas aos treinos
 * Centraliza a lógica de negócio para criação e gestão de treinos
 */
class TreinoService
{
    private $treino;

    public function __construct()
    {
        $this->treino = new Treino();
    }

    /**
     * Obtém todos os dados necessários para criação de treino
     *
     * @return array
     * @throws Exception
     */
    public function getDadosParaCriacaoTreino(): array
    {
        $grupo_muscular = $this->treino->getGrupo_muscular();
        $exercicios = $this->treino->getExercicios();

        if (!is_array($grupo_muscular) || !is_array($exercicios)) {
            throw new Exception("Erro ao carregar dados de exercícios e grupos musculares.");
        }

        return [
            'grupos_musculares' => $grupo_muscular,
            'exercicios' => $exercicios
        ];
    }

    /**
     * Organiza exercícios por grupo muscular para facilitar a busca
     *
     * @return array
     */
    public function getExerciciosPorGrupo(): array
    {
        $exercicios = $this->treino->getExercicios();
        $exerciciosPorGrupo = [];

        foreach ($exercicios as $exercicio) {
            $grupo = $exercicio['grupo_muscular'];
            if (!isset($exerciciosPorGrupo[$grupo])) {
                $exerciciosPorGrupo[$grupo] = [];
            }
            $exerciciosPorGrupo[$grupo][] = $exercicio;
        }

        return $exerciciosPorGrupo;
    }

    /**
     * Valida se os dados do formulário de treino estão corretos
     *
     * @param array $dados
     * @return array ['valido' => bool, 'erros' => array]
     */
    public function validarDadosTreino(array $dados): array
    {
        $erros = [];

        // Validar se há pelo menos um treino
        if (empty($dados) || !is_array($dados)) {
            $erros[] = "Pelo menos um treino deve ser adicionado.";
            return ['valido' => false, 'erros' => $erros];
        }

        // Validar cada treino
        foreach ($dados as $letra => $treino) {
            if (!is_array($treino) || empty($treino)) {
                $erros[] = "Treino $letra deve ter pelo menos um exercício.";
                continue;
            }

            // Validar cada exercício do treino
            foreach ($treino as $numExercicio => $exercicio) {
                $this->validarExercicio($exercicio, $letra, $numExercicio, $erros);
            }
        }

        return [
            'valido' => empty($erros),
            'erros' => $erros
        ];
    }

    /**
     * Valida um exercício específico
     *
     * @param array $exercicio
     * @param string $letra
     * @param int $numExercicio
     * @param array &$erros
     */
    private function validarExercicio(array $exercicio, string $letra, int $numExercicio, array &$erros): void
    {
        $campos = [
            'grupo_muscular' => 'Grupo muscular',
            'nome_exercicio' => 'Nome do exercício',
            'series_exercicio' => 'Séries',
            'repeticoes_exercicio' => 'Repetições'
        ];

        foreach ($campos as $campo => $label) {
            if (empty($exercicio[$campo])) {
                $erros[] = "Treino $letra, Exercício $numExercicio: $label é obrigatório.";
            }
        }

        // Validações específicas
        if (!empty($exercicio['series_exercicio']) && !is_numeric($exercicio['series_exercicio'])) {
            $erros[] = "Treino $letra, Exercício $numExercicio: Séries deve ser um número.";
        }

        if (!empty($exercicio['series_exercicio']) && (int)$exercicio['series_exercicio'] <= 0) {
            $erros[] = "Treino $letra, Exercício $numExercicio: Séries deve ser maior que zero.";
        }
    }

    /**
     * Formata os dados do treino para processamento
     *
     * @param array $dados
     * @param int $idSolicitacao
     * @param int $idAluno
     * @return array
     */
    public function formatarDadosParaProcessamento(array $dados, int $idSolicitacao, int $idAluno): array
    {
        $dadosFormatados = [
            'id_solicitacao' => $idSolicitacao,
            'id_aluno' => $idAluno,
            'treinos' => []
        ];

        foreach ($dados as $letra => $treino) {
            $dadosFormatados['treinos'][$letra] = [
                'exercicios' => [],
                'observacoes' => $treino['observacoes'] ?? ''
            ];

            foreach ($treino as $numExercicio => $exercicio) {
                if ($numExercicio !== 'observacoes') {
                    $dadosFormatados['treinos'][$letra]['exercicios'][] = [
                        'numero' => $exercicio['num_exercicio'],
                        'grupo_muscular' => $exercicio['grupo_muscular'],
                        'id_exercicio' => $exercicio['nome_exercicio'],
                        'series' => (int)$exercicio['series_exercicio'],
                        'repeticoes' => $exercicio['repeticoes_exercicio']
                    ];
                }
            }
        }

        return $dadosFormatados;
    }
}
