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

    public function cadastrarForm($altura,$peso,$sexo,$id_user){
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

    public function getFormById($id_user){
        $stmt = $this->link->prepare("SELECT * FROM formulario WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function enviarNotificação($contador,$instrutor){
        $stmt = $this->link->prepare("INSERT INTO notificacao (id_instrutor, $contador) VALUES (:id_user, :mensagem)");
        $stmt->bindParam(':id_user', $_SESSION['usuario']['id']);
        $stmt->bindParam(':mensagem', $contador);
        if ($stmt->execute()) {
            echo "Notificação enviada com sucesso!";
        } else {
            echo "Erro ao enviar notificação: " . implode('', $stmt->errorInfo());
        }
    }
}