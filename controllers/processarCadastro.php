<?php
require_once __DIR__ . '/../models/User.class.php';
session_start();

$usuario = new User();
// Recebe os dados do formulário
// Verifica se os campos estão preenchidos
// Verifica se o email é válido
// Verifica se o CPF é válido  
// Verifica se a senha tem no mínimo 6 caracteres
// Verifica se as senhas coincidem
// Verifica se o email já está cadastrado
// Verifica se o CPF já está cadastrado
// Cadastra o usuário
// Redireciona para a página de login

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo "recebendo dados do formulario\n";
    $Email = $_POST['campo1'];
    $Username = $_POST['campo2'];
    $Cpf = $_POST['campo3'];
    $Senha = $_POST['campo4'];
    $Confirm_password = $_POST['campo5'];

    if($Email == "" || $Username == "" || $Cpf == "" || $Senha == "" || $Confirm_password == ""){
        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: ../index.php");
        exit();
    }
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
        header("Location: ../index.php");
        exit();
    }
    if(strlen($Cpf) != 11){
        $_SESSION['error'] = "CPF inválido!";
        header("Location: ../index.php");
        exit();
    }
    if(strlen($Senha) < 6){
        $_SESSION['error'] = "A senha deve ter no mínimo 6 caracteres!";
        header("Location: ../index.php");
        exit();
    }

    if($Senha != $Confirm_password){
        $_SESSION['error'] = "As senhas não coincidem!";
        header("Location: ../index.php");
        exit();
    }
    if($usuario->checkEmailExists($Email)){
        $_SESSION['error'] = "Este email já está cadastrado!";
        header("Location: ../index.php");
        exit();
    }
    if($usuario->checkCpfExists($Cpf)){
        $_SESSION['error'] = "Este CPF já está cadastrado!";
        header("Location: ../index.php");
        exit();
    }

    // Insere os dados no banco
    if($ususario->cadastrar($Email, $Username, $Cpf, $Senha, $Confirm_password)){
        $_SESSION['success'] = "Cadastro realizado com sucesso!";
        header("Location: ../index.php");
        exit();
    }else{
        $_SESSION['error'] = "Erro ao cadastrar!";
        header("Location: ../index.php");
        exit();
    }
    
    
}


?>
