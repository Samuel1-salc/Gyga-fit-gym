<?php
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/UserInstrutor.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Users();
    $instrutor = new UserInstrutor();

    $id_instrutor = $_POST['id_instrutor'] ?? '';
    $username = htmlspecialchars($_POST['campo1'] ?? '', ENT_QUOTES, 'UTF-8');

    $idade = htmlspecialchars($_POST['campo2'] ?? '', ENT_QUOTES, 'UTF-8'); // (não usado no update)

    $idade = htmlspecialchars($_POST['campo2'] ?? '', ENT_QUOTES, 'UTF-8'); // 

    $email = htmlspecialchars($_POST['campo3'] ?? '', ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['campo4'] ?? '', ENT_QUOTES, 'UTF-8');
    $cpf = htmlspecialchars($_POST['campo5'] ?? '', ENT_QUOTES, 'UTF-8');
    $unidade = htmlspecialchars($_POST['campo6'] ?? '', ENT_QUOTES, 'UTF-8');
    $servico = htmlspecialchars($_POST['campo7'] ?? '', ENT_QUOTES, 'UTF-8');
    $typeUser = 'instrutor';

    if (empty($id_instrutor) || empty($username) || empty($email) || empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos obrigatórios!";
        header("Location: ../view/painelAdmin.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
        header("Location: ../view/painelAdmin.php");
        exit();
    }

    try {
        $editado = $instrutor->editarInstrutor($id_instrutor, $username, $email, $cpf, $unidade, $servico, $phone);
        if ($editado) {
            $_SESSION['success'] = "Instrutor atualizado com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao atualizar o instrutor.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro inesperado: " . $e->getMessage();
        error_log("Erro na edição do instrutor: " . $e->getMessage());
    }

    header("Location: ../view/painelAdmin.php");
    exit();
}
