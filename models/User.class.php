<?php

class User
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
    public function cadastrar($Email, $Username, $Cpf, $Senha, $Confirm_password)
    {
        // Criptografa a senha
        $senha_hash = password_hash($Senha, PASSWORD_DEFAULT);

        // Insere os dados no banco
        $stmt = $this->link->prepare("INSERT INTO usuarios (username, email, senha, cpf) VALUES (:Username, :Email, :Senha, :Cpf)");
        $stmt->bindParam(':Username', $Username);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Senha', $senha_hash);
        $stmt->bindParam(':Cpf', $Cpf);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }

    // Verifica se o email já está cadastrado
    public function checkEmailExists($Email)
    {
        $stmt = $this->link->prepare("SELECT * FROM usuarios WHERE email = :Email");
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? true : false;
    }

    // Verifica se o cpf já está cadastrado
    public function checkCpfExists($Cpf)
    {
        $stmt = $this->link->prepare("SELECT * FROM usuarios WHERE cpf = :Cpf");
        $stmt->bindParam(':Cpf', $Cpf);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? true : false;
    }

    // Busca os dados do usuário pelo email
    public function getDataUser($Email)
    {
        $stmt = $this->link->prepare("SELECT id, username, email, senha, phone FROM usuarios WHERE email = :Email");
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();
        $dadosUser = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dadosUser[] = $row;
        }
        return $dadosUser;
    }

    // Busca os dados do usuário pelo email
    public function getUserByEmail($Email)
    {
        $stmt = $this->link->prepare("SELECT * FROM usuarios WHERE email = :Email");
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca os dados do usuário pelo CPF
    public function getUserByCpf($Cpf)
    {
        // Busca os dados do usuário pelo CPF
        $stmt = $this->link->prepare("SELECT * FROM usuarios WHERE cpf = :Cpf");
        $stmt->bindParam(':Cpf', $Cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}