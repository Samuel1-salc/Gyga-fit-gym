<?php
/**
 * controllers/ProcessarNovoTreino.php
 *
 * 1. Se GET[gerar_pdf]=1, chama a geração do PDF do treino do aluno.
 * 2. Se POST[submit_plano], processa e cadastra o novo treino como antes.
 * 3. Se POST[submit_NovoTreino], redireciona para a página de novo treino.
 */

session_start();

// ---------------------------------------------------------
// 1) Geração de PDF via GET
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['gerar_pdf']) && isset($_GET['alunoId'])) {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../config/database.class.php';
    require_once __DIR__ . '/../models/Treino.class.php';
    require_once __DIR__ . '/PdfService.php';

    $alunoId = (int) $_GET['alunoId'];
    if ($alunoId <= 0) {
        http_response_code(400);
        exit('alunoId inválido');
    }

    $treinoModel = new Treino();
    $dados       = $treinoModel->getPlanoParaPdf($alunoId);
    if (empty($dados)) {
        http_response_code(404);
        exit('Treino não encontrado.');
    }

    $pdfService = new PdfService();
    $pdfService->render($dados);
    // não chega aqui, pois render() faz exit()
}

// ---------------------------------------------------------
// 2) Cadastro de novo treino via POST
// ---------------------------------------------------------
require_once __DIR__ . '/../models/Treino.class.php';
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';

$novoTreino = new Treino();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_plano'])) {
        // --- Lógica original de processar cadastro de treino ---
        $id_aluno = filter_input(INPUT_POST, 'id_aluno', FILTER_VALIDATE_INT);
        if (!$id_aluno) {
            die("ID do aluno inválido.");
        }

        $id_instrutor = $_SESSION['usuario']['id'] ?? '';
        if (!$id_instrutor) {
            die("ID do instrutor não encontrado.");
        }

        // Recupera ou inicializa o id_treino_criado
        $_SESSION['id_treino_criado'] = $_SESSION['id_treino_criado'] ?? 0;
        $ultimo = $novoTreino->getIdTreinoCriado($id_aluno);
        if (!empty($ultimo)) {
            $_SESSION['id_treino_criado'] = $ultimo['id_treino_criado'];
        }
        $_SESSION['id_treino_criado']++;
        $id_treino_criado = $_SESSION['id_treino_criado'];

        $data_criacao = date('Y-m-d H:i:s');
        $dados        = $_POST['dados'] ?? [];
        $observacoes  = $_POST['obs']   ?? [];
        $sucesso      = true;

        if (!empty($dados)) {
            foreach ($dados as $letra => $exercicios) {
                $observacao = $observacoes[$letra] ?? '';
                foreach ($exercicios as $ex) {
                    $novoTreino->cadastrarTreino(
                        $id_instrutor,
                        $id_aluno,
                        $id_treino_criado,
                        $letra,
                        $ex['num_exercicio'],
                        $ex['nome_exercicio'],
                        $ex['series_exercicio'],
                        $ex['repeticoes_exercicio'],
                        $observacao,
                        $data_criacao
                    );
                }
            }
        } else {
            echo "Nenhum dado recebido para o treino.";
            $sucesso = false;
        }

        if ($sucesso) {
            $id_solicitacao = $_POST['id_solicitacao'] ?? null;
            if ($id_solicitacao) {
                $atualizado = new SolicitacaoTreino();
                $atualizado->atualizarStatusSolicitacao($id_aluno, "Atendido");
            }
            header("Location: ../view/perfilInstrutor.php?id_aluno=$id_aluno");
            exit();
        } else {
            echo "Erro ao cadastrar o plano de treino!";
            echo '<pre>'; print_r($dados); echo '</pre>';
        }
    }

    // ---------------------------------------------------------
    // 3) Redireciona para página de criação de novo treino
    // ---------------------------------------------------------
    if (isset($_POST['submit_NovoTreino'])) {
        $id_aluno = $_POST['id_alunoNovoTreino'] ?? '';
        header("Location: ../view/paginaDeTreino.php?id_aluno=$id_aluno");
        exit();
    }
}

