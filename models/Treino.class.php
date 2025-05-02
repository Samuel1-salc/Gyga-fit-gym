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

    public function cadastrarTreino($id_instrutor,$id_aluno,$id_treino_criado, $grupo_treino, $exercicio, $nome_exercicio, $series, $repeticoes,$observacao, $data_criacao)
    {
        $stmt = $this->link->prepare("INSERT INTO plano_de_treino (id_instrutor,id_aluno, id_treino_criado, grupo_treino,exercicio,nome_exercicio,series,repeticoes,observacao,data_criacao)
        VALUES (:id_instrutor, :id_aluno, :id_treino_criado, :grupo_treino, :exercicio, :nome_exercicio, :series, :repeticoes, :observacao, :data_criacao)"); 
        $stmt->bindParam(':id_instrutor', $id_instrutor);
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->bindParam(':id_treino_criado', $id_treino_criado);
        $stmt->bindParam(':grupo_treino', $grupo_treino);
        $stmt->bindParam(':exercicio', $exercicio);
        $stmt->bindParam(':nome_exercicio', $nome_exercicio);
        $stmt->bindParam(':series', $series);
        $stmt->bindParam(':repeticoes', $repeticoes);
        $stmt->bindParam(':observacao', $observacao);
        $stmt->bindParam(':data_criacao', $data_criacao);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }
}
