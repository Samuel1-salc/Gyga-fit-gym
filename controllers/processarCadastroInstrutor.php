<?php
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/UserInstrutor.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Users();
    $cadastrarPersonal = new  UserInstrutor();

    $username = $_POST['campo1'] ?? '';
    $idade = $_POST['campo2'] ?? '';
    $email = $_POST['campo3'] ?? '';
    $phone = $_POST['campo4'] ?? '';
    $cpf = $_POST['campo5'] ?? '';
    $unidade = $_POST['campo6'] ?? '';
    $servico = $_POST['campo7'] ?? '';
    $data_entrada = $cadastrarPersonal->dataInicio();
    $data_saida = "";
    $typeUser = "instrutor"; // Definindo o tipo de usuário como instrutor




    // Validações
    if (empty($email) || empty($username) || empty($cpf) ) {
        $_SESSION['error'] = "Preencha todos os campos!";
        
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
       
        
        exit();
    } elseif (strlen($cpf) !== 11) {
        $_SESSION['error'] = "CPF inválido!";
       
       
        exit();
    }  elseif ($usuario->chekByEmail($email,$typeUser)) {
        $_SESSION['error'] = "Este email já está cadastrado!";
       
        
        exit();
    } elseif ($usuario->checkByCpf($cpf, $typeUser)) {
        $_SESSION['error'] = "Este CPF já está cadastrado!";
        exit();
    } elseif ($usuario->checkByPhone($phone,$typeUser)) {
        $_SESSION['error'] = "Este telefone já está cadastrado!";
        exit();
    }
    else {
        // Cadastro
        if ($cadastrarPersonal->cadastrarInstrutor($username, $email, $cpf,$unidade,$servico,$data_entrada,$data_saida, $phone, $typeUser)) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: ../view/telaLogin.php");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao cadastrar!";
        }
    }
    
   header("Location: ../view/telaLogin.php");
}