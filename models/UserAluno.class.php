<?php

class UserAluno
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
    public function cadastrarAluno($Username, $Email, $Senha, $Cpf,$unidade,$plano,$data_inicio,$data_termino, $phone, $typeUser)
    {
        
        // Criptografa a senha
        $senha_hash = password_hash($Senha, PASSWORD_DEFAULT);
        // Insere os dados no banco
        $stmt = $this->link->prepare("INSERT INTO Alunos (username, email, senha, cpf,unidade,plano,data_nicio,data_termino, phone, typeUser) VALUES (:username, :email, :senha, :cpf,:unidade,:plano,:data_inicio,:data_termino:phone,:typeUser)");
        $stmt->bindParam(':username', $Username);
        $stmt->bindParam(':email', $Email);
        $stmt->bindParam(':senha', $senha_hash);
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
    

}
