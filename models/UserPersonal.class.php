<?php

class UserPersonal
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
    public function cadastrarPersonal($Username, $Email, $Senha, $Cpf,$unidade,$servico,$data_entrada,$data_saida, $phone, $typeUser)
    {
        
        // Criptografa a senha
        $senha_hash = password_hash($Senha, PASSWORD_DEFAULT);
        // Insere os dados no banco
        $stmt = $this->link->prepare("INSERT INTO Personais (username, email, senha, cpf,unidade,servico,data_entrada,data_saida phone, typeUser) VALUES (:username, :email, :senha, :cpf,:unidade,:servico,:data_entrada,:data_saida:phone,:typeUser)");
        $stmt->bindParam(':username', $Username);
        $stmt->bindParam(':email', $Email);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':cpf', $Cpf);
        $stmt->bindParam(':unidade', $unidade);
        $stmt->bindParam(':plano', $servico);
        $stmt->bindParam(':data_inicio', $data_entrada);
        $stmt->bindParam(':data_termino', $data_saida);
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
}
    
