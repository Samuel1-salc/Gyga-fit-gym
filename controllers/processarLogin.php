<?php
session_start();
require_once __DIR__ . '/../models/User.class.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Validações
    if (empty($cpf) || empty($senha)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        //header("Location: ../view/telaPrincipal.php");
        exit();
    }

    if (strlen($cpf) != 11) {
        $_SESSION['error'] = "CPF inválido!";
        //header("Location: ../view/telaPrincipal.php");
        exit();
    }

    $usuarios = new User();
    $user = $usuarios->getUserByCpf($cpf);
   
    // Verificação de usuário e senha
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario'] = $user;
        $veryfyFirstLogin = $usuarios->firstLogin($user['id']);
        $_SESSION['firstLogin'] = $veryfyFirstLogin;
        if(empty($_SESSION['firstLogin']['id_user'])){
            header("Location: ./../view/paginaFormulario.php");
            echo "Primeiro login, preencha o formulário!";
           
        }else{
            header("Location: ./../view/telaPrincipal.php");
            
        }
        echo "Login efetuado com sucesso!";
      
       
    } else {
        $_SESSION['error'] = "Senha incorreta ou usuário não encontrado!";
        echo "Senha incorreta ou usuário não encontrado!";
        
    }
}