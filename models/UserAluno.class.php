<?php

/**
 * Classe responsável pelas operações relacionadas ao cadastro de alunos.
 * Permite cadastrar um novo aluno e calcular datas de início e término do plano.
 */
class UserAluno
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
        $this->con = new Database();
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
    public function cadastrarAluno($Username, $Email, $Cpf, $unidade, $plano, $data_inicio, $data_termino, $phone, $typeUser)
    {
        $stmt = $this->link->prepare("INSERT INTO aluno (username, email, cpf,unidade,plano,data_inicio,data_termino, phone, typeUser) VALUES (:username, :email,:cpf,:unidade,:plano,:data_inicio,:data_termino,:phone,:typeUser)");
        $stmt->bindParam(':username', $Username);
        $stmt->bindParam(':email', $Email);
        $stmt->bindParam(':cpf', $Cpf);
        $stmt->bindParam(':unidade', $unidade);
        $stmt->bindParam(':plano', $plano);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_termino', $data_termino);
        $stmt->bindParam(':cpf', $Cpf);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':typeUser', $typeUser);

        // Executa a query e verifica se foi bem-sucedida
        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }

    /**
     * Retorna a data e hora atual formatada para início do plano.
     *
     * @return string Data e hora atual no formato Y-m-d H:i:s.
     */
    function dataInicio()
    {
        $data = new DateTime();
        $data_formatada = $data->format('Y-m-d H:i:s');
        return $data_formatada;
    }

    /**
     * Calcula a data de término do plano com base no tipo de plano.
     *
     * @param string $data_incio Data de início do plano (Y-m-d H:i:s).
     * @param string|int $plano Tipo do plano (1 = 1 mês, 2 = 6 meses, 3 = 12 meses).
     * @return string Data de término do plano no formato Y-m-d H:i:s.
     */
    function dataTermino($data_incio, $plano)
    {
        $data_termino = '';
        if ($plano == 1) {
            $data_termino = date('Y-m-d H:i:s', strtotime($data_incio . ' + 1 month'));
        } else if ($plano == 2) {
            $data_termino = date('Y-m-d H:i:s', strtotime($data_incio . ' + 6 month'));
        } else if ($plano == 3) {
            $data_termino = date('Y-m-d H:i:s', strtotime($data_incio . ' + 12 month'));
        }
        return $data_termino;
    }
}
