<?php
require_once __DIR__ . '/../models/User.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new User();

    $email = $_POST['campo1'] ?? '';
    $username = $_POST['campo2'] ?? '';
    $cpf = $_POST['campo3'] ?? '';
    $senha = $_POST['campo4'] ?? '';
    $confirmSenha = $_POST['campo5'] ?? '';

    // Validações
    if (empty($email) || empty($username) || empty($cpf) || empty($senha) || empty($confirmSenha)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
       
        
        exit();
    } elseif (strlen($cpf) !== 11) {
        $_SESSION['error'] = "CPF inválido!";
       
       
        exit();
    } elseif (strlen($senha) < 6) {
        $_SESSION['error'] = "A senha deve ter no mínimo 6 caracteres!";
        header("Location: ../view/telaCadastro.php");
        
        exit();
    } elseif ($senha !== $confirmSenha) {
        $_SESSION['error'] = "As senhas não coincidem!";
        
        
        exit();
    } elseif ($usuario->checkEmailExists($email)) {
        $_SESSION['error'] = "Este email já está cadastrado!";
       
        
        exit();
    } elseif ($usuario->checkCpfExists($cpf)) {
        $_SESSION['error'] = "Este CPF já está cadastrado!";
        
       
        exit();
    } else {
        // Cadastro
        if ($usuario->cadastrar($email, $username, $cpf, $senha)) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: ../view/telaLogin.php");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao cadastrar!";
        }
    }
    
   header("Location: ../view/telaLogin.php");
}