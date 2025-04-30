<?php
session_start();
require_once __DIR__ . '/../models/Usuarios.class.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'] ?? '';

    $_SESSION['error'] = '';

    // Validações
    if (empty($cpf) ) {
        $_SESSION['error'] = "Preencha todos os campos!";
        //header("Location: ../view/telaPrincipal.php");
        exit();
    }

    if (strlen($cpf) != 11) {
        $_SESSION['error'] = "CPF inválido!";
        //header("Location: ../view/telaPrincipal.php");
        exit();
    }

    $usuarios = new Users();//!!!!!!!!!!


    if(!empty($usuarios->getDataAlunoByCpf($cpf))){
        $user = $usuarios->getDataAlunoByCpf($cpf);

    }else if(!empty($usuarios->getDataPersonalByCpf($cpf))){
        $user = $usuarios->getDataPersonalByCpf($cpf);

    }else{

        $_SESSION['error'] = "Usuário não encontrado!";
        //header("Location: ../view/telaPrincipal.php");
        exit();
    }
   
    // Verificação de usuário e senha
    if (!empty($user)) {
        $_SESSION['usuario'] = $user;

       if($user['typeUser'] == 'aluno'){
            header("Location: ../view/paginaFormulario.php");    
            exit();   
        }else if($user['typeUser'] == 'instrutor'){
            header("Location: ../view/perfilInstrutor.php");
            exit();
        }   
       
    }else {
        $_SESSION['error'] = "Senha incorreta ou usuário não encontrado!";
        echo "Senha incorreta ou usuário não encontrado!";
        
    }
    
}

