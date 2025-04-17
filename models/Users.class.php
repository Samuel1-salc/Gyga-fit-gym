<?php

class Users{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        require_once __DIR__ . '/models/User.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

 
    public function getAlunoById($id){
        $stmt = $this->link->prepare("SELECT * FROM Alunos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
    public function getDataAlunoByCpf($cpf){
        $stmt = $this->link->prepare("SELECT * FROM Alunos WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }   
    public function getDataPersonalByCpf($cpf){
        $stmt = $this->link->prepare("SELECT * FROM Personais WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function checkByCpf($cpf, $typeUser){
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM Alunos WHERE cpf = :cpf LIMIT 1");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM Personais WHERE cpf = :cpf LIMIT 1");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function chekByEmail($email, $typeUser){
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM Alunos WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM Personais WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
       
    }

    public function checkByPhone($phone,$typeUser){
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM Alunos WHERE phone = :phone LIMIT 1");
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM Personais WHERE phone = :phone LIMIT 1");
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
    public function firstLogin($id){
        //formulario é a tabela no banco de dados
        $stmt = $this->link->prepare("SELECT * FROM formulario WHERE id_user = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

  
}