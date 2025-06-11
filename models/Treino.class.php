<?php

/**
 * Classe responsável pelas operações relacionadas ao plano de treino.
 * Inclui métodos para cadastrar treinos, buscar treinos, exercícios e grupos musculares.
 */
class Treino
{
    /**
     * @var Database $con Instância da conexão com o banco de dados.
     */
    private $con;

    /**
     * @var PDO $link Link da conexão PDO.
     */
    private $link;

    /**
     * Construtor da classe Treino.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
     * Cadastra um novo treino no banco de dados.
     *
     * @param int $id_instrutor ID do instrutor responsável.
     * @param int $id_aluno ID do aluno.
     * @param int $id_treino_criado ID do treino criado.
     * @param string $letra_treino Letra identificadora do treino (A, B, C, ...).
     * @param int $num_exercicio Número do exercício.
     * @param string $nome_exercicio Nome do exercício.
     * @param int $series Número de séries.
     * @param string $repeticoes Número de repetições.
     * @param string $observacao Observações do treino.
     * @param string $data_criacao Data de criação do treino (Y-m-d H:i:s).
     * @return void
     */
    public function cadastrarTreino($id_instrutor, $id_aluno, $id_treino_criado, $letra_treino, $num_exercicio, $nome_exercicio, $series, $repeticoes, $observacao, $data_criacao)
    {
        $stmt = $this->link->prepare("INSERT INTO plano_de_treino (id_instrutor,id_aluno, id_treino_criado, letra_treino,num_exercicio,nome_exercicio,series,repeticoes,observacao,data_criacao)
        VALUES (:id_instrutor, :id_aluno, :id_treino_criado, :letra_treino, :num_exercicio, :nome_exercicio, :series, :repeticoes, :observacao, :data_criacao)");
        $stmt->bindParam(':id_instrutor', $id_instrutor);
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->bindParam(':id_treino_criado', $id_treino_criado);
        $stmt->bindParam(':letra_treino', $letra_treino);
        $stmt->bindParam(':num_exercicio', $num_exercicio);
        $stmt->bindParam(':nome_exercicio', $nome_exercicio);
        $stmt->bindParam(':series', $series);
        $stmt->bindParam(':repeticoes', $repeticoes);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->bindParam(':data_criacao', $data_criacao);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }

    /**
     * Busca o último id_treino_criado para um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com o id_treino_criado ou false em caso de erro.
     * @throws InvalidArgumentException Se o ID do aluno for inválido.
     */
    public function getTreinoByIdTreino($id_treino_criado)
    {
        if (!is_numeric($id_treino_criado)) {
            throw new InvalidArgumentException("ID do treino deve ser um número.");
        }
        if (empty($id_treino_criado)) {
            throw new InvalidArgumentException("ID do treino não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT * FROM plano_de_treino WHERE id_treino_criado = :id_treino_criado 
            ");
            $stmt->bindParam(':id_treino_criado', $id_treino_criado);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar treinos: " . $e->getMessage();
            return false;
        }
    }



    /**
     * Busca o último id_treino_criado para um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com o id_treino_criado ou false em caso de erro.
     * @throws InvalidArgumentException Se o ID do aluno for inválido.
     */
    public function getIdTreinoCriado($id_aluno)
    {
        if (!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if (empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("SELECT id_treino_criado FROM plano_de_treino WHERE id_aluno = :id_aluno ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitação de treino: " . $e->getMessage();
            return false;
        }
    }

    public function getLetrasDotreino($id_treino_criado)
    {
        if (!is_numeric($id_treino_criado)) {
            throw new InvalidArgumentException("ID do treino deve ser um número.");
        }
        if (empty($id_treino_criado)) {
            throw new InvalidArgumentException("ID do treino não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare(" SELECT DISTINCT letra_treino FROM plano_de_treino WHERE id_treino_criado = :id_treino_criado  ");
            $stmt->bindParam(':id_treino_criado', $id_treino_criado);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar treinos: " . $e->getMessage();
            return false;
        }
    }


    /**
     * Busca a data de criação do último treino de um aluno.
     *
     * @param int $id_aluno ID do aluno.
     * @return array|false Retorna um array com a data_criacao ou false em caso de erro.
     * @throws InvalidArgumentException Se o ID do aluno for inválido.
     */
    public function getDataTreinos($id_aluno)
    {
        if (!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if (empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT data_criacao FROM plano_de_treino WHERE id_aluno = :id_aluno ORDER BY data_criacao DESC LIMIT 1
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar treinos: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca todos os exercícios cadastrados.
     *
     * @return array|false Retorna um array de exercícios ou false em caso de erro.
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
    }

    /**
     * Busca todos os grupos musculares distintos cadastrados.
     *
     * @return array|false Retorna um array de grupos musculares ou false em caso de erro.
     */
    public function getGrupo_muscular()
    {
        try {
            $stmt = $this->link->prepare("SELECT DISTINCT grupo_muscular FROM exercicios");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar exercícios: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca todos os exercícios de um grupo muscular específico.
     *
     * @param string $grupo_muscular Nome do grupo muscular.
     * @return array|false Retorna um array de exercícios ou false em caso de erro.
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
    }

    public function getExerciciosById($id_exercicio)
    {
        try {
            $stmt = $this->link->prepare("SELECT * FROM exercicios WHERE id = :id_exercicio");
            $stmt->bindParam(':id_exercicio', $id_exercicio);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar exercícios: " . $e->getMessage();
            return false;
        }
    }
}
