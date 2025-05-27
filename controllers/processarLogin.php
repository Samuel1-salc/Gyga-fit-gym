<?php
session_start();
require_once __DIR__ . '/../models/Usuarios.class.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'] ?? '';

    $_SESSION['error'] = '';

    // Validação
    if (empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: ../view/login.php");
        exit();
    }

    if (strlen($cpf) != 11) {
        $_SESSION['error'] = "CPF inválido!";
        header("Location: ../view/login.php");
        exit();
    }

    $usuarios = new Users();

    // Verifica gerente
    if (!empty($usuarios->getDataGerenteByCpf($cpf))) {
        $user = $usuarios->getDataGerenteByCpf($cpf);
        $user['typeUser'] = 'gerente';
    }
    // Verifica aluno
    else if (!empty($usuarios->getDataAlunoByCpf($cpf))) {
        $user = $usuarios->getDataAlunoByCpf($cpf);
        $user['typeUser'] = 'aluno';
    }
    // Verifica instrutor
    else if (!empty($usuarios->getDataPersonalByCpf($cpf))) {
        $user = $usuarios->getDataPersonalByCpf($cpf);
        $user['typeUser'] = 'instrutor';
    }
    else {
        $_SESSION['error'] = "Usuário não encontrado!";
        header("Location: ../view/login.php");
        exit();
    }

    // Armazena usuário e redireciona
    $_SESSION['usuario'] = $user;

    switch ($user['typeUser']) {
        case 'aluno':
            header("Location: ../view/telaPrincipal.php");
            break;
        case 'instrutor':
            header("Location: ../view/perfilInstrutor.php");
            break;
        case 'gerente':
            header("Location: ../view/painelAdministrativo"); 
            break;
    }

    exit();
}
