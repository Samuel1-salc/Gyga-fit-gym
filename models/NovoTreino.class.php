<?php
require_once __DIR__ . "/usuarios.class.php";
class NovoTreino{
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
    public function enviarTreino($id_aluno,$username, $dia_da_semana, $exercicio, $series, $repeticoes, $observacoes, $data_criacao)
    {
        session_start();
        $User = new Users();
        $id_instrutor = $_SESSION['usuario']['id'];
        

        $stmt = $this->link->prepare("INSERT INTO adcionar_treino (id_aluno, username, dia_da_semana, exercicio, series, repeticoes, observacoes, data_criacao, id_instrutor) VALUES (:id_aluno, :username, :dia_da_semana,:exercicio,:series,:repeticoes,:observacoes, :data_criacao, :id_instrutor)");
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':dia_da_semana', $dia_da_semana);
        $stmt->bindParam(':exercicio', $exercicio);
        $stmt->bindParam(':series', $series);
        $stmt->bindParam(':repeticoes', $repeticoes);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':data_criacao', $data_criacao);
        $stmt->bindParam(':id_instrutor', $id_instrutor);

        // Executa a query e verifica se foi bem-sucedida


        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }
}