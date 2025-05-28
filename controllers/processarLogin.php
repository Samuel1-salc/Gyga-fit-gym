<?php
/**
 * Script responsável por processar o login de usuários (alunos e instrutores).
 * Realiza validações do CPF recebido via POST, busca o usuário no banco de dados
 * e redireciona para o index.php que fará o roteamento com base no tipo.
 *
 * @package controllers
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Usuarios.class.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf = $_POST['cpf'] ?? '';

    $_SESSION['error'] = '';

    // Validação dos campos obrigatórios
    if (empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: http://localhost/Gyga-fit-gym/index.php?page=telaLogin");
        exit();
    }

    // Validação do tamanho do CPF
    if (strlen($cpf) != 11 || !ctype_digit($cpf)) {
        $_SESSION['error'] = "CPF inválido!";
        header("Location: http://localhost/Gyga-fit-gym/index.php?page=telaLogin");
        exit();
    }

    $usuarios = new Users();

    // Busca usuário por CPF (aluno ou instrutor)
    $user = $usuarios->getDataAlunoByCpf($cpf);
    if (empty($user)) {
        $user = $usuarios->getDataPersonalByCpf($cpf);
    }

    if (empty($user)) {
        $_SESSION['error'] = "Usuário não encontrado!";
        header("Location: http://localhost/Gyga-fit-gym/index.php?page=telaLogin");
        exit();
    }

    // Usuário encontrado → salva na sessão
    $_SESSION['usuario'] = $user;

    // Redireciona para o index principal (que faz o roteamento)
    header("Location: http://localhost/Gyga-fit-gym/index.php");
    exit();
}
?>
