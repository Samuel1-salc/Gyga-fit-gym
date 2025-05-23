<?php

/**
 */

require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/UserAluno.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario    = new Users();
    $alunoModel = new UserAluno();

    $id_aluno  = $_POST['id_aluno']  ?? '';
    $username  = htmlspecialchars($_POST['campo1'] ?? '', ENT_QUOTES, 'UTF-8');
    $idade     = htmlspecialchars($_POST['campo2'] ?? '', ENT_QUOTES, 'UTF-8'); // apenas para exibição
    $email     = htmlspecialchars($_POST['campo3'] ?? '', ENT_QUOTES, 'UTF-8');
    $phone     = htmlspecialchars($_POST['campo4'] ?? '', ENT_QUOTES, 'UTF-8');
    $cpf       = htmlspecialchars($_POST['campo5'] ?? '', ENT_QUOTES, 'UTF-8');
    $unidade   = htmlspecialchars($_POST['campo6'] ?? '', ENT_QUOTES, 'UTF-8');
    $plano     = htmlspecialchars($_POST['campo7'] ?? '', ENT_QUOTES, 'UTF-8');
    $typeUser  = 'aluno';

    if (empty($id_aluno) || empty($username) || empty($email) || empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos obrigatórios!";
        header("Location: ../view/painelAdmin.php");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
        header("Location: ../view/painelAdmin.php");
        exit();
    }
    $sucesso = $alunoModel->editarAluno(
        $id_aluno,
        $username,
        $email,
        $cpf,
        $unidade,
        $plano,
        $phone
    );

    if ($sucesso) {
        $_SESSION['success'] = "Dados do aluno atualizados com sucesso!";
    } else {
        $_SESSION['error']   = "Erro ao atualizar o aluno.";
    }

    header("Location: ../view/painelAdmin.php");
    exit();
}
