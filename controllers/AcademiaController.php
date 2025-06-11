<?php

require_once __DIR__ . '/../services/academiaService.php';

/**
 * Controller para operações de academia
 * Segue princípios de código limpo e POO
 */
class AcademiaController
{
    private $academiaService;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->academiaService = new AcademiaService();
    }

    /**
     * Processa cadastro de academia
     */
    public function cadastrar()
    {
        try {
            // Validação do método
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método não permitido.");
            }

            // Validação de entrada
            $this->validarCamposObrigatorios();

            // Sanitização dos dados
            $dados = $this->sanitizarDados();

            // Processamento via service
            if ($this->academiaService->cadastrarAcademia(
                $dados['nome'],
                $dados['cep'],
                $dados['email'],
                $dados['cnpj'],
                $dados['capacidade'],
                $dados['unidade']
            )) {
                $this->definirMensagemSucesso("Cadastro realizado com sucesso!");
            } else {
                $this->definirMensagemErro("Erro ao cadastrar academia. Verifique os dados e tente novamente.");
            }
        } catch (Exception $e) {
            $this->definirMensagemErro($e->getMessage());
        }

        $this->redirecionarParaPainel();
    }

    /**
     * Obtém dados organizados da academia baseado na unidade do gerente
     */
    public function obterDadosAcademiaGerente()
    {
        try {
            // Verifica se o usuário está logado e tem unidade
            if (!isset($_SESSION['usuario']['unidade'])) {
                throw new Exception("Usuário não possui unidade definida.");
            }

            $unidadeGerente = $_SESSION['usuario']['unidade'];

            // Obtém dados organizados apenas da unidade do gerente
            return $this->academiaService->organizarDadosAcademia($unidadeGerente);
        } catch (Exception $e) {
            return [
                'sucesso' => false,
                'mensagem' => $e->getMessage(),
                'dados' => []
            ];
        }
    }

    /**
     * Lista academias (para gerente ver sua unidade)
     */
    public function listarParaGerente()
    {
        $dados = $this->obterDadosAcademiaGerente();

        // Aqui você incluiria a view apropriada
        // include __DIR__ . '/../view/academia/painel-gerente.php';

        return $dados;
    }

    /**
     * Edita dados da academia
     */
    public function editar()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método não permitido.");
            }

            $unidade = $_POST['unidade'] ?? '';
            $dados = $this->sanitizarDados();

            if ($this->academiaService->atualizarAcademia($unidade, $dados)) {
                $this->definirMensagemSucesso("Academia atualizada com sucesso!");
            } else {
                $this->definirMensagemErro("Erro ao atualizar academia.");
            }
        } catch (Exception $e) {
            $this->definirMensagemErro($e->getMessage());
        }

        $this->redirecionarParaPainel();
    }

    // ========== MÉTODOS PRIVADOS (AUXILIARES) ==========

    /**
     * Valida se todos os campos obrigatórios estão preenchidos
     */
    private function validarCamposObrigatorios()
    {
        $campos = ['campo1', 'campo2', 'campo3', 'campo4', 'campo5', 'campo6'];

        foreach ($campos as $campo) {
            if (empty($_POST[$campo])) {
                throw new Exception("Todos os campos são obrigatórios.");
            }
        }
    }

    /**
     * Sanitiza os dados do formulário
     */
    private function sanitizarDados()
    {
        return [
            'nome' => htmlspecialchars($_POST['campo1'], ENT_QUOTES, 'UTF-8'),
            'cep' => htmlspecialchars($_POST['campo2'], ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($_POST['campo3'], ENT_QUOTES, 'UTF-8'),
            'cnpj' => htmlspecialchars($_POST['campo4'], ENT_QUOTES, 'UTF-8'),
            'capacidade' => $_POST['campo5'],
            'unidade' => htmlspecialchars($_POST['campo6'], ENT_QUOTES, 'UTF-8')
        ];
    }

    /**
     * Define mensagem de sucesso na sessão
     */
    private function definirMensagemSucesso($mensagem)
    {
        $_SESSION['success'] = $mensagem;
    }

    /**
     * Define mensagem de erro na sessão
     */
    private function definirMensagemErro($mensagem)
    {
        $_SESSION['error'] = $mensagem;
    }

    /**
     * Redireciona para o painel administrativo
     */
    private function redirecionarParaPainel()
    {
        header("Location: ../view/painelAdmin.php");
        exit();
    }
}

// ========== EXECUÇÃO ==========

// Instancia o controller e executa a ação apropriada
$controller = new AcademiaController();

// Determina qual ação executar baseado no parâmetro ou método
$acao = $_GET['acao'] ?? 'cadastrar';

switch ($acao) {
    case 'cadastrar':
        $controller->cadastrar();
        break;
    case 'listar':
        $dados = $controller->listarParaGerente();
        // Aqui você pode incluir uma view ou retornar JSON
        break;
    case 'editar':
        $controller->editar();
        break;
    default:
        header("Location: ../view/painelAdmin.php");
        exit();
}
