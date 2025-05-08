<?php
/**
 * Script responsável por processar o cadastro de um novo aluno.
 * Realiza validações dos dados recebidos via POST, cadastra o aluno no banco de dados
 * e redireciona para as telas apropriadas conforme o resultado.
 *
 * Dependências:
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 * - UserAluno.class.php: Classe para operações específicas de alunos.
 *
 * Fluxo:
 * 1. Recebe dados do formulário via POST.
 * 2. Valida os dados (campos obrigatórios, email, CPF, telefone).
 * 3. Em caso de erro, armazena mensagem na sessão e redireciona para tela de cadastro.
 * 4. Se válido, cadastra o aluno e redireciona para tela de login com mensagem de sucesso.
 * 5. Em caso de exceção, armazena mensagem de erro na sessão e redireciona para tela de cadastro.
 *
 * @package controllers
 */

require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/UserAluno.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Users();
    $cadastrarAluno = new UserAluno();

    // Recebe e sanitiza os dados do formulário
    $username = htmlspecialchars($_POST['campo1'] ?? '', ENT_QUOTES, 'UTF-8');
    $idade = htmlspecialchars($_POST['campo2'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['campo3'] ?? '', ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['campo4'] ?? '', ENT_QUOTES, 'UTF-8');
    $cpf = htmlspecialchars($_POST['campo5'] ?? '', ENT_QUOTES, 'UTF-8');
    $unidade = htmlspecialchars($_POST['campo6'] ?? '', ENT_QUOTES, 'UTF-8');
    $plano = htmlspecialchars($_POST['campo7'] ?? '', ENT_QUOTES, 'UTF-8');
    $data_inicio = $cadastrarAluno->dataInicio();
    $data_termino = $cadastrarAluno->dataTermino($data_inicio, $plano);
    $typeUser = 'aluno';

    // Validações dos dados
    $erro = validarDados($username, $email, $cpf, $phone, $usuario, $typeUser);
    if ($erro) {
        $_SESSION['error'] = $erro;
        header("Location: ../view/telaCadastro.php");
        exit();
    }

    // Cadastro do aluno
    try {
        if ($cadastrarAluno->cadastrarAluno($username, $email, $cpf, $unidade, $plano, $data_inicio, $data_termino, $phone, $typeUser)) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: ../view/telaLogin.php");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao cadastrar!";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erro inesperado: " . $e->getMessage();
        error_log("Erro ao cadastrar aluno: " . $e->getMessage());
    }

    header("Location: ../view/telaCadastro.php");
}

/**
 * Valida os dados do formulário de cadastro de aluno.
 *
 * @param string $username Nome do aluno.
 * @param string $email Email do aluno.
 * @param string $cpf CPF do aluno.
 * @param string $phone Telefone do aluno.
 * @param Users $usuario Instância da classe Users para validações.
 * @param string $typeUser Tipo de usuário (ex: 'aluno').
 * @return string|null Retorna mensagem de erro ou null se estiver tudo correto.
 */
function validarDados($username, $email, $cpf, $phone, $usuario, $typeUser) {
    if (empty($email) || empty($username) || empty($cpf)) {
        return "Preencha todos os campos!";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Email inválido!";
    }
    if ($usuario->checkByEmail($email, $typeUser)) {
        return "Este email já está cadastrado!";
    }
    if ($usuario->checkByCpf($cpf, $typeUser)) {
        return "Este CPF já está cadastrado!";
    }
    if ($usuario->checkByPhone($phone, $typeUser)) {
        return "Este telefone já está cadastrado!";
    }
    return null;
}