<?php
/**
 * Script responsável por processar o cadastro de um novo instrutor.
 * Realiza validações dos dados recebidos via POST, cadastra o instrutor no banco de dados
 * e redireciona para as telas apropriadas conforme o resultado.
 *
 * Dependências:
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 * - UserInstrutor.class.php: Classe para operações específicas de instrutores.
 *
 * Fluxo:
 * 1. Recebe dados do formulário via POST.
 * 2. Valida os dados (campos obrigatórios, email, CPF, telefone).
 * 3. Em caso de erro, armazena mensagem na sessão e interrompe o script.
 * 4. Se válido, cadastra o instrutor e redireciona para tela de login com mensagem de sucesso.
 * 5. Em caso de erro no cadastro, armazena mensagem de erro na sessão e redireciona para tela de login.
 *
 * @package controllers
 */

require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/UserInstrutor.class.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Users();
    $cadastrarPersonal = new UserInstrutor();

    // Recebe os dados do formulário
    $username = $_POST['campo1'] ?? '';
    $idade = $_POST['campo2'] ?? '';
    $email = $_POST['campo3'] ?? '';
    $phone = $_POST['campo4'] ?? '';
    $cpf = $_POST['campo5'] ?? '';
    $unidade = $_POST['campo6'] ?? '';
    $servico = $_POST['campo7'] ?? '';
    $data_entrada = $cadastrarPersonal->dataInicio();
    $data_saida = "";
    $typeUser = "instrutor"; // Definindo o tipo de usuário como instrutor

    // Validações dos dados
    if (empty($email) || empty($username) || empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
        exit();
    } elseif (strlen($cpf) !== 11) {
        $_SESSION['error'] = "CPF inválido!";
        exit();
    } elseif ($usuario->checkByEmail($email, $typeUser)) {
        $_SESSION['error'] = "Este email já está cadastrado!";
        exit();
    } elseif ($usuario->checkByCpf($cpf, $typeUser)) {
        $_SESSION['error'] = "Este CPF já está cadastrado!";
        exit();
    } elseif ($usuario->checkByPhone($phone, $typeUser)) {
        $_SESSION['error'] = "Este telefone já está cadastrado!";
        exit();
    } else {
        // Cadastro do instrutor
        if ($cadastrarPersonal->cadastrarInstrutor($username, $email, $cpf, $unidade, $servico, $data_entrada, $data_saida, $phone, $typeUser)) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: ../view/telaLogin.php");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao cadastrar!";
        }
    }

    header("Location: ../view/telaLogin.php");
}