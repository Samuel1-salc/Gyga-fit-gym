<?php
session_start();
require_once __DIR__ . './../models/User.class.php';
require_once __DIR__ . './../config/database.class.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cpf = trim($_POST['cpf']);
    $senha = trim($_POST['senha']);
    if($cpf == "" || $senha == ""){
        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: ../index.php");
        exit();
    }
    if(strlen($Cpf) != 11){
        $_SESSION['error'] = "CPF inválido!";
        header("Location: ../index.php");
        exit();
    }
    $usuarios = new User();
    
    $user = $usarios->getUserByCpf($cpf);
    if($user && password_verify($senha, $user['senha'])){
        if(password_verify($senha, $user['senha'])){
            $_SESSION['usuario'] = $user;
            header("Location: ../resources/paginas/telaPrincipal.php");
        }else{
            $_SESSION['error'] = "Senha incorreta!";
            header("Location: ../index.php");
            exit();
        }
    }


}
