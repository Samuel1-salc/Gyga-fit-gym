<?php
session_start();
require_once __DIR__ . '/../models/Usuarios.class.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $_SESSION['error'] = '';

    // Validação básica
    if (empty($cpf)) {
        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: http://localhost/Gyga-fit-gym/index.php?page=telaLogin");
        exit();
    }

    if (strlen($cpf) != 11) {
        $_SESSION['error'] = "CPF inválido!";
        header("Location: http://localhost/Gyga-fit-gym/index.php?page=telaLogin");
        exit();
    }

    $usuarios = new Users();

    // Verifica se é gerente
    $usuarioGerente = $usuarios->getDataGerenteByCpf($cpf);

    if (!empty($usuarioGerente)) {
        // Se não enviou senha ainda, pede senha
        if (empty($senha)) {
            $_SESSION['mostrarSenha'] = true;
            $_SESSION['cpfDigitado'] = $cpf;
            header("Location: ./../view/telaLogin.php");
            exit();
        } else {
            // Já enviou senha, valida
            if (password_verify($senha, $usuarioGerente['senha'])) {
                $_SESSION['usuario'] = [
                    'id' => $usuarioGerente['id'],
                    'nome' => $usuarioGerente['nome'],
                    'typeUser' => 'gerente'
                ];
                // Limpa flags
                unset($_SESSION['mostrarSenha'], $_SESSION['cpfDigitado'], $_SESSION['error']);
                header("Location: http://localhost/Gyga-fit-gym/index.php?page=painelAdministrativo");
                exit();
            } else {
                $_SESSION['mostrarSenha'] = true;
                $_SESSION['cpfDigitado'] = $cpf;
                $_SESSION['error'] = "Senha incorreta.";
                header("Location: ../view/login.php");
                exit();
            }
        }
    }

    // Verifica se é aluno (sem senha)
    $usuarioAluno = $usuarios->getDataAlunoByCpf($cpf);
    if (!empty($usuarioAluno)) {
        $_SESSION['usuario'] = $usuarioAluno;
        $_SESSION['usuario']['typeUser'] = 'aluno';
        header("Location: http://localhost/Gyga-fit-gym/index.php?page=telaPrincipal");
        exit();
    }

    // Verifica se é instrutor (sem senha)
    $usuarioInstrutor = $usuarios->getDataPersonalByCpf($cpf);
    if (!empty($usuarioInstrutor)) {
        $_SESSION['usuario'] = $usuarioInstrutor;
        $_SESSION['usuario']['typeUser'] = 'instrutor';
        header("Location: http://localhost/Gyga-fit-gym/index.php?page=perfilInstrutor");
        exit();
    }

    // Se chegou aqui, não encontrou usuário
    $_SESSION['error'] = "Usuário não encontrado!";
    header("Location: http://localhost/Gyga-fit-gym/index.php?page=telaLogin");
    exit();
}
