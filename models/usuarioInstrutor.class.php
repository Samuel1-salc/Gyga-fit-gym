<?php
require_once __DIR__ . '/../config/database.class.php';
require_once __DIR__ . '/usuarios.class.php';

/**
 * Classe responsável pelas operações relacionadas ao relacionamento entre alunos e instrutores.
 */
class aluno_instrutor
{
    private $con;
    private $link;

    public function __construct()
    {
        $this->con = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
     * Retorna todos os instrutores cadastrados no sistema.
     *
     * @return array [ ['id'=>…, 'username'=>…, 'email'=>…], … ]
     */
    public function getAllInstrutores()
    {
        $stmt = $this->link->prepare("SELECT id, username, email FROM instrutor");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca instrutores pelo nome (ou parte do nome).
     *
     * @param string $nome
     * @return array
     */
    public function getInstrutoresByNome($nome)
    {
        $stmt = $this->link->prepare(
            "SELECT id, username, email
             FROM instrutor
             WHERE username LIKE :nome"
        );
        $like = "%{$nome}%";
        $stmt->bindParam(':nome', $like, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna todos os alunos vinculados a um instrutor.
     *
     * @param int $id_instrutor
     * @return array
     */
    public function getAlunosByIdInstrutor($id_instrutor)
    {
        if (!is_numeric($id_instrutor)) {
            throw new InvalidArgumentException("ID do instrutor deve ser um número.");
        }
        if (empty($id_instrutor)) {
            throw new InvalidArgumentException("ID do instrutor não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT ai.nome_aluno, ai.id_aluno, ai.data_solicitacao, ai.contato_aluno, ai.processo, f.*
                FROM aluno_instrutor ai
                LEFT JOIN formulario f ON ai.id_Aluno = f.id_aluno
                WHERE ai.id_instrutor = :id_instrutor
                ORDER BY ai.id_Aluno, f.data_created DESC
            ");
            $stmt->bindParam(':id_instrutor', $id_instrutor);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar alunos e formulários do instrutor: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Conta quantos alunos um instrutor atende.
     *
     * @param int $id_instrutor
     * @return int
     */
    public function quantidadeAlunosAtendidos($id_instrutor)
    {
        $stmt = $this->link->prepare(
            "SELECT COUNT(*) as total FROM aluno_instrutor WHERE id_instrutor = :id_instrutor"
        );
        $stmt->bindParam(':id_instrutor', $id_instrutor, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Retorna data de solicitação atual formatada.
     *
     * @return string
     */
    public function dataDeSolicitacao()
    {
        return (new DateTime())->format('Y-m-d H:i:s');
    }

    /**
     * Adiciona relação aluno–instrutor.
     */
    public function adicionarAluno_Instrutor($id_aluno, $processo, $data_solicitacao)
    {
        session_start();
        $user  = new Users();
        $aluno = $user->getAlunoById($id_aluno);

        $stmt = $this->link->prepare(
            "INSERT INTO aluno_instrutor
             (id_Aluno, nome_aluno, contato_aluno, data_solicitacao, processo, id_instrutor, nome_instrutor)
             VALUES
             (:id_aluno, :nome_aluno, :contato_aluno, :data_solicitacao, :processo, :id_instrutor, :nome_instrutor)"
        );
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt->bindParam(':nome_aluno', $aluno['username'], PDO::PARAM_STR);
        $stmt->bindParam(':contato_aluno', $aluno['email'], PDO::PARAM_STR);
        $stmt->bindParam(':data_solicitacao', $data_solicitacao, PDO::PARAM_STR);
        $stmt->bindParam(':processo', $processo, PDO::PARAM_STR);
        $stmt->bindParam(':id_instrutor', $_SESSION['usuario']['id'], PDO::PARAM_INT);
        $stmt->bindParam(':nome_instrutor', $_SESSION['usuario']['username'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Atualiza status de processo de aluno.
     */
    public function adcStatus($processo, $id_aluno)
    {
        $stmt = $this->link->prepare(
            "UPDATE aluno_instrutor SET processo = :processo WHERE id_Aluno = :id_aluno"
        );
        $stmt->bindParam(':processo', $processo, PDO::PARAM_STR);
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Busca alunos pelo nome para painel do instrutor.
     */
    public function getNameAlunoForPainelInstrutor($nome_aluno)
    {
        $stmt = $this->link->prepare(
            "SELECT * FROM aluno_instrutor WHERE nome_aluno LIKE :nome_aluno"
        );
        $like = "%{$nome_aluno}%";
        $stmt->bindParam(':nome_aluno', $like, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca aluno específico por ID para painel.
     */
    public function getAlunosByIdAlunosForPainelInstrutor($id_aluno)
    {
        $stmt = $this->link->prepare(
            "SELECT * FROM aluno_instrutor WHERE id_Aluno = :id_aluno"
        );
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica relacionamento aluno–instrutor.
     */
    public function checkRelationshipUsers($id_aluno)
    {
        $stmt = $this->link->prepare(
            "SELECT id_instrutor, nome_instrutor 
             FROM aluno_instrutor 
             WHERE id_Aluno = :id_aluno 
             LIMIT 1"
        );
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza processo de treino.
     */
    public function updateProcesso($id_aluno, $processo)
    {
        $stmt = $this->link->prepare(
            "UPDATE aluno_instrutor SET processo = :processo WHERE id_Aluno = :id_aluno"
        );
        $stmt->bindParam(':processo', $processo, PDO::PARAM_STR);
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
