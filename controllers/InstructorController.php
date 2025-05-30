<?php

require_once __DIR__ . '/../services/InstructorService.php';

/**
 * Controller responsável por gerenciar o painel do instrutor
 * Faz a ponte entre a camada de serviços e a view
 */
class InstructorController
{
    private $instructorService;
    private $dadosView;

    public function __construct()
    {
        $this->instructorService = new InstructorService();
        $this->dadosView = [];
    }

    /**
     * Processa a requisição para exibir o painel do instrutor
     *
     * @return array Dados para a view
     */
    public function exibirPainelInstrutor(): array
    {
        session_start();

        try {
            // Validar sessão
            $idInstrutor = $this->validarSessaoInstrutor();
            
            // Obter e validar filtros
            $filtros = $this->processarFiltros();
            
            // Obter dados do serviço
            $dados = $this->instructorService->getDadosCompletos($idInstrutor, $filtros);
            
            if (!$dados['sucesso']) {
                throw new Exception($dados['erro']);
            }

            // Adicionar dados da sessão
            $dados['instrutor'] = $_SESSION['usuario'];
            
            // Preparar dados para a view
            $this->dadosView = [
                'sucesso' => true,
                'erro' => null,
                'instrutor' => $dados['instrutor'],
                'alunos' => $dados['alunos'],
                'alunosOriginais' => $dados['alunosOriginais'],
                'alunosUnicos' => $dados['alunosUnicos'] ?? [],
                'metricas' => $dados['metricas'],
                'mensagemFiltro' => $dados['mensagemFiltro'],
                'filtros' => $dados['filtros']
            ];

        } catch (Exception $e) {
            $this->dadosView = [
                'sucesso' => false,
                'erro' => $e->getMessage(),
                'instrutor' => $_SESSION['usuario'] ?? null,
                'alunos' => [],
                'alunosOriginais' => [],
                'alunosUnicos' => [],
                'metricas' => [
                    'total_alunos' => 0,
                    'alunos_pendentes' => 0,
                    'alunos_atendidos' => 0,
                    'disponibilidade' => 'disponível'
                ],
                'mensagemFiltro' => '<div class="alert alert-error"><i data-lucide="alert-triangle" class="icon"></i><strong>Erro ao carregar dados do painel.</strong></div>',
                'filtros' => []
            ];
        }

        return $this->dadosView;
    }

    /**
     * Obtém dados específicos para componentes da view
     *
     * @param string $componente Nome do componente
     * @param array $parametros Parâmetros específicos
     * @return array Dados do componente
     */
    public function getDadosComponente(string $componente, array $parametros = []): array
    {
        switch ($componente) {
            case 'metricas':
                return $this->getDadosMetricas();
                
            case 'aluno-card':
                return $this->getDadosAlunoCard($parametros);
                
            case 'search-filters':
                return $this->getDadosSearchFilters();
                
            default:
                return [];
        }
    }

    /**
     * Obtém dados para o componente de métricas
     *
     * @return array
     */
    private function getDadosMetricas(): array
    {
        $metricas = $this->dadosView['metricas'] ?? [];
        $instrutor = $this->dadosView['instrutor'] ?? [];
        
        return [
            'total_alunos' => $metricas['total_alunos'] ?? 0,
            'alunos_pendentes' => $metricas['alunos_pendentes'] ?? 0,
            'alunos_atendidos' => $metricas['alunos_atendidos'] ?? 0,
            'disponibilidade' => $this->calcularDisponibilidade($instrutor)
        ];
    }

    /**
     * Obtém dados para o card de um aluno específico
     *
     * @param array $parametros Deve conter 'aluno' e 'alunos_originais'
     * @return array
     */
    private function getDadosAlunoCard(array $parametros): array
    {
        $aluno = $parametros['aluno'] ?? [];
        $alunosOriginais = $parametros['alunos_originais'] ?? [];
        
        if (empty($aluno['id_aluno'])) {
            return $aluno;
        }
        
        // Adicionar dados calculados
        $aluno['count_solicitacoes'] = $this->instructorService->countSolicitacaoTreino(
            $aluno['id_aluno'], 
            $alunosOriginais
        );
        
        $aluno['status_atual'] = $this->instructorService->getStatus(
            $aluno['id_aluno'], 
            $alunosOriginais
        );
        
        $aluno['formularios'] = $this->instructorService->getFormulariosByAluno(
            $aluno['id_aluno'], 
            $alunosOriginais
        );
        
        $aluno['pode_criar_treino'] = $this->instructorService->veryFyStatus(
            $aluno['formularios']
        );
        
        return $aluno;
    }

    /**
     * Obtém dados para o componente de busca e filtros
     *
     * @return array
     */
    private function getDadosSearchFilters(): array
    {
        $filtros = $this->dadosView['filtros'] ?? [];
        
        return [
            'termo_busca' => $filtros['search'] ?? '',
            'status_filtro' => $filtros['status'] ?? '',
            'opcoes_status' => [
                'todos' => 'Todos os alunos',
                'em andamento' => 'Pendentes',
                'atendido' => 'Atendidos'
            ]
        ];
    }

    /**
     * Calcula disponibilidade do instrutor
     *
     * @param array $instrutor Dados do instrutor
     * @return string
     */
    private function calcularDisponibilidade(array $instrutor): string
    {
        $dataSaida = $instrutor['data_saida'] ?? null;
        return ($dataSaida && $dataSaida != '0000-00-00') ? 'indisponível' : 'disponível';
    }

    /**
     * Processa e valida filtros da requisição
     *
     * @return array Filtros validados
     */
    private function processarFiltros(): array
    {
        return $this->instructorService->validarFiltros($_GET);
    }

    /**
     * Valida se o instrutor está logado e retorna seu ID
     *
     * @return int ID do instrutor
     * @throws Exception Se a sessão for inválida
     */
    private function validarSessaoInstrutor(): int
    {
        if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
            throw new Exception("Sessão inválida. Faça login novamente.");
        }

        $idInstrutor = $_SESSION['usuario']['id'];
        
        if (!is_numeric($idInstrutor) || $idInstrutor <= 0) {
            throw new Exception("ID do instrutor inválido.");
        }

        return (int)$idInstrutor;
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
                'instrutor' => null,
                'alunos' => [],
                'metricas' => null,
                'tem_alunos' => false
            ];
        }

        return [
            'instrutor' => $this->dadosView['instrutor'],
            'alunos' => $this->dadosView['alunosUnicos'] ?? [],
            'alunos_originais' => $this->dadosView['alunosOriginais'] ?? [],
            'metricas' => $this->dadosView['metricas'],
            'mensagem_filtro' => $this->dadosView['mensagemFiltro'],
            'filtros' => $this->dadosView['filtros'],
            'tem_alunos' => !empty($this->dadosView['alunosUnicos'])
        ];
    }

    /**
     * Gera mensagem de resultado baseada nos dados
     *
     * @return string Mensagem informativa
     */
    public function getMensagemResultado(): string
    {
        $dados = $this->dadosView;

        if ($this->temErro()) {
            return "Erro ao carregar dados do painel";
        }

        $filtros = $dados['filtros'] ?? [];
        $totalAlunos = count($dados['alunosUnicos'] ?? []);

        if (!empty($filtros['search'])) {
            return "Encontrados {$totalAlunos} aluno(s) para \"{$filtros['search']}\"";
        }

        if (!empty($filtros['status']) && $filtros['status'] !== 'todos') {
            $statusLabel = ($filtros['status'] === 'em andamento') ? 'pendentes' : 'atendidos';
            return "Exibindo {$totalAlunos} aluno(s) {$statusLabel}";
        }

        return "Exibindo todos os {$totalAlunos} aluno(s)";
    }
}
