<?php

class SolicitacaoTreino
{
    private $link;
    private $con;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    public function SolicitarTreino($data_created,$id_aluno, $experiencia, $objetivo, $treinos, $sexo, $peso, $altura)
    {
        try {
            $stmt = $this->link->prepare("
                INSERT INTO formulario (data_created,id_aluno, experiencia, objetivo, treinos, sexo, peso, altura)
                VALUES (:data_created,:id_aluno, :experiencia, :objetivo, :treinos, :sexo, :peso, :altura)
            ");

            $stmt->bindParam(':data_created', $data_created);
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':experiencia', $experiencia);
            $stmt->bindParam(':objetivo', $objetivo);
            $stmt->bindParam(':treinos', $treinos);
            $stmt->bindParam(':sexo', $sexo);
            $stmt->bindParam(':peso', $peso);
            $stmt->bindParam(':altura', $altura);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo "Erro ao solicitar treino: " . $e->getMessage();
            return false;
        }
    }

 

    public function getSolicitacaoTreino($id_aluno)
    {
        if(!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if(empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT * FROM formulario WHERE id_aluno = :id_aluno
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetchALL(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitação de treino: " . $e->getMessage();
            return false;
        }
    }
    public function getFormularioForCriacaoDeTreino($id_aluno)
    {
        if(!is_numeric($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno deve ser um número.");
        }
        if(empty($id_aluno)) {
            throw new InvalidArgumentException("ID do aluno não pode ser vazio.");
        }
        try {
            $stmt = $this->link->prepare("
                SELECT * FROM formulario WHERE id_aluno = :id_aluno ORDER BY id DESC LIMIT 1
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao buscar solicitação de treino: " . $e->getMessage();
            return false;
        }
    }
    public function contarSolicitacoesTreino($id_aluno)
    {
        try {
            $stmt = $this->link->prepare("
                SELECT COUNT(*) as total FROM formulario WHERE id_aluno = :id_aluno
            ");
            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            echo "Erro ao contar solicitações de treino: " . $e->getMessage();
            return 0;
        }
    }
}
?>
