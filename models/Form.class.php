<?php

class Form{
    private $con;
    private $link;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.class.php';
        $this->con = new Database();
        $this->link = $this->con->getConexao();
    }

    public function getForm($altura,$peso,$sexo,$id_user){
        $stmt = $this->link->prepare("INSERT INTO formulario (altura, peso, sexo, id_user) VALUES (:altura, :peso, :sexo, :id_user)");
        $stmt->bindParam(':altura', $altura);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':id_user', $id_user);

        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
        } else {
            echo "Erro ao cadastrar: " . implode('', $stmt->errorInfo());
        }
    }
}