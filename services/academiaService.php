<?php
require_once __DIR__ . '/../models/UserAcademia.class.php';
/**
 * Classe de serviço para operações relacionadas à academia.
 * 
 * Esta classe encapsula a lógica de negócios para o cadastro de academias,
 * utilizando a classe UserAcademia para interagir com o banco de dados.
 */
class AcademiaService
{
    private $userAcademia;

    public function __construct()
    {
        require_once __DIR__ . '/../models/UserAcademia.class.php';
        $this->userAcademia = new UserAcademia();
    }

    public function cadastrarAcademia($nome, $cep, $email, $cnpj, $capacidade, $unidade)
    {
        try {
            // Valida os dados antes de cadastrar
            if (!$this->validarDados($nome, $cep, $email, $cnpj, $capacidade, $unidade)) {
                throw new Exception("Dados inválidos fornecidos.");
            }

            // Chama o método de cadastro da classe UserAcademia
            return $this->userAcademia->cadastrarAcademia($nome, $capacidade, $alunosAtivos = 0, $toalPersonais = 0, $cep, $email, $cnpj,  $unidade);
        } catch (Exception $e) {
            error_log("Erro ao cadastrar academia: " . $e->getMessage());
            return false;
        }
    }

    public function validarDados($nome, $cep, $email, $cnpj, $capacidade, $unidade)
    {
        // Verifica se os campos obrigatórios estão preenchidos
        if (empty($nome) || empty($cep) || empty($email) || empty($cnpj) || empty($capacidade) || empty($unidade)) {
            return false;
        }

        // Valida o formato do email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Valida o formato do CNPJ (apenas exemplo, pode ser necessário uma validação mais robusta)
        if (!preg_match('/^\d{14}$/', $cnpj)) {
            return false;
        }

        // Valida a capacidade como um número inteiro positivo
        if (!is_numeric($capacidade) || $capacidade <= 0) {
            return false;
        }

        return true;
    }

    public function getALLAcademias()
    {
        try {
            return $this->userAcademia->getAllAcademias();
        } catch (Exception $e) {
            error_log("Erro ao recuperar academias: " . $e->getMessage());
            return [];
        }
    }

    public function atualizarAcademia($unidade, $dados)
    {
        try {
            return $this->userAcademia->atualizarAcademia($unidade, $dados);
        } catch (Exception $e) {
            error_log("Erro ao atualizar academia: " . $e->getMessage());
            return false;
        }
    }
    public function getAcademiaPorUnidade($unidade)
    {
        try {
            return $this->userAcademia->getAcademiaPorUnidade($unidade);
        } catch (Exception $e) {
            error_log("Erro ao buscar academia por unidade: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Organiza todos os dados da academia com informações completas
     * 
     * @param string|null $unidade Unidade específica (opcional)
     * @return array Dados organizados da academia
     */
    public function organizarDadosAcademia($unidade = null)
    {
        try {
            // Busca dados básicos das academias
            $academias = $this->userAcademia->getInfoAcademia($unidade);

            if (empty($academias)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Nenhuma academia encontrada',
                    'dados' => []
                ];
            }

            $dadosOrganizados = [];

            foreach ($academias as $academia) {
                // Calcula métricas adicionais
                $ocupacao = $this->calcularOcupacao($academia['total_alunos_atual'], $academia['capacidade']);
                $vagasDisponiveis = max(0, $academia['capacidade'] - $academia['total_alunos_atual']);
                $statusCapacidade = $this->determinarStatusCapacidade($ocupacao);
                $ratioAlunoInstrutor = $this->calcularRatioAlunoInstrutor($academia['total_alunos_atual'], $academia['total_instrutores_atual']);

                $dadosOrganizados[] = [
                    // Dados básicos
                    'id' => $academia['id'],
                    'nome' => $academia['nome'],
                    'unidade' => $academia['unidade'],
                    'capacidade_maxima' => (int)$academia['capacidade'],
                    'cep' => $academia['cep'],
                    'email' => $academia['email'],
                    'cnpj' => $academia['cnpj'],

                    // Dados de ocupação
                    'alunos_ativos' => (int)$academia['total_alunos_atual'],
                    'instrutores_ativos' => (int)$academia['total_instrutores_atual'],
                    'vagas_disponiveis' => $vagasDisponiveis,
                    'ocupacao_percentual' => $ocupacao,
                    'status_capacidade' => $statusCapacidade,
                    'ratio_aluno_instrutor' => $ratioAlunoInstrutor,

                    // Indicadores de status
                    'pode_aceitar_alunos' => $vagasDisponiveis > 0,
                    'capacidade_critica' => $ocupacao >= 95,
                    'necessita_mais_instrutores' => $ratioAlunoInstrutor > 15,

                    // Dados formatados para exibição
                    'ocupacao_texto' => $ocupacao . '%',
                    'status_texto' => $this->formatarStatusTexto($statusCapacidade),
                    'capacidade_texto' => $academia['total_alunos_atual'] . '/' . $academia['capacidade']
                ];
            }

            return [
                'sucesso' => true,
                'total_academias' => count($dadosOrganizados),
                'dados' => $dadosOrganizados,
                'resumo' => $this->gerarResumoGeral($dadosOrganizados)
            ];
        } catch (Exception $e) {
            error_log("Erro ao organizar dados da academia: " . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro interno ao organizar dados',
                'dados' => []
            ];
        }
    }

    /**
     * Calcula o percentual de ocupação
     */
    private function calcularOcupacao($alunosAtuais, $capacidade)
    {
        if ($capacidade <= 0) return 0;
        return round(($alunosAtuais / $capacidade) * 100, 2);
    }

    /**
     * Determina o status da capacidade
     */
    private function determinarStatusCapacidade($ocupacao)
    {
        if ($ocupacao >= 95) return 'lotada';
        if ($ocupacao >= 80) return 'quase_lotada';
        if ($ocupacao >= 50) return 'moderada';
        return 'baixa';
    }

    /**
     * Calcula a proporção aluno/instrutor
     */
    private function calcularRatioAlunoInstrutor($alunos, $instrutores)
    {
        if ($instrutores <= 0) return $alunos;
        return round($alunos / $instrutores, 2);
    }

    /**
     * Formata o status para exibição
     */
    private function formatarStatusTexto($status)
    {
        $textos = [
            'lotada' => 'Lotada',
            'quase_lotada' => 'Quase Lotada',
            'moderada' => 'Ocupação Moderada',
            'baixa' => 'Ocupação Baixa'
        ];

        return $textos[$status] ?? 'Indefinido';
    }

    /**
     * Gera resumo geral das academias
     */
    private function gerarResumoGeral($academias)
    {
        $resumo = [
            'total_alunos' => 0,
            'total_instrutores' => 0,
            'capacidade_total' => 0,
            'ocupacao_media' => 0,
            'academias_lotadas' => 0,
            'academias_baixa_ocupacao' => 0
        ];

        foreach ($academias as $academia) {
            $resumo['total_alunos'] += $academia['alunos_ativos'];
            $resumo['total_instrutores'] += $academia['instrutores_ativos'];
            $resumo['capacidade_total'] += $academia['capacidade_maxima'];

            if ($academia['status_capacidade'] === 'lotada') {
                $resumo['academias_lotadas']++;
            }
            if ($academia['status_capacidade'] === 'baixa') {
                $resumo['academias_baixa_ocupacao']++;
            }
        }

        if ($resumo['capacidade_total'] > 0) {
            $resumo['ocupacao_media'] = round(($resumo['total_alunos'] / $resumo['capacidade_total']) * 100, 2);
        }

        return $resumo;
    }
}
