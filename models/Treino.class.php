<?php
require_once __DIR__ . '/../config/database.class.php';
require_once __DIR__ . '/Usuarios.class.php';

/**
 * Classe responsável pelas operações relacionadas ao plano de treino.
 * Inclui métodos para cadastrar treinos, buscar treinos, exercícios e grupos musculares.
 */
class Treino
{
    /** @var Database Instância da conexão com o banco */
    private $con;
    /** @var PDO Link PDO para executar queries */
    private $link;

    /**
     * Construtor: inicializa a conexão usando singleton.
     */
    public function __construct()
    {
        $this->con = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
     * Cadastra um novo treino no banco de dados.
     */
    public function cadastrarTreino(
        $id_instrutor,
        $id_aluno,
        $id_treino_criado,
        $letra_treino,
        $num_exercicio,
        $nome_exercicio,
        $series,
        $repeticoes,
        $observacao,
        $data_criacao
    ) {
        $stmt = $this->link->prepare(
            "INSERT INTO plano_de_treino
             (id_instrutor, id_aluno, id_treino_criado, letra_treino,
              num_exercicio, nome_exercicio, series, repeticoes,
              observacao, data_criacao)
             VALUES
             (:id_instrutor, :id_aluno, :id_treino_criado, :letra_treino,
              :num_exercicio, :nome_exercicio, :series, :repeticoes,
              :observacao, :data_criacao)"
        );
        $stmt->bindParam(':id_instrutor', $id_instrutor, PDO::PARAM_INT);
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt->bindParam(':id_treino_criado', $id_treino_criado, PDO::PARAM_INT);
        $stmt->bindParam(':letra_treino', $letra_treino, PDO::PARAM_STR);
        $stmt->bindParam(':num_exercicio', $num_exercicio, PDO::PARAM_INT);
        $stmt->bindParam(':nome_exercicio', $nome_exercicio, PDO::PARAM_STR);
        $stmt->bindParam(':series', $series, PDO::PARAM_INT);
        $stmt->bindParam(':repeticoes', $repeticoes, PDO::PARAM_STR);
        $stmt->bindParam(':observacao', $observacao, PDO::PARAM_STR);
        $stmt->bindParam(':data_criacao', $data_criacao, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Busca todos os registros de um treino pelo seu ID criado.
     */
    public function getTreinoByIdTreino($id_treino_criado)
    {
        $stmt = $this->link->prepare(
            "SELECT * FROM plano_de_treino
             WHERE id_treino_criado = :id_treino_criado"
        );
        $stmt->bindParam(':id_treino_criado', $id_treino_criado, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca o último id_treino_criado para um aluno.
     */
    public function getIdTreinoCriado($id_aluno)
    {
        $stmt = $this->link->prepare(
            "SELECT id_treino_criado FROM plano_de_treino
             WHERE id_aluno = :id_aluno
             ORDER BY id DESC
             LIMIT 1"
        );
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna todas as letras usadas em um treino.
     */
    public function getLetrasDotreino($id_treino_criado)
    {
        $stmt = $this->link->prepare(
            "SELECT DISTINCT letra_treino FROM plano_de_treino
             WHERE id_treino_criado = :id_treino_criado"
        );
        $stmt->bindParam(':id_treino_criado', $id_treino_criado, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca a data de criação do último treino de um aluno.
     */
    public function getDataTreinos($id_aluno)
    {
        $stmt = $this->link->prepare(
            "SELECT data_criacao FROM plano_de_treino
             WHERE id_aluno = :id_aluno
             ORDER BY data_criacao DESC
             LIMIT 1"
        );
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca todos os exercícios cadastrados.
     */
    public function getExercicios()
    {
        try {
            $stmt = $this->link->prepare("SELECT id, exercicio AS nome_exercicio, grupo_muscular FROM exercicios");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar exercícios: " . $e->getMessage();
            return false;
        }

        $stmt = $this->link->prepare(
            "SELECT id, nome_exercicio, grupo_muscular FROM exercicios"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca grupos musculares distintos.
     */
    public function getGrupo_muscular()
    {
        $stmt = $this->link->prepare(
            "SELECT DISTINCT grupo_muscular FROM exercicios"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca exercícios de um grupo específico.
     */
    public function getExerciciosByGrupoMuscular($grupo_muscular)
    {

        try {
            $stmt = $this->link->prepare("SELECT id, exercicio AS nome_exercicio, grupo_muscular FROM exercicios WHERE grupo_muscular = :grupo_muscular");
            $stmt->bindParam(':grupo_muscular', $grupo_muscular);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar exercícios: " . $e->getMessage();
            return false;
        }

        $stmt = $this->link->prepare(
            "SELECT id, nome_exercicio, grupo_muscular FROM exercicios
             WHERE grupo_muscular = :grupo_muscular"
        );
        $stmt->bindParam(':grupo_muscular', $grupo_muscular, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca exercício por ID.
     */
    public function getExerciciosById($id_exercicio)
    {
        $stmt = $this->link->prepare(
            "SELECT * FROM exercicios WHERE id = :id_exercicio"
        );
        $stmt->bindParam(':id_exercicio', $id_exercicio, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Monta e retorna os dados estruturados para gerar o PDF do treino.
     */
    public function getPlanoParaPdf(int $alunoId): array
    {
        // Tenta buscar treino no banco
        $idArr = $this->getIdTreinoCriado($alunoId);
        if (!empty($idArr['id_treino_criado'])) {
            $idTreino = $idArr['id_treino_criado'];
            $treinos = $this->getTreinoByIdTreino($idTreino);

            // Corrige o nome dos exercícios baseado no ID
            foreach ($treinos as &$treino) {
                $exercicioData = $this->getExerciciosById($treino['nome_exercicio']);
                if (!empty($exercicioData)) {
                    $treino['nome_exercicio'] = $exercicioData[0]['exercicio'];
                }
            }

            $dataArr = $this->getDataTreinos($alunoId);
            $dataCri = $dataArr['data_criacao'] ?? date('Y-m-d H:i:s');
            $user = new Users();
            $aluno = $user->getAlunoById($alunoId) ?? ['id' => $alunoId, 'username' => 'Aluno'];
        } else {
            // Fallback pré-montado
            $aluno = ['id' => $alunoId, 'nome' => 'Aluno de Exemplo'];
            $dataCri = date('Y-m-d H:i:s');
            $treinos = [
                ['letra_treino' => 'A', 'num_exercicio' => 1, 'nome_exercicio' => 'Agachamento', 'series' => 3, 'repeticoes' => '12', 'observacao' => 'Coluna neutra'],
                ['letra_treino' => 'A', 'num_exercicio' => 2, 'nome_exercicio' => 'Flexão', 'series' => 3, 'repeticoes' => '15', 'observacao' => 'Corpo alinhado'],
                ['letra_treino' => 'B', 'num_exercicio' => 1, 'nome_exercicio' => 'Remada', 'series' => 3, 'repeticoes' => '10', 'observacao' => 'Sem balanço'],
                ['letra_treino' => 'B', 'num_exercicio' => 2, 'nome_exercicio' => 'Abdominal', 'series' => 3, 'repeticoes' => '20', 'observacao' => 'Contraia abdômen']
            ];
        }

        return [
            'aluno' => ['id' => $aluno['id'], 'nome' => $aluno['username'] ?? $aluno['nome']],
            'data_criacao' => $dataCri,
            'observacoes_gerais' => '',
            'treinos' => $treinos,
        ];
    }
}
