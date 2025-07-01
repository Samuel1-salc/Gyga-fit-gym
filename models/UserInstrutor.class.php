<?php

/**
 * Classe responsável pelas operações relacionadas ao cadastro e edição de instrutores.
 */
class UserInstrutor
{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = Database::getInstance();
        $this->link = $this->con->getConexao();
    }

    /**
     * Cadastra um novo instrutor.
     */
    public function cadastrarInstrutor($Username, $Email, $Cpf, $unidade, $servico, $data_entrada, $data_saida, $phone, $typeUser)
    {
        $data_saida = empty($data_saida) ? null : $data_saida;

        $stmt = $this->link->prepare("INSERT INTO instrutor 
            (username, email, cpf, unidade, servico, data_entrada, data_saida, phone, typeUser) 
            VALUES 
            (:username, :email, :cpf, :unidade, :servico, :data_entrada, :data_saida, :phone, :typeUser)");

        $stmt->bindValue(':username', $Username);
        $stmt->bindValue(':email', $Email);
        $stmt->bindValue(':cpf', $Cpf);
        $stmt->bindValue(':unidade', $unidade);
        $stmt->bindValue(':servico', $servico);
        $stmt->bindValue(':data_entrada', $data_entrada);
        $stmt->bindValue(':data_saida', $data_saida, $data_saida === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':typeUser', $typeUser);

        return $stmt->execute();
    }

    /**
     * Edita os dados de um instrutor existente.
     */
    public function editarInstrutor($id, $username, $email, $cpf, $unidade, $servico, $phone)
    {
        try {
            $stmt = $this->link->prepare("
                UPDATE instrutor SET 
                    username = :username,
                    email = :email,
                    cpf = :cpf,
                    unidade = :unidade,
                    servico = :servico,
                    phone = :phone
                WHERE id = :id
            ");

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':cpf', $cpf);
            $stmt->bindValue(':unidade', $unidade);
            $stmt->bindValue(':servico', $servico);
            $stmt->bindValue(':phone', $phone);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao editar instrutor: " . $e->getMessage());
            return false;
        }
    }


    public function dataInicio()
    {
        $data = new DateTime();
        return $data->format('Y-m-d H:i:s');
    }
}
