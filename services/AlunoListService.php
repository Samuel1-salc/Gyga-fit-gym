<?php

require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';

/**
 * Serviço responsável por operações relacionadas à listagem e gerenciamento de alunos
 * Centraliza a lógica de negócio para o painel de alunos do instrutor
 */
class AlunoListService
{
    private $users;
    private $alunoInstrutor;

    public function __construct()
    {
        $this->users = new Users();
        $this->alunoInstrutor = new aluno_instrutor();
    }

    /**
     * Obtém a lista de alunos com base no termo de busca
     *
     * @param string|null $termoBusca Termo para filtrar alunos por nome
     * @return array Lista de alunos
     */
    public function obterListaAlunos(?string $termoBusca = null): array
    {
        if (!empty($termoBusca)) {
            $alunos = $this->users->getDataAlunosByNome($termoBusca);
        } else {
            $alunos = $this->users->getDataAlunosForPerfilAlunos();
        }

        return $this->enriquecerDadosAlunos($alunos);
    }

    /**
     * Enriquece os dados dos alunos com informações adicionais
     *
     * @param array $alunos Lista bruta de alunos
     * @return array Lista de alunos com dados enriquecidos
     */
    private function enriquecerDadosAlunos(array $alunos): array
    {
        $alunosEnriquecidos = [];

        foreach ($alunos as $aluno) {
            $alunosEnriquecidos[] = [
                'id' => $aluno['id'],
                'username' => $aluno['username'],
                'email' => $aluno['email'],
                'plano' => $aluno['plano'],
                'plano_nome' => $this->obterNomePlano($aluno['plano']),
                'disponibilidade' => $this->verificarDisponibilidade($aluno['id']),
                'disponivel' => $this->estaDisponivel($aluno['id'])
            ];
        }

        return $alunosEnriquecidos;
    }

    /**
     * Verifica a disponibilidade de um aluno
     *
     * @param int $idAluno ID do aluno
     * @return string Status da disponibilidade
     */
    public function verificarDisponibilidade(int $idAluno): string
    {
        $relacao = $this->alunoInstrutor->checkRelationshipUsers($idAluno);
        
        if ($relacao && !empty($relacao['id_instrutor'])) {
            return "Indisponível";
        }
        
        return "Disponível";
    }

    /**
     * Verifica se o aluno está disponível (boolean)
     *
     * @param int $idAluno ID do aluno
     * @return bool True se disponível, false caso contrário
     */
    public function estaDisponivel(int $idAluno): bool
    {
        return $this->verificarDisponibilidade($idAluno) === "Disponível";
    }

    /**
     * Obtém o nome do plano baseado no código
     *
     * @param int $codigoPlano Código do plano (1, 2 ou 3)
     * @return string Nome do plano
     */
    public function obterNomePlano(int $codigoPlano): string
    {
        $planos = [
            1 => "Mensal",
            2 => "Semestral", 
            3 => "Anual"
        ];

        return $planos[$codigoPlano] ?? "Não informado";
    }

    /**
     * Conta o total de alunos disponíveis
     *
     * @return int Número de alunos disponíveis
     */
    public function contarAlunosDisponiveis(): int
    {
        $todosAlunos = $this->users->getDataAlunosForPerfilAlunos();
        $contador = 0;

        foreach ($todosAlunos as $aluno) {
            if ($this->estaDisponivel($aluno['id'])) {
                $contador++;
            }
        }

        return $contador;
    }

    /**
     * Obtém estatísticas gerais dos alunos
     *
     * @return array Estatísticas dos alunos
     */
    public function obterEstatisticas(): array
    {
        $todosAlunos = $this->users->getDataAlunosForPerfilAlunos();
        $disponiveis = 0;
        $indisponiveis = 0;
        $planos = ['Mensal' => 0, 'Semestral' => 0, 'Anual' => 0];

        foreach ($todosAlunos as $aluno) {
            // Contabilizar disponibilidade
            if ($this->estaDisponivel($aluno['id'])) {
                $disponiveis++;
            } else {
                $indisponiveis++;
            }

            // Contabilizar planos
            $nomePlano = $this->obterNomePlano($aluno['plano']);
            if (isset($planos[$nomePlano])) {
                $planos[$nomePlano]++;
            }
        }

        return [
            'total' => count($todosAlunos),
            'disponiveis' => $disponiveis,
            'indisponiveis' => $indisponiveis,
            'planos' => $planos
        ];
    }

    /**
     * Valida o termo de busca
     *
     * @param string|null $termo Termo a ser validado
     * @return string|null Termo validado ou null
     */
    public function validarTermoBusca(?string $termo): ?string
    {
        if (empty($termo)) {
            return null;
        }

        // Remove caracteres especiais e limita o tamanho
        $termo = trim($termo);
        $termo = preg_replace('/[^a-zA-ZÀ-ÿ\s]/', '', $termo);
        
        if (strlen($termo) < 2) {
            return null;
        }

        return substr($termo, 0, 50); // Limita a 50 caracteres
    }

    /**
     * Processa a adição de um aluno ao instrutor
     *
     * @param int $idAluno ID do aluno a ser adicionado
     * @param int $idInstrutor ID do instrutor
     * @return array Resultado da operação
     */
    public function processarAdicaoAluno(int $idAluno, int $idInstrutor): array
    {
        // Verificar se o aluno está disponível
        if (!$this->estaDisponivel($idAluno)) {
            return [
                'sucesso' => false,
                'erro' => 'Aluno não está disponível para ser adicionado.'
            ];
        }

        try {
            // Aqui você chamaria o método para adicionar o aluno ao instrutor
            // Exemplo: $this->alunoInstrutor->adicionarAluno($idAluno, $idInstrutor);
            
            return [
                'sucesso' => true,
                'mensagem' => 'Aluno adicionado com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'erro' => 'Erro ao adicionar aluno: ' . $e->getMessage()
            ];
        }
    }
}
