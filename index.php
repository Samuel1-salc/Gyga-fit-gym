<?php
session_start();

// Página solicitada via GET (rota simples)
$page = $_GET['page'] ?? 'home';

// Redireciona para a interface conforme o tipo de usuário logado
function redirecionarUsuario() {
    $tipo = $_SESSION['usuario']['typeUser'] ?? '';

    if ($tipo === 'aluno') {
        header("Location: index.php?page=telaPrincipal");
        exit();
    } elseif ($tipo === 'instrutor') {
        header("Location: index.php?page=perfilInstrutor");
        exit();
    } else {
        header("Location: index.php?page=erro");
        exit();
    }
}

// Rota de logout
if ($page === 'logout') {
    session_destroy();
    header("Location: index.php?page=telaLogin");
    exit();
}

// Se não estiver logado e tentar acessar qualquer coisa que não seja a tela de login → redireciona
if (!isset($_SESSION['usuario']) && $page !== 'telaLogin') {
    header("Location: index.php?page=telaLogin");
    exit();
}

// Se estiver logado e tentar acessar a home → redireciona para a interface correta
if (isset($_SESSION['usuario']) && $page === 'home') {
    redirecionarUsuario();
}

// Roteamento centralizado
switch ($page) {
    case 'telaPrincipal':
        if ($_SESSION['usuario']['typeUser'] === 'aluno') {
            include 'view/telaPrincipal.php';
        } else {
            header("Location: index.php?page=erro");
        }
        break;

    case 'perfilInstrutor':
        if ($_SESSION['usuario']['typeUser'] === 'instrutor') {
            include 'view/perfilInstrutor.php';
        } else {
            header("Location: index.php?page=erro");
        }
        break;

    case 'telaLogin':
        include 'view/telaLogin.php';
        break;

    case 'erro':
        echo "<h2>Acesso negado ou tipo de usuário inválido.</h2>";
        break;

    default:
        echo "<h2>Página não encontrada.</h2>";
        break;
}
?>
