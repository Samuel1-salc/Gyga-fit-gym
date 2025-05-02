<?php

class UserInstrutor
{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    // Cadastra um novo usuário
    // Recebe os dados do formulário e insere no banco de dados
    public function cadastrarInstrutor($Username, $Email, $Cpf, $unidade, $servico, $data_entrada, $data_saida, $phone, $typeUser)
    {
        // Se data_saida estiver vazia, converte para null
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

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode(' | ', $stmt->errorInfo());
        }
    }

    // Retorna a data e hora atual formatada
    public function dataInicio()
    {
        $data = new DateTime();
        return $data->format('Y-m-d H:i:s');
    }
}
