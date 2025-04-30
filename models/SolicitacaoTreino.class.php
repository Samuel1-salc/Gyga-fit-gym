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

    public function SolicitarTreino($id_user, $experiencia, $objetivo, $treinos, $sexo, $peso, $altura)
    {
        try {
            $stmt = $this->link->prepare("
                INSERT INTO formulario (id_user, experiencia, objetivo, treinos, sexo, peso, altura)
                VALUES (:id_user, :experiencia, :objetivo, :treinos, :sexo, :peso, :altura)
            ");

            $stmt->bindParam(':id_user', $id_user);
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
}
?>
