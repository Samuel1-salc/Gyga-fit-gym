<?php

class Users{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    public function getDataAlunosForPerfilAlunos(){
        $stmt = $this->link->prepare("SELECT id,username, email, plano FROM aluno");
        $stmt->execute();
        $tabela =  $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $tabela;
    }
    public function getDataAlunosByNome($nameSearch){
        $stmt = $this->link->prepare("SELECT id,username, email, plano FROM aluno WHERE username LIKE :nameSearch");
        $nameSearch = "%$nameSearch%";
        $stmt->bindParam(':nameSearch', $nameSearch, PDO::PARAM_STR);
        $stmt->execute();
        return  $stmt->fetchAll(PDO::FETCH_ASSOC); 
        
    }

    public function getAlunosByInstrutor($id_instrutor){
        $stmt = $this->link->prepare("SELECT nome_aluno, contato_aluno, data_solicitacao, processo FROM aluno_instrutor WHERE id_instrutor = :id_instrutor");
        $stmt->bindParam(':id_instrutor', $id_instrutor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function getAlunosINSTRUTOR($id_instrutor){
        $stmt = $this->link->prepare("SELECT id_aluno, nome_aluno, contato_aluno, data_solicitacao, processo FROM aluno_instrutor WHERE id_instrutor = :id_instrutor");
        $stmt->bindParam(':id_instrutor', $id_instrutor);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getNomePersonalByAluno($id_aluno){
        $stmt = $this->link->prepare("SELECT nome_instrutor  FROM aluno_instrutor WHERE id_Aluno = :id_aluno");
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function getIPersonalByAluno($id_aluno){
        $stmt = $this->link->prepare("SELECT id_instrutor  FROM aluno_instrutor WHERE id_Aluno = :id_aluno");
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
    public function getAlunoById($id){
        $stmt = $this->link->prepare("SELECT * FROM aluno WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
    public function getDataAlunoByCpf($cpf){
        $stmt = $this->link->prepare("SELECT * FROM aluno WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }   
    public function getDataPersonalByCpf($cpf){
        $stmt = $this->link->prepare("SELECT * FROM instrutor WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function checkByCpf($cpf, $typeUser){
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM aluno WHERE cpf = :cpf LIMIT 1");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM instrutor WHERE cpf = :cpf LIMIT 1");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function chekByEmail($email, $typeUser){
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM aluno WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM instrutor WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
       
    }

    public function checkByPhone($phone,$typeUser){
        if($typeUser == 1){
            $stmt = $this->link->prepare("SELECT 1 FROM aluno WHERE phone = :phone LIMIT 1");
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        }else if($typeUser == 2){
            $stmt = $this->link->prepare("SELECT 1 FROM instrutor WHERE phone = :phone LIMIT 1");
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

    public function printDadosUser($id){
        $stmt = $this->link->prepare("SELECT * FROM aluno WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $tabela =  $stmt->fetch(PDO::FETCH_ASSOC); 
        return $tabela;
    }

  
}