<?php
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/UserAluno.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Users();
    $cadastrarAluno = new UserAluno();

    $username = $_POST['campo1'] ?? '';
    $idade = $_POST['campo2'] ?? '';
    $email = $_POST['campo3'] ?? '';
    $phone = $_POST['campo4'] ?? '';
    $cpf = $_POST['campo5'] ?? '';
    $unidade = $_POST['campo6'] ?? '';
    $plano = $_POST['campo7'] ?? '';
    $data_inicio = $cadastrarAluno->dataInicio();
    $data_termino = $cadastrarAluno->dataTermino($data_inicio,$plano);
    $typeUser = 'aluno'; 



    // Validações
    if (empty($email) || empty($username) || empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        //exit();

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
       //exit();

    } elseif ($usuario->chekByEmail($email,$typeUser)) {
        $_SESSION['error'] = "Este email já está cadastrado!";
        //exit();

    } elseif ($usuario->checkByCpf($cpf, $typeUser)) {
        $_SESSION['error'] = "Este CPF já está cadastrado!";
        //exit();

    } elseif ($usuario->checkByPhone($phone,$typeUser)) {
        $_SESSION['error'] = "Este telefone já está cadastrado!";
        //exit();
    }
    else {
        // Cadastro
        if ($cadastrarAluno->cadastrarAluno($username, $email, $cpf,$unidade,$plano,$data_inicio,$data_termino, $phone, $typeUser)) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: ../view/telaLogin.php");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao cadastrar!";
        }
    }
    
   header("Location: ../view/telaLogin.php");
}