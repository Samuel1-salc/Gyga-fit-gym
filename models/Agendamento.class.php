<?php

class Agendamento {
    private $id;
    private $id_aluno;
    private $data_hora;
    private $observacao;
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../config/database.class.php';
        $database = Database::getInstance();
        $this->conn = $database->getConexao();
    }

    public function criar($id_aluno, $id_instrutor, $data_hora, $observacao = '') {
        try {
            $query = "INSERT INTO agendamentos (id_aluno, id_instrutor, data_hora, observacao) VALUES (:id_aluno, :id_instrutor, :data_hora, :observacao)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $stmt->bindParam(':id_instrutor', $id_instrutor, PDO::PARAM_INT);
            $stmt->bindParam(':data_hora', $data_hora, PDO::PARAM_STR);
            $stmt->bindParam(':observacao', $observacao, PDO::PARAM_STR);
            if($stmt->execute()) {
                return true;
            }
            // Se não executou, exibe erro
            $errorInfo = $stmt->errorInfo();
            echo '<pre>Erro ao inserir: ' . print_r($errorInfo, true) . '</pre>';
            return false;
        } catch (PDOException $e) {
            echo '<pre>Exceção PDO: ' . $e->getMessage() . '</pre>';
            return false;
        }
    }

    public function listarPorAluno($id_aluno) {
        $query = "SELECT * FROM agendamentos WHERE id_aluno = :id_aluno ORDER BY data_hora DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
        $stmt->execute();
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $agendamentos;
    }
}
