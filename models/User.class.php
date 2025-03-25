<?php


class User{
    private $con;
    private $link;
  
    public function __construct() {
        require_once __DIR__ . './../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }
    // Cadastra um novo usuário
    // Recebe os dados do formulário e insere no banco de dados
    public function cadastrar($Email, $Username, $Cpf, $Senha, $Confirm_password){
       

        // Verifica se as senhas coincidem
      
        // Criptografa a senha
        $senha_hash = password_hash($Senha, PASSWORD_DEFAULT);

        // Insere os dados no banco
        // as variaveis :Username, :Email, :Senha, :Cpf são placeholders
        // que serão substituidos pelos valores das variaveis $Username, $Email, $senha_hash, $Cpf
        // e impedem que haja SQL Injection
        // as variaveis que estão em minúsculo na verdade correspondem a colunas da tabela usuarios

        $stmt = $this->link->prepare("INSERT INTO usuarios (nome,email,senha,cpf) VALUES (:Username, :Email, :Senha, :Cpf)");
        $stmt->bindParam(':Username', $Username);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Senha', $senha_hash);
        $stmt->bindParam(':Cpf', $Cpf);
        if(!$stmt){
            echo "erro ao preparar a query:" .implode ('', $this->link->errorInfo());
        }
        if($stmt->execute()){
            echo "Cadastro realizado com sucesso!";
        }else{
            echo "Erro ao cadastrar: " .implode ('', $stmt->errorInfo());
        }
        
    }
    // Verifica se o email já está cadastrado
    public function checkEmailExists($Email){
       

        // Verifica se o email já está cadastrado
        // a variavel :Email é um placeholder que será substituido pelo valor da variavel $Email
        // e impede que haja SQL Injection
        $stmt = $this->link->prepare("SELECT * FROM usuarios WHERE email = :Email");
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function checkCpfExists($Cpf){
       
        // Verifica se o cpf já está cadastrado
        // a variavel :cpf é um placeholder que será substituido pelo valor da variavel $cpf
        // e impede que haja SQL Injection
        $stmt = $this->link->prepare("SELECT * FROM usuarios WHERE cpf = :Cpf");
        $stmt->bindParam(':Cpf', $Cpf);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    // Busca os dados do usuário pelo email
    public function getDataUser($Email){
        

        // Busca os dados do usuário pelo email
        // a variavel :Email é um placeholder que será substituido pelo valor da variavel $Email
        // e impede que haja SQL Injection
        $stmt = $this->link->prepare("SELECT id, username, email, senha, phone FROM users WHERE email = :Email");
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();
        $dadosUser = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dadosUser[] = $row;
        }
        return $dadosUser;
    }

    public function getUserByEmail($Email){
       

        // Busca os dados do usuário pelo email
        // a variavel :Email é um placeholder que será substituido pelo valor da variavel $Email
        // e impede que haja SQL Injection
        $stmt = $this->link->prepare("SELECT * FROM usuarios WHERE email = :Email");
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }



}


      



    