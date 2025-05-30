<?php

require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models/Treino.class.php';

/**
 * Serviço responsável por operações relacionadas ao painel do instrutor
 * Centraliza a lógica de negócio para o dashboard e gerenciamento de alunos
 */
class InstructorService
{
    private $alunoInstrutor;
    private $solicitacaoTreino;
    private $treino;

    public function __construct()
    {
        $this->alunoInstrutor = new aluno_instrutor();
        $this->solicitacaoTreino = new SolicitacaoTreino();
        $this->treino = new Treino();
    }

    /**
     * Obtém todos os dados necessários para o painel do instrutor
     *
     * @param int $idInstrutor ID do instrutor
     * @param array $filtros Filtros aplicados (search, status)
     * @return array Dados completos do painel
     */
    public function getDadosCompletos(int $idInstrutor, array $filtros = []): array
    {
        try {
            // Buscar alunos do instrutor
            $alunosOriginais = $this->alunoInstrutor->getAlunosByIdInstrutor($idInstrutor);
            
            if (empty($alunosOriginais)) {
                return [
                    'sucesso' => true,
                    'alunos' => [],
                    'alunosOriginais' => [],
                    'metricas' => $this->getMetricasVazias(),
                    'mensagemFiltro' => $this->getMensagemSemAlunos()
                ];
            }

            // Aplicar filtros se especificados
            $alunosFiltrados = $this->aplicarFiltros($alunosOriginais, $filtros);
            
            // Extrair alunos únicos
            $alunosUnicos = $this->extrairAlunosUnicos($alunosFiltrados);
            
            // Calcular métricas
            $metricas = $this->calcularMetricas($alunosOriginais, $alunosUnicos);
            
            // Gerar mensagem de filtro
            $mensagemFiltro = $this->gerarMensagemFiltro($alunosOriginais, $alunosFiltrados, $filtros);

            return [
                'sucesso' => true,
                'alunos' => $alunosFiltrados,
                'alunosOriginais' => $alunosOriginais,
                'alunosUnicos' => $alunosUnicos,
                'metricas' => $metricas,
                'mensagemFiltro' => $mensagemFiltro,
                'filtros' => $filtros
            ];

        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => 'Erro ao carregar dados do painel: ' . $e->getMessage(),
                'alunos' => [],
                'alunosOriginais' => [],
                'metricas' => $this->getMetricasVazias()
            ];
        }
    }

    /**
     * Extrai alunos únicos de uma lista
     *
     * @param array $dadosAlunos Lista de alunos
     * @return array Alunos únicos
     */
    public function extrairAlunosUnicos(array $dadosAlunos): array
    {
        $alunosUnicos = [];
        $idsProcessados = [];
        $contadorSemId = 0;

        foreach ($dadosAlunos as $item) {
            $idAluno = $item['id_aluno'] ?? null;
            
            if (empty($idAluno)) {
                $chaveUnica = 'sem_id_' . $contadorSemId . '_' . ($item['nome_aluno'] ?? '');
                $contadorSemId++;
            } else {
                $chaveUnica = $idAluno;
            }

            if (!in_array($chaveUnica, $idsProcessados)) {
                $alunosUnicos[] = [
                    'id_aluno' => $item['id_aluno'] ?? null,
                    'nome_aluno' => $item['nome_aluno'] ?? '',
                    'data_solicitacao' => $item['data_solicitacao'] ?? '',
                    'contato_aluno' => $item['contato_aluno'] ?? '',
                    'processo' => $item['processo'] ?? '',
                    'status' => $item['status'] ?? '',
                ];
                $idsProcessados[] = $chaveUnica;
            }
        }

        return $alunosUnicos;
    }

    /**
     * Conta solicitações de treino para um aluno específico
     *
     * @param int $idAluno ID do aluno
     * @param array $alunos Lista de alunos para buscar
     * @return int Número de solicitações
     */
    public function countSolicitacaoTreino(int $idAluno, array $alunos): int
    {
        $count = 0;
        foreach ($alunos as $item) {
            if (!empty($idAluno) && 
                isset($item['id_aluno']) && 
                $item['id_aluno'] == $idAluno && 
                !empty($item['data_created'])) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Obtém o status de um aluno específico
     *
     * @param int $idAluno ID do aluno
     * @param array $alunos Lista de alunos para buscar
     * @return string|null Status do aluno
     */
    public function getStatus(int $idAluno, array $alunos): ?string
    {
        foreach ($alunos as $item) {
            if (!empty($idAluno) && 
                isset($item['id_aluno']) && 
                $item['id_aluno'] == $idAluno) {
                return $item['status'] ?? null;
            }
        }
        return null;
    }

    /**
     * Obtém formulários de solicitação para um aluno
     *
     * @param int $idAluno ID do aluno
     * @param array $alunos Lista de alunos para buscar
     * @return array|null Formulários do aluno
     */
    public function getFormulariosByAluno(int $idAluno, array $alunos): ?array
    {
        $formularios = [];
        foreach ($alunos as $item) {
            if (!empty($idAluno) && 
                isset($item['id_aluno']) && 
                $item['id_aluno'] == $idAluno && 
                !empty($item['data_created'])) {
                $formularios[] = [
                    'id_aluno' => $item['id_aluno'],
                    'nome_aluno' => $item['nome_aluno'] ?? '',
                    'data_created' => $item['data_created'],
                    'experiencia' => $item['experiencia'] ?? '',
                    'objetivo' => $item['objetivo'] ?? '',
                    'treinos' => $item['treinos'] ?? 0,
                    'sexo' => $item['sexo'] ?? '',
                    'peso' => $item['peso'] ?? null,
                    'altura' => $item['altura'] ?? null,
                    'status' => $item['status'] ?? '',
                ];
            }
        }
        return empty($formularios) ? null : $formularios;
    }

    /**
     * Verifica se há solicitações em andamento
     *
     * @param array|null $solicitacoes Lista de solicitações
     * @return bool True se há status "em andamento"
     */
    public function veryFyStatus(?array $solicitacoes): bool
    {
        if (empty($solicitacoes)) {
            return false;
        }

        $statusVerificar = 'em andamento';
        foreach ($solicitacoes as $sol) {
            if (isset($sol['status']) && $sol['status'] == $statusVerificar) {
                return true;
            }
        }
        return false;
    }

    /**
     * Conta alunos pendentes (status "em andamento")
     *
     * @param array $alunosUnicos Lista de alunos únicos
     * @return int Número de alunos pendentes
     */
    public function countPendentes(array $alunosUnicos): int
    {
        $status = 'em andamento';
        $count = 0;
        foreach ($alunosUnicos as $item) {
            if (isset($item['status']) && $item['status'] == $status) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Aplica filtros nos dados de alunos
     *
     * @param array $alunos Lista original de alunos
     * @param array $filtros Filtros a aplicar
     * @return array Dados filtrados
     */
    private function aplicarFiltros(array $alunos, array $filtros): array
    {
        $alunosFiltrados = $alunos;

        // Aplicar filtro de status
        if (!empty($filtros['status']) && $filtros['status'] !== 'todos') {
            $alunosFiltrados = $this->aplicarFiltroStatus($alunosFiltrados, $filtros['status']);
        }

        // Aplicar filtro de busca
        if (!empty($filtros['search'])) {
            $alunosFiltrados = $this->aplicarPesquisa($alunosFiltrados, $filtros['search']);
        }

        return $alunosFiltrados;
    }

    /**
     * Aplica filtro de status
     *
     * @param array $alunos Lista de alunos
     * @param string $statusFiltro Status para filtrar
     * @return array Alunos filtrados
     */
    private function aplicarFiltroStatus(array $alunos, string $statusFiltro): array
    {
        $statusFiltro = strtolower(trim($statusFiltro));
        
        return array_filter($alunos, function ($item) use ($statusFiltro) {
            return !empty($item['status']) && strtolower($item['status']) === $statusFiltro;
        });
    }

    /**
     * Aplica filtro de pesquisa por nome
     *
     * @param array $alunos Lista de alunos
     * @param string $search Termo de busca
     * @return array Alunos filtrados
     */
    private function aplicarPesquisa(array $alunos, string $search): array
    {
        $search = trim($search);
        if (empty($search)) {
            return $alunos;
        }

        return array_filter($alunos, function ($item) use ($search) {
            return isset($item['nome_aluno']) && 
                   stripos($item['nome_aluno'], $search) !== false;
        });
    }

    /**
     * Calcula métricas do dashboard
     *
     * @param array $alunosOriginais Lista original de alunos
     * @param array $alunosUnicos Lista de alunos únicos
     * @return array Métricas calculadas
     */
    private function calcularMetricas(array $alunosOriginais, array $alunosUnicos): array
    {
        $totalAlunos = count($this->extrairAlunosUnicos($alunosOriginais));
        $alunosPendentes = $this->countPendentes($alunosUnicos);
        
        return [
            'total_alunos' => $totalAlunos,
            'alunos_pendentes' => $alunosPendentes,
            'alunos_atendidos' => $totalAlunos - $alunosPendentes,
            'disponibilidade' => $this->calcularDisponibilidade()
        ];
    }

    /**
     * Calcula disponibilidade do instrutor
     *
     * @return string Status de disponibilidade
     */
    private function calcularDisponibilidade(): string
    {
        // Aqui você pode implementar lógica mais complexa
        // Por ora, retorna sempre disponível
        return 'disponível';
    }

    /**
     * Gera mensagem de filtro baseada nos resultados
     *
     * @param array $alunosOriginais Lista original
     * @param array $alunosFiltrados Lista filtrada
     * @param array $filtros Filtros aplicados
     * @return string HTML da mensagem
     */
    private function gerarMensagemFiltro(array $alunosOriginais, array $alunosFiltrados, array $filtros): string
    {
        if (empty($alunosOriginais)) {
            return $this->getMensagemSemAlunos();
        }

        $alunosUnicosFiltrados = $this->extrairAlunosUnicos($alunosFiltrados);
        $totalFiltrados = count($alunosUnicosFiltrados);

        // Se não há filtros ativos
        if (empty($filtros['status']) && empty($filtros['search'])) {
            return '';
        }

        // Se aplicou filtro de status e não há resultados
        if (!empty($filtros['status']) && $filtros['status'] !== 'todos' && empty($alunosFiltrados)) {
            $statusLabel = ($filtros['status'] === 'em andamento') ? 'pendente' : 'atendido';
            return '<div class="alert alert-warning"><i data-lucide="alert-circle" class="icon"></i><strong>Nenhum aluno ' . $statusLabel . ' encontrado.</strong></div>';
        }

        // Se aplicou busca
        if (!empty($filtros['search'])) {
            $termoBusca = htmlspecialchars($filtros['search']);
            
            if (!empty($alunosFiltrados)) {
                $statusLabel = '';
                if (!empty($filtros['status']) && $filtros['status'] !== 'todos') {
                    $statusLabel = ($filtros['status'] === 'em andamento') ? 'pendente' : 'atendido';
                }
                return '<div class="alert alert-info"><i data-lucide="search" class="icon"></i><strong>' . $totalFiltrados . ' aluno(s) ' . $statusLabel . ' encontrado(s) para "' . $termoBusca . '".</strong></div>';
            } else {
                return '<div class="alert alert-error"><i data-lucide="x-circle" class="icon"></i><strong>Nenhum aluno encontrado para "' . $termoBusca . '".</strong></div>';
            }
        }

        return '';
    }

    /**
     * Retorna métricas vazias
     *
     * @return array
     */
    private function getMetricasVazias(): array
    {
        return [
            'total_alunos' => 0,
            'alunos_pendentes' => 0,
            'alunos_atendidos' => 0,
            'disponibilidade' => 'disponível'
        ];
    }

    /**
     * Retorna mensagem para quando não há alunos
     *
     * @return string
     */
    private function getMensagemSemAlunos(): string
    {
        return '<div class="alert alert-error"><i data-lucide="users-x" class="icon"></i><strong>Nenhum aluno encontrado.</strong></div>';
    }

    /**
     * Valida e sanitiza filtros de entrada
     *
     * @param array $dadosEntrada Dados do GET/POST
     * @return array Filtros validados
     */
    public function validarFiltros(array $dadosEntrada): array
    {
        $filtros = [];

        // Validar status
        $status = $dadosEntrada['status'] ?? '';
        if (!empty($status)) {
            $statusValidos = ['todos', 'em andamento', 'atendido'];
            $filtros['status'] = in_array($status, $statusValidos) ? $status : 'todos';
        }

        // Validar termo de busca
        $search = $dadosEntrada['search'] ?? '';
        if (!empty($search)) {
            $filtros['search'] = trim(filter_var($search, FILTER_SANITIZE_STRING));
        }

        return $filtros;
    }
}
