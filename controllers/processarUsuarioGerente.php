<?php
class Gerente {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "gyga_fit");
    }

    public function verificarCpfGerente($cpf) {
        $stmt = $this->conn->prepare("SELECT * FROM gerentes WHERE cpf = ?");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();
        $gerente = $result->fetch_assoc();

        return $gerente ?: false;
    }
}
?>
