<?php
require_once __dir__ . '/../services/academiaService.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // Validação de entrada
        if (
            empty($_POST['campo1']) || empty($_POST['campo2']) || empty($_POST['campo3']) ||
            empty($_POST['campo4']) || empty($_POST['campo5']) || empty($_POST['campo6'])
        ) {
            throw new Exception("Todos os campos são obrigatórios.");
        }

        // Sanitização dos dados
        $dados = [
            'nome' => htmlspecialchars($_POST['campo1'], ENT_QUOTES, 'UTF-8'),
            'cep' => htmlspecialchars($_POST['campo2'], ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($_POST['campo3'], ENT_QUOTES, 'UTF-8'),
            'cnpj' => htmlspecialchars($_POST['campo4'], ENT_QUOTES, 'UTF-8'),
            'capacidade' => $_POST['campo5'],
            'unidade' => htmlspecialchars($_POST['campo6'], ENT_QUOTES, 'UTF-8')
        ];

        // Processamento via service
        $academiaService = new AcademiaService();

        if ($academiaService->cadastrarAcademia(
            $dados['nome'],
            $dados['cep'],
            $dados['email'],
            $dados['cnpj'],
            $dados['capacidade'],
            $dados['unidade']
        )) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao cadastrar academia. Verifique os dados e tente novamente.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    // Redirecionamento
    header("Location: ../view/painelAdmin.php");
    exit();
}

/**
 * Obtém dados organizados da academia baseado na unidade do gerente logado
 */
function obterDadosAcademiaGerente()
{
    try {
        // Verifica se o usuário está logado e tem unidade
        if (!isset($_SESSION['usuario']['unidade'])) {
            throw new Exception("Usuário não possui unidade definida.");
        }

        $unidadeGerente = $_SESSION['usuario']['unidade'];

        // Instância do service
        $academiaService = new AcademiaService();

        // Obtém dados organizados apenas da unidade do gerente
        $dadosAcademia = $academiaService->organizarDadosAcademia($unidadeGerente);

        return $dadosAcademia;
    } catch (Exception $e) {
        return [
            'sucesso' => false,
            'mensagem' => $e->getMessage(),
            'dados' => []
        ];
    }
}
