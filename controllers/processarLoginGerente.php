<?php
session_start();
require_once '../models/Usuarios.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = trim($_POST['cpf'] ?? '');

    if (empty($cpf)) {
        $_SESSION['erro'] = "Informe o CPF.";
        header("Location: ../views/loginGerente.php");
        exit;
    }

    $users = new Users();
    $gerente = $users->getDataGerenteByCpf($cpf);

    if ($gerente) {
        $_SESSION['usuario_id'] = $gerente['id'];
        $_SESSION['usuario_nome'] = $gerente['nome'];
        $_SESSION['tipo'] = 'gerente';

        header("Location: ../views/painelAdministrativo.php");
    } else {
        $_SESSION['erro'] = "CPF não encontrado como gerente.";
        header("Location: ../views/loginGerente.php");
    }
    exit;
} else {
    header("Location: ../views/loginGerente.php");
    exit;
}
