<?php
class Gerente {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "gyga_fit");
    }

    public function autenticar($cpf, $senhaDigitada) {
        $stmt = $this->conn->prepare("SELECT * FROM gerentes WHERE cpf = ?");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();
        $gerente = $result->fetch_assoc();

        if ($gerente && password_verify($senhaDigitada, $gerente['senha'])) {
            return $gerente;
        }

        return false;
    }
}
?>