<?php
session_start();
require_once './../config/database.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($cpf) || empty($senha)) {
        echo "Preencha todos os campos.";
        exit;
    }

    try {
        $db = new Database();
        $conn = $db->getConexao();

        $sql = "SELECT * FROM gerente WHERE cpf = :cpf ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'typeUser' => 'gerente'
            ];

            header("Location: http://localhost/Gyga-fit-gym/index.php?page=painelAdministrativo");
            exit(); // <- fundamental para que o redirecionamento funcione corretamente
        } else {
            echo "CPF ou senha incorretos.";
        }
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco: " . $e->getMessage();
    }
} else {
    echo "Método inválido.";
}
