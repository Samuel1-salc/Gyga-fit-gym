<?php
/**
 * Script responsável por processar o login de usuários (alunos e instrutores).
 * Realiza validações do CPF recebido via POST, busca o usuário no banco de dados
 * e redireciona para a tela apropriada conforme o tipo de usuário.
 *
 * Dependências:
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 *
 * Fluxo:
 * 1. Recebe o CPF do formulário via POST.
 * 2. Valida o CPF (campo obrigatório e tamanho).
 * 3. Busca o usuário (aluno ou instrutor) pelo CPF.
 * 4. Se encontrado, armazena os dados do usuário na sessão e redireciona para a tela correspondente.
 * 5. Se não encontrado, armazena mensagem de erro na sessão e interrompe o script.
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
        exit();
    }

    // Validação do tamanho do CPF
    if (strlen($cpf) != 11) {
        $_SESSION['error'] = "CPF inválido!";
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
        exit();
    }

    // Redireciona conforme o tipo de usuário
    if (!empty($user)) {
        $_SESSION['usuario'] = $user;

        if ($user['typeUser'] == 'aluno') {
            header("Location: ../view/telaPrincipal.php");
            exit();
        } else if ($user['typeUser'] == 'instrutor') {
            header("Location: ../view/perfilInstrutor.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Senha incorreta ou usuário não encontrado!";
        echo "Senha incorreta ou usuário não encontrado!";
    }
}

