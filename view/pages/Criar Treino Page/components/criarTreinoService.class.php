<?php

require_once __DIR__ . '/../models//Usuarios.class.php';
require_once __DIR__ . '/../models//SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models//Treino.class.php';


class criarTreinoService
{
    private $userModel;
    private $formularioTreinoModel;
    private $treinoModel;

    public function __construct()
    {
        $this->userModel = new Users();
        $this->formularioTreinoModel = new SolicitacaoTreino();
        $this->treinoModel = new Treino();
    }

    public function getMetricasFormulario($id_aluno): array
    {
        $this->validarAluno($id_aluno);

        try {


            $formulario = $this->formularioTreinoModel->getFormularioForCriacaoDeTreino($id_aluno);
            if (!$formulario) {
                return [
                    'error' => 'Nenhum formulário encontrado para o aluno.'
                ];
            }
            $nome_aluno = $this->getNomeAluno($id_aluno);
            return [
                'id' => $formulario['id'],
                'nome_aluno' => $nome_aluno,
                'quantidade_treinos' => (int)$formulario['treinos'] ?? 0,
                'experiencia' => $formulario['experiencia'] ?? '',
                'objetivo' => $formulario['objetivo'] ?? '',
                'peso' => (float)$formulario['peso'] ?? 0.0,
                'altura' => (float)$formulario['altura'] ?? 0.0,
                'sexo' => $formulario['sexo'] ?? '',
            ];
        } catch (Exception $e) {

            error_log("Erro en getMetricasFormulario: " . $e->getMessage());
            return [
                'error' => 'Erro ao buscar métricas do formulário: ' . $e->getMessage(),
                'codigo' => 500,
                'sucesso' => false
            ];
        }
    }

    public function getNomeAluno($id_aluno): string
    {
        try {
            $nome_aluno = $this->userModel->getNomeAluno($id_aluno);
            if (!$nome_aluno || isset($nome_aluno['username']) === false) {
                return 'Nome não encontrado';
            }
            return trim($nome_aluno['username']) ?? 'nome não encontrado';
        } catch (Exception $e) {
            error_log("Erro ao buscar nome do aluno: " . $e->getMessage());
            return 'Erro ao buscar nome do aluno';
        }
    }
    public function getExerciciosPorGrupo(): array
    {
        $exercicios = $this->treinoModel->getExercicios();
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

    public function getGruposMusculares(): array
    {
        try {
            $gruposMusculares = array_keys(array_unique($this->getExerciciosPorGrupo()));
            sort($gruposMusculares); // Ordena os grupos musculares
            return $gruposMusculares;
        } catch (Exception $e) {
            error_log("Erro ao buscar grupos musculares: " . $e->getMessage());
            return [];
        }
    }



    private function validarAluno($id_aluno): void
    {
        if (empty($id_aluno) || !is_numeric($id_aluno)) {
            error_log("ID do aluno inválido: " . $id_aluno);
            throw new InvalidArgumentException("ID do aluno deve ser um número válido.");
        }
    }

    public function criarTreino($dados)
    {
        if (empty($dados['id_aluno']) || !is_numeric($dados['id_aluno'])) {
            throw new InvalidArgumentException("ID do aluno inválido.");
        }

        $id_aluno = $dados['id_aluno'];
        $id_instrutor = $_SESSION['usuario']['id'] ?? null;

        if (!$id_instrutor) {
            throw new Exception("ID do instrutor não encontrado na sessão.");
        }

        $data_criacao = date('Y-m-d H:i:s');
        $id_treino_criado = $_SESSION['id_treino_criado'] ?? 0;
        $_SESSION['id_treino_criado'] = ++$id_treino_criado;

        
        foreach ($dados['exercicios'] as $letra => $exercicios) {
            foreach ($exercicios as $exercicio) {
                $this->treinoModel->cadastrarTreino(
                    $id_instrutor,
                    $id_aluno,
                    $id_treino_criado,
                    $letra,
                    $exercicio['num_exercicio'],
                    $exercicio['nome_exercicio'],
                    $exercicio['series_exercicio'],
                    $exercicio['repeticoes_exercicio'],
                    $exercicio['observacao'] ?? '',
                    $data_criacao,
                );
            }
        }

        return [
            'success' => true,
            'message' => 'Treino criado com sucesso.',
            'id_treino_criado' => $id_treino_criado
        ];
    }
}
