<?php
/**
 * Script responsável por processar o login de usuários (alunos e instrutores).
 * Realiza validações do CPF recebido via POST, busca o usuário no banco de dados
 * e redireciona para o index.php que fará o roteamento com base no tipo.
 *
 * @package controllers
 */

session_start();
require_once __DIR__ . '/../models/Usuarios.class.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'] ?? '';

    $_SESSION['error'] = '';

    // Validação dos campos obrigatórios
    if (empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: ../index.php?page=telaLogin");
        exit();
    }

    // Validação do tamanho do CPF
    if (strlen($cpf) != 11) {
        $_SESSION['error'] = "CPF inválido!";
        header("Location: ../index.php?page=telaLogin");
        exit();
    }

    $usuarios = new Users();

    // Busca usuário por CPF (aluno ou instrutor)
    if (!empty($usuarios->getDataAlunoByCpf($cpf))) {
        $user = $usuarios->getDataAlunoByCpf($cpf);
    } else if (!empty($usuarios->getDataPersonalByCpf($cpf))) {
        $user = $usuarios->getDataPersonalByCpf($cpf);
    } else {
        $_SESSION['error'] = "Usuário não encontrado!";
        header("Location: ../index.php?page=telaLogin");
        exit();
    }

    // Usuário encontrado → salva na sessão e deixa o index.php cuidar do redirecionamento
    $_SESSION['usuario'] = $user;
    header("Location: ../index.php"); // Acesso à home → index decide para onde mandar
    exit();
}
