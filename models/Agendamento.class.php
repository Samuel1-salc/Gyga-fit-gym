<?php

class Agendamento {
    private $id;
    private $id_aluno;
    private $data_hora;
    private $observacao;
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../config/database.class.php';
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function criar($id_aluno, $data_hora, $observacao = '') {
        $query = "INSERT INTO agendamentos (id_aluno, data_hora, observacao) VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bind_param("iss", $id_aluno, $data_hora, $observacao);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function listarPorAluno($id_aluno) {
        $query = "SELECT * FROM agendamentos WHERE id_aluno = ? ORDER BY data_hora DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_aluno);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $agendamentos = [];
        while($row = $result->fetch_assoc()) {
            $agendamentos[] = $row;
        }
        
        return $agendamentos;
    }
}
