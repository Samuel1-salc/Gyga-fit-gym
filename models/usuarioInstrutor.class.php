<?php

require_once __DIR__ . "/usuarios.class.php";
require_once __DIR__ . "/../config/database.class.php";



class aluno_instrutor{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
        
    }

    public function adcStatus($processo,$id_aluno){
        $stmt = $this->link->prepare("UPDATE aluno_instrutor SET processo = :processo WHERE id_Aluno = :id_aluno");
        $stmt->bindParam(':processo', $processo);
        $stmt->bindParam(':id_aluno', $id_aluno);
        if ($stmt->execute()) {
            echo "Status atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar status: " . implode('', $stmt->errorInfo());
        }
    }

   public function adicionarAluno_Instrutor($id_aluno,$processo,$data_solicitacao){
    session_start();
    $user = new Users();
    $aluno = $user->getAlunoById($id_aluno);
    $stmt = $this->link->prepare("INSERT INTO aluno_instrutor (id_Aluno, nome_aluno,contato_aluno,data_solicitacao, processo, id_instrutor, nome_instrutor ) VALUES (:id_aluno, :nome_aluno,:contato_aluno,:data_solicitacao, :processo,:id_instrutor, :nome_instrutor)");
    $stmt->bindParam(':id_aluno', $id_aluno);
    $stmt->bindParam(':nome_aluno', $aluno['username']);
    $stmt->bindParam(':contato_aluno', $aluno['email']);
    $stmt->bindParam(':data_solicitacao',$data_solicitacao );
    $stmt->bindParam(':processo', $processo);
    $stmt->bindParam(':id_instrutor', $_SESSION['usuario']['id']);
    $stmt->bindParam(':nome_instrutor', $_SESSION['usuario']['username']);
    if ($stmt->execute()) {
        echo "Cadastro em usuario_intrutor realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
    }

   }
   public function getNameAlunoForPainelInstrutor($nome_aluno){
    $stmt = $this->link->prepare("SELECT * FROM aluno_instrutor WHERE nome_aluno LIKE :nome_aluno");
    $NOME_ALUNO = "%$nome_aluno%";
    $stmt->bindParam(':nome_aluno', $NOME_ALUNO, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }
    public function getAlunosByIdInstrutor($id_instrutor){
     $stmt = $this->link->prepare("SELECT * FROM aluno_instrutor WHERE id_instrutor = :id_instrutor");
     $stmt->bindParam(':id_instrutor', $id_instrutor);
     $stmt->execute();
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlunosByIdAlunosForPainelInstrutor($id_aluno){
        
        if(!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if(empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try{
        $stmt = $this->link->prepare("SELECT * FROM aluno_instrutor WHERE id_Aluno = :id_aluno");
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo "Erro ao buscar aluno: " . $e->getMessage();
            return false;
        }
    }
   public function checkRelationshipUsers($id_aluno){
    $stmt = $this->link->prepare("SELECT id_instrutor,nome_instrutor FROM aluno_instrutor WHERE id_Aluno = :id_aluno  LIMIT 1");
    $stmt->bindParam(':id_aluno', $id_aluno);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
   }
   public function quantidadeAlunosAtendidos($id_instrutor){
    $stmt = $this->link->prepare("SELECT COUNT(*) as total FROM aluno_instrutor WHERE id_instrutor = :id_instrutor");
    $stmt->bindParam(':id_instrutor', $id_instrutor);
    $stmt->execute();
    $countAlunos = $stmt->fetchColumn();
    return $countAlunos;
   }

   public function dataDeSolicitacao(){
    $data = new DateTime();
    $data_formatada = $data->format('Y-m-d H:i:s');
    return $data_formatada;
   }

   public function updateProcesso($id_aluno,$processo){
    $stmt = $this->link->prepare("UPDATE aluno_instrutor SET processo = :processo WHERE id_Aluno = :id_aluno");
    $stmt->bindParam(':processo', $processo);
    $stmt->bindParam(':id_aluno', $id_aluno);
    if ($stmt->execute()) {
        echo "Status atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar status: " . implode('', $stmt->errorInfo());
    }
   }
}