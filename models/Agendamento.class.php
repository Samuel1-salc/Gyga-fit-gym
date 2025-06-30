<?php

class Agendamento {
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../config/database.class.php';
        $database = Database::getInstance();
        $this->conn = $database->getConexao();
    }

    public function criar($id_instrutor, $id_aluno, $data_hora, $observacao = '') {
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "INSERT INTO agendamentos (id_instrutor, id_aluno, data_hora, observacao) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        try {
            $result = $stmt->execute([$id_instrutor, $id_aluno, $data_hora, $observacao]);
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                die('Erro ao inserir agendamento: ' . print_r($errorInfo, true));
            }
            return $result;
        } catch (PDOException $e) {
            die('Exceção ao inserir agendamento: ' . $e->getMessage());
        }
    }

    public function listarPorAluno($id_aluno) {
        $query = "SELECT * FROM agendamentos WHERE id_aluno = ? ORDER BY data_hora DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_aluno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
