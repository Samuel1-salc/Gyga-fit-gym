<?php

/**
 * Classe responsável pelas operações relacionadas ao cadastro de academias.
 */
class UserAcademia
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
     * Construtor da classe UserAcademia.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
<<<<<<< Updated upstream
     * Cadastra uma nova academia no banco de dados.
=======
>>>>>>> Stashed changes
     *
     * @param string $nome Nome da unidade.
     * @param int $capacidade Capacidade máxima de alunos.
     * @param int $alunosAtivos Número de alunos ativos atualmente.
     * @param int $totalPersonais Número total de personais da unidade.
     * @return bool True em caso de sucesso, False em caso de erro.
     */
    public function cadastrarAcademia($nome, $capacidade, $alunosAtivos, $totalPersonais)
    {
        try {
            $stmt = $this->link->prepare("
                INSERT INTO academia (nome, capacidade, alunos_ativos, total_personais)
                VALUES (:nome, :capacidade, :alunos_ativos, :total_personais)
            ");

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':capacidade', $capacidade);
            $stmt->bindParam(':alunos_ativos', $alunosAtivos);
            $stmt->bindParam(':total_personais', $totalPersonais);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar academia: " . $e->getMessage());
            return false;
        }
    }
}
