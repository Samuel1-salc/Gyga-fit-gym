<?php

/**
 * Classe responsável pelas operações relacionadas ao cadastro de alunos.
 * Permite cadastrar um novo aluno e calcular datas de início e término do plano.
 */
class userGerente
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
     * Construtor da classe UserAluno.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
     * Cadastra um novo aluno no banco de dados.
     *
     * @param string $Username Nome do aluno.
     * @param string $Email Email do aluno.
     * @param string $Cpf CPF do aluno.
     * @param string $unidade Unidade da academia.
     * @param string|int $plano Tipo do plano (1, 2 ou 3).
     * @param string $data_inicio Data de início do plano (Y-m-d H:i:s).
     * @param string $data_termino Data de término do plano (Y-m-d H:i:s).
     * @param string $phone Telefone do aluno.
     * @param string $typeUser Tipo de usuário (ex: 'aluno').
     * @return void
     */
    public function cadastrarGerente($nome, $cpf, $senha)
    {
        $stmt = $this->link->prepare("INSERT INTO gerente (nome,cpf,senha) VALUES (:nome, :cpf, :senha)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':senha', $senha);
        // Executa a query e verifica se foi bem-sucedida
        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }
}
