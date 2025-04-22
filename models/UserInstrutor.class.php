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
    $stmt = $this->link->prepare("INSERT INTO instrutor 
        (username, email, cpf, unidade, servico, data_entrada, data_saida, phone, typeUser) 
        VALUES 
        (:username, :email, :cpf, :unidade, :servico, :data_entrada, :data_saida, :phone, :typeUser)");

    $stmt->bindParam(':username', $Username);
    $stmt->bindParam(':email', $Email);
    $stmt->bindParam(':cpf', $Cpf);
    $stmt->bindParam(':unidade', $unidade);
    $stmt->bindParam(':servico', $servico);
    $stmt->bindParam(':data_entrada', $data_entrada);
    $stmt->bindParam(':data_saida', $data_saida);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':typeUser', $typeUser);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . implode(' | ', $stmt->errorInfo());
    }
}

    function dataInicio(){
        $data = new DateTime();
        $data_formatada = $data->format('Y-m-d H:i:s');
        return $data_formatada;
    }
}
    
