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

    public function cadastrarTreino($id_instrutor,$id_aluno,$id_treino_criado, $letra_treino, $num_exercicio, $nome_exercicio, $series, $repeticoes,$observacao, $data_criacao)
    {
        $stmt = $this->link->prepare("INSERT INTO plano_de_treino (id_instrutor,id_aluno, id_treino_criado, letra_treino,num_exercicio,nome_exercicio,series,repeticoes,observacao,data_criacao)
        VALUES (:id_instrutor, :id_aluno, :id_treino_criado, :letra_treino, :num_exercicio, :nome_exercicio, :series, :repeticoes, :observacao, :data_criacao)"); 
        $stmt->bindParam(':id_instrutor', $id_instrutor);
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->bindParam(':id_treino_criado', $id_treino_criado);
        $stmt->bindParam(':letra_treino', $letra_treino);
        $stmt->bindParam(':num_exercicio', $num_exercicio);
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
    public function getIdTreinoCriado($id_aluno)
    {
        if(!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if(empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT id_treino_criado FROM plano_de_treino WHERE id_aluno = :id_aluno ORDER BY id DESC LIMIT 1
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitação de treino: " . $e->getMessage();
            return false;
        }
    }
}
