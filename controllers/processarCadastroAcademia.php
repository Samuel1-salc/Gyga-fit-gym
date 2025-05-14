<?php
require_once __DIR__ . '/../models/UserAcademia.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $academia = new UserAcademia();

    // Recebendo e sanitizando os dados do formulário
    $nome = htmlspecialchars($_POST['campo1'] ?? '', ENT_QUOTES, 'UTF-8');
    $capacidade = htmlspecialchars($_POST['campo2'] ?? '', ENT_QUOTES, 'UTF-8');
    $alunosAtivos = htmlspecialchars($_POST['campo3'] ?? '', ENT_QUOTES, 'UTF-8');
    $totalPersonais = htmlspecialchars($_POST['campo4'] ?? '', ENT_QUOTES, 'UTF-8');

    // Validação simples
    if (empty($nome) || empty($capacidade) || empty($alunosAtivos) || empty($totalPersonais)) {
        $_SESSION['error'] = "Preencha todos os campos obrigatórios!";
        header("Location: ../view/painelAdmin.php");
        exit();
    }

    // Cadastro da academia
    try {
        if ($academia->cadastrarAcademia($nome, $capacidade, $alunosAtivos, $totalPersonais)) {
            $_SESSION['success'] = "Academia cadastrada com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao cadastrar academia.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro inesperado: " . $e->getMessage();
        error_log("Erro ao cadastrar academia: " . $e->getMessage());
    }

    header("Location: ../view/painelAdmin.php");
    exit();
}
