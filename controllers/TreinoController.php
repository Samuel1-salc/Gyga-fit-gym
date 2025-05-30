<?php

require_once __DIR__ . '/../services/AlunoService.php';
require_once __DIR__ . '/../services/TreinoService.php';

/**
 * Controller responsável por gerenciar a criação de planos de treino
 * Faz a ponte entre a camada de serviços e a view
 */
class TreinoController
{
    private $alunoService;
    private $treinoService;
    private $dadosView;

    public function __construct()
    {
        $this->alunoService = new AlunoService();
        $this->treinoService = new TreinoService();
        $this->dadosView = [];
    }

    /**
     * Processa a requisição para exibir a página de criação de treino
     *
     * @return array Dados para a view
     * @throws Exception
     */
    public function exibirPaginaCriacaoTreino(): array
    {
        session_start();

        $id_aluno = $this->validarParametroIdAluno();

        try {
            // Carregar dados do aluno
            $dadosAluno = $this->alunoService->getDadosCompletosPorId($id_aluno);

            // Carregar dados para criação de treino
            $dadosTreino = $this->treinoService->getDadosParaCriacaoTreino();

            // Preparar dados para a view
            $this->dadosView = [
                'aluno' => $dadosAluno,
                'grupos_musculares' => $dadosTreino['grupos_musculares'],
                'exercicios' => $dadosTreino['exercicios'],
                'exercicios_por_grupo' => $this->treinoService->getExerciciosPorGrupo(),
                'sucesso' => true,
                'erro' => null
            ];
        } catch (Exception $e) {
            $this->dadosView = [
                'sucesso' => false,
                'erro' => $e->getMessage(),
                'aluno' => null,
                'grupos_musculares' => [],
                'exercicios' => []
            ];
        }

        return $this->dadosView;
    }

    /**
     * Valida e retorna o parâmetro id_aluno
     *
     * @return int
     * @throws Exception
     */
    private function validarParametroIdAluno(): int
    {
        $id_aluno = $_GET['id_aluno'] ?? null;

        if ($id_aluno === null) {
            throw new Exception("ID do aluno não foi fornecido.");
        }

        $id_aluno = filter_var($id_aluno, FILTER_VALIDATE_INT);

        if ($id_aluno === false || $id_aluno <= 0) {
            throw new Exception("ID do aluno inválido.");
        }

        // Validar se o aluno existe e tem solicitação
        if (!$this->alunoService->validarAlunoParaTreino($id_aluno)) {
            throw new Exception("Aluno não encontrado ou não possui solicitação de treino pendente.");
        }

        return $id_aluno;
    }

    /**
     * Processa o envio do formulário de criação de treino
     *
     * @return array Resultado do processamento
     */
    public function processarCriacaoTreino(): array
    {
        try {
            // Validar dados do formulário
            $dados = $_POST['dados'] ?? [];
            $observacoes = $_POST['obs'] ?? [];
            $idSolicitacao = (int)($_POST['id_solicitacao'] ?? 0);
            $idAluno = (int)($_POST['id_aluno'] ?? 0);

            // Mesclar observações com os dados
            foreach ($observacoes as $letra => $obs) {
                if (isset($dados[$letra])) {
                    $dados[$letra]['observacoes'] = $obs;
                }
            }

            // Validar dados
            $validacao = $this->treinoService->validarDadosTreino($dados);

            if (!$validacao['valido']) {
                return [
                    'sucesso' => false,
                    'erros' => $validacao['erros']
                ];
            }

            // Formatar dados para processamento
            $dadosFormatados = $this->treinoService->formatarDadosParaProcessamento(
                $dados,
                $idSolicitacao,
                $idAluno
            );

            // Aqui você chamaria o serviço para salvar o treino no banco
            // Por exemplo: $this->treinoService->salvarPlanoTreino($dadosFormatados);

            return [
                'sucesso' => true,
                'mensagem' => 'Plano de treino criado com sucesso!',
                'dados' => $dadosFormatados
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtém os dados preparados para a view
     *
     * @return array
     */
    public function getDadosView(): array
    {
        return $this->dadosView;
    }

    /**
     * Verifica se houve erro no processamento
     *
     * @return bool
     */
    public function temErro(): bool
    {
        return !empty($this->dadosView['erro']) || !$this->dadosView['sucesso'];
    }

    /**
     * Obtém a mensagem de erro
     *
     * @return string|null
     */
    public function getErro(): ?string
    {
        return $this->dadosView['erro'] ?? null;
    }
}
