<?php
require_once __DIR__ . "/models/User.class.php";
require_once __DIR__ . "../config/database.class.php";

class usuario_instrutor{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

   public function adicionarAluno_Instrutor($id_aluno){
    $user = new Users();
    $aluno = $user->getAlunoById($id_aluno);
    $stmt = $this->link->prepare("INSERT INTO usuario_instrutor (id_Aluno, nome_aluno,contato_aluno,id_instrutor, nome_instrutor ) VALUES (:id_aluno, :nome_aluno,:contato_aluno, :id_instrutor, :nome_instrutor)");
    $stmt->bindParam(':id_aluno', $aluno['id']);
    $stmt->bindParam(':nome_aluno', $aluno['username']);
    $stmt->bindParam(':contato_aluno', $aluno['phone']);
    $stmt->bindParam(':id_instrutor', $_SESSION['usuario']['id']);
    $stmt->bindParam(':nome_instrutor', $_SESSION['usuario']['username']);
    if ($stmt->execute()) {
        echo "Cadastro em usuario_intrutor realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
    }

   }
    public function getAlunosByIdInstrutor($id_instrutor){
     $stmt = $this->link->prepare("SELECT * FROM usuario_instrutor WHERE id_instrutor = :id_instrutor");
     $stmt->bindParam(':id_instrutor', $id_instrutor);
     $stmt->execute();
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function checkRelationshipUsers($id_aluno, $id_instrutor){
    $stmt = $this->link->prepare("SELECT 1 FROM usuario_instrutor WHERE id_Aluno = :id_aluno AND id_instrutor = :id_instrutor LIMIT 1");
    $stmt->bindParam(':id_aluno', $id_aluno);
    $stmt->bindParam(':id_instrutor', $id_instrutor);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
   }
}