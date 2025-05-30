<?php

require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';

/**
 * Serviço responsável por operações relacionadas aos alunos
 * Centraliza a lógica de negócio relacionada aos dados do aluno
 */
class AlunoService
{
    private $users;
    private $solicitacao;

    public function __construct()
    {
        $this->users = new Users();
        $this->solicitacao = new SolicitacaoTreino();
    }

    /**
     * Obtém informações completas do aluno para criação de treino
     *
     * @param int $id_aluno ID do aluno
     * @return array Dados completos do aluno
     * @throws Exception Quando o aluno não é encontrado
     */
    public function getDadosCompletosPorId(int $id_aluno): array
    {
        $nome = $this->getNomeAluno($id_aluno);
        $formulario = $this->getFormularioSolicitacao($id_aluno);

        if (!$formulario) {
            throw new Exception("Solicitação de treino não encontrada para o aluno ID: $id_aluno");
        }

        return [
            'id' => $id_aluno,
            'nome' => $nome,
            'treinos' => $formulario['treinos'] ?? 0,
            'experiencia' => $formulario['experiencia'] ?? 'Não informado',
            'objetivo' => $formulario['objetivo'] ?? 'Não informado',
            'id_solicitacao' => $formulario['id'] ?? 0,
            'peso' => $formulario['peso'] ?? null,
            'altura' => $formulario['altura'] ?? null,
            'sexo' => $formulario['sexo'] ?? null
        ];
    }

    /**
     * Obtém o nome do aluno pelo ID
     *
     * @param int $id_aluno
     * @return string
     */
    public function getNomeAluno(int $id_aluno): string
    {
        $aluno = $this->users->getNomeAluno($id_aluno);
        return $aluno['username'] ?? 'Aluno não encontrado';
    }

    /**
     * Obtém o formulário de solicitação de treino
     *
     * @param int $id_aluno
     * @return array|null
     */
    private function getFormularioSolicitacao(int $id_aluno): ?array
    {
        return $this->solicitacao->getFormularioForCriacaoDeTreino($id_aluno);
    }

    /**
     * Valida se o aluno existe e tem solicitação pendente
     *
     * @param int $id_aluno
     * @return bool
     */
    public function validarAlunoParaTreino(int $id_aluno): bool
    {
        $nome = $this->getNomeAluno($id_aluno);
        $formulario = $this->getFormularioSolicitacao($id_aluno);

        return $nome !== 'Aluno não encontrado' && !empty($formulario);
    }
}
