<?php

class Treino
{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    public function cadastrarTreino($idAluno, $exercicio, $serie, $repeticoes, $carga, $obs, $data_criacao)
    {
        $stmt = $this->link->prepare("INSERT INTO treinos (id_aluno, exercicio, serie, repeticoes, carga, observacoes, data_criacao) 
                                      VALUES (:id_aluno, :exercicio, :serie, :repeticoes, :carga, :observacoes, :data_criacao)");

        $stmt->bindParam(':id_aluno', $idAluno);
        $stmt->bindParam(':exercicio', $exercicio);
        $stmt->bindParam(':serie', $serie);
        $stmt->bindParam(':repeticoes', $repeticoes);
        $stmt->bindParam(':carga', $carga);
        $stmt->bindParam(':observacoes', $obs);
        $stmt->bindParam(':data_criacao', $data_criacao);

        return $stmt->execute();
    }
}
