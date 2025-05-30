<?php

require_once __DIR__ . '/../services/AlunoListService.php';

/**
 * Controller responsável por gerenciar a listagem e ações relacionadas aos alunos
 * Faz a ponte entre a camada de serviços e a view
 */
class AlunoController
{
    private $alunoListService;
    private $dadosView;

    public function __construct()
    {
        $this->alunoListService = new AlunoListService();
        $this->dadosView = [];
    }

    /**
     * Processa a requisição para exibir a lista de alunos
     *
     * @return array Dados para a view
     */
    public function exibirListaAlunos(): array
    {
        session_start();

        try {
            // Processar termo de busca
            $termoBusca = $this->processarTermoBusca();

            // Obter lista de alunos
            $alunos = $this->alunoListService->obterListaAlunos($termoBusca);

            // Obter estatísticas
            $estatisticas = $this->alunoListService->obterEstatisticas();

            // Preparar dados para a view
            $this->dadosView = [
                'alunos' => $alunos,
                'termo_busca' => $termoBusca,
                'estatisticas' => $estatisticas,
                'total_encontrados' => count($alunos),
                'sucesso' => true,
                'erro' => null
            ];
        } catch (Exception $e) {
            $this->dadosView = [
                'sucesso' => false,
                'erro' => $e->getMessage(),
                'alunos' => [],
                'termo_busca' => null,
                'estatisticas' => null,
                'total_encontrados' => 0
            ];
        }

        return $this->dadosView;
    }

    /**
     * Processa e valida o termo de busca
     *
     * @return string|null Termo de busca validado
     */
    private function processarTermoBusca(): ?string
    {
        $termo = $_GET['search'] ?? null;
        return $this->alunoListService->validarTermoBusca($termo);
    }

    /**
     * Processa a ação de adicionar um aluno ao instrutor
     *
     * @return array Resultado da operação
     */
    public function processarAdicaoAluno(): array
    {
        try {
            // Validar dados do POST
            $idAluno = $this->validarIdAluno();
            $idInstrutor = $this->obterIdInstrutor();

            // Processar adição
            $resultado = $this->alunoListService->processarAdicaoAluno($idAluno, $idInstrutor);

            return $resultado;
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    /**
     * Valida o ID do aluno vindo do POST
     *
     * @return int ID do aluno validado
     * @throws Exception Se o ID for inválido
     */
    private function validarIdAluno(): int
    {
        $idAluno = $_POST['id_aluno'] ?? null;

        if ($idAluno === null) {
            throw new Exception("ID do aluno não foi fornecido.");
        }

        $idAluno = filter_var($idAluno, FILTER_VALIDATE_INT);

        if ($idAluno === false || $idAluno <= 0) {
            throw new Exception("ID do aluno inválido.");
        }

        return $idAluno;
    }

    /**
     * Obtém o ID do instrutor da sessão
     *
     * @return int ID do instrutor
     * @throws Exception Se não houver instrutor na sessão
     */
    private function obterIdInstrutor(): int
    {
        if (!isset($_SESSION['id_usuario'])) {
            throw new Exception("Sessão inválida. Faça login novamente.");
        }

        return (int)$_SESSION['id_usuario'];
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

    /**
     * Obtém dados formatados para exibição na view
     *
     * @return array Dados formatados
     */
    public function getDadosFormatados(): array
    {
        if ($this->temErro()) {
            return [
                'erro' => $this->getErro(),
                'alunos' => [],
                'estatisticas' => null
            ];
        }

        return [
            'alunos' => $this->dadosView['alunos'],
            'termo_busca' => $this->dadosView['termo_busca'],
            'estatisticas' => $this->dadosView['estatisticas'],
            'total_encontrados' => $this->dadosView['total_encontrados'],
            'tem_busca' => !empty($this->dadosView['termo_busca'])
        ];
    }

    /**
     * Gera mensagem de resultado da busca
     *
     * @return string Mensagem informativa
     */
    public function getMensagemBusca(): string
    {
        $dados = $this->dadosView;

        if (!empty($dados['termo_busca'])) {
            $total = $dados['total_encontrados'];
            return "Encontrados {$total} aluno(s) para \"{$dados['termo_busca']}\"";
        }

        if (isset($dados['estatisticas'])) {
            $total = $dados['estatisticas']['total'];
            return "Exibindo todos os {$total} alunos cadastrados";
        }

        return "Nenhum aluno encontrado";
    }
}
