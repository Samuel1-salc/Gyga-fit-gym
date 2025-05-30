<?php

session_start();

// Página acessada via GET; padrão = telaInicial
$page = $_GET['page'] ?? 'telaInicial';

// Redirecionamento inteligente com base no tipo de usuário
function redirecionarUsuario()
{
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

// Logout
if ($page === 'logout') {
    session_destroy();
    header("Location: index.php?page=telaInicial");
    exit();
}

// Se o usuário já estiver logado e cair na página pública, redireciona
if (isset($_SESSION['usuario']) && $page === 'telaInicial') {
    //redirecionarUsuario();
}

// Lista de páginas que exigem autenticação
$rotasProtegidas = ['telaPrincipal', 'perfilInstrutor'];

if (in_array($page, $rotasProtegidas)) {
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?page=telaLogin");
        exit();
    }
}

// Roteamento das páginas
switch ($page) {
    case 'telaInicial':
        include 'view/telaInicialAcademia.php';
        break;

    case 'telaLogin':
        include 'view/telaLogin.php';
        break;

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
    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?page=telaInicial");
        exit();
        break;

    case 'solicitacaoTreino':
        if ($_SESSION['usuario']['typeUser'] === 'aluno') {
            include 'view/paginaFormulario.php';
        } else {
            header("Location: index.php?page=erro");
        }
        break;
    case 'sucessoSolicitacaoDeTreino':
        if ($_SESSION['usuario']['typeUser'] === 'aluno') {
            include 'view/telaPrincipal.php';
        } else {
            header("Location: index.php?page=erro");
        }
        break;
    case 'painelAdministrativo':
        if ($_SESSION['usuario']['typeUser'] === 'gerente') {
            include 'view/painelAdministrativo.php';
        } else {
            header("Location: index.php?page=erro");
        }
        break;

    case 'erro':
        echo "<h2>Acesso negado ou tipo de usuário inválido.</h2>";
        break;

    default:
        echo "<h2>Página não encontrada.</h2>";
        break;
}
