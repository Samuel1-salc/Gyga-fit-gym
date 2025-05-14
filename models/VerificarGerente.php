<?php
session_start();
require_once '/../config/database.class.php';

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

        $sql = "SELECT * FROM usuarios WHERE cpf = :cpf AND tipo = 'gerente'";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo'] = $usuario['tipo'];

            header("Location: painel_admin.php");
            exit;
        } else {
            echo "CPF ou senha incorretos.";
        }
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco: " . $e->getMessage();
    }
} else {
    echo "Método inválido.";
}
