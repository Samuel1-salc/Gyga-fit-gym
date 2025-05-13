<?php
/**
 * Script responsável por processar o cadastro de um novo plano de treino para um aluno.
 * Realiza validações dos dados recebidos via POST, cadastra os exercícios do treino no banco de dados,
 * atualiza o status da solicitação de treino e redireciona para as telas apropriadas conforme o resultado.
 *
 * Dependências:
 * - Treino.class.php: Classe para operações de cadastro de treinos.
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 * - SolicitacaoTreino.class.php: Classe para operações de solicitações de treino.
 *
 * Fluxo:
 * 1. Recebe dados do formulário via POST.
 * 2. Valida o ID do aluno e do instrutor.
 * 3. Recupera ou gera o ID do treino criado.
 * 4. Processa e cadastra cada exercício do treino.
 * 5. Atualiza o status da solicitação de treino para "Atendido" se necessário.
 * 6. Redireciona para a tela de sucesso ou exibe mensagens de erro.
 * 7. Também processa o redirecionamento para a página de criação de novo treino.
 *
 * @package controllers
 */

require_once __DIR__ . '/../models/Treino.class.php';
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
$novoTreino = new Treino();
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Cadastro do plano de treino
    if (isset($_POST['submit_plano'])) {
        $id_aluno = filter_input(INPUT_POST, 'id_aluno', FILTER_VALIDATE_INT);
        if (!$id_aluno) {
            die("ID do aluno inválido.");
        }

        // Recupera o ID do instrutor da sessão
        $id_instrutor = $_SESSION['usuario']['id'] ?? '';
        if (!$id_instrutor) {
            die("ID do instrutor não encontrado.");
        }

        // Recupera ou inicializa o ID do treino criado
        if (!isset($_SESSION['id_treino_criado']) || empty($novoTreino->getIdTreinoCriado($id_aluno))) {	
            $_SESSION['id_treino_criado'] = 0;
        }
        if (!empty($novoTreino->getIdTreinoCriado($id_aluno))) {
            $_SESSION['id_treino_criado'] = $novoTreino->getIdTreinoCriado($id_aluno)['id_treino_criado'];
        }
        $_SESSION['id_treino_criado']++;
        $id_treino_criado = $_SESSION['id_treino_criado'];

        // Data de criação do treino
        $data_criacao = date('Y-m-d H:i:s');

        // Recupera os dados enviados pelo formulário
        $dados = $_POST['dados'] ?? [];
        $observacoes = $_POST['obs'] ?? [];

        $sucesso = true;

        // Verifica se os dados estão vazios e cadastra os exercícios
        if (!empty($dados)) {
            foreach ($dados as $letra => $exercicios) {
                $observacao = $observacoes[$letra] ?? '';
                foreach ($exercicios as $exercicio) {
                    if (!$novoTreino->cadastrarTreino(
                        $id_instrutor,
                        $id_aluno,
                        $id_treino_criado,
                        $letra,
                        $exercicio['num_exercicio'],
                        $exercicio['nome_exercicio'],
                        $exercicio['series_exercicio'],
                        $exercicio['repeticoes_exercicio'],
                        $observacao,
                        $data_criacao
                    )) {
                        $sucesso = false;
                        echo "Erro ao cadastrar exercício: ";
                        echo '<pre>';
                        print_r([
                            'letra' => $letra,
                            'num_exercicio' => $exercicio['num_exercicio'],
                            'nome_exercicio' => $exercicio['nome_exercicio'],
                            'series' => $exercicio['series_exercicio'],
                            'repeticoes' => $exercicio['repeticoes_exercicio'],
                            'observacao' => $observacao
                        ]);
                        echo '</pre>';
                    }
                }
            }
        } else {
            echo "Nenhum dado recebido para o treino.";
            $sucesso = false;
        }

        // Atualiza status da solicitação e redireciona
        if ($sucesso) {
            $id_solicitacao = $_POST['id_solicitacao'] ?? null;
            if ($id_solicitacao) {
                $atualizado = new SolicitacaoTreino();
                $atualizado->atualizarStatusSolicitacao($id_aluno, "Atendido");
            }
            header("Location: ./../../view/alunoSucessoInstrutor.php");
            exit();
        } else {
            echo "Erro ao cadastrar o plano de treino!";
            echo '<pre>';
            print_r($dados);
            echo '</pre>';
        }
    }

    // Redireciona para a página de criação de novo treino
    if (isset($_POST['submit_NovoTreino'])) {
        $id_aluno = $_POST['id_alunoNovoTreino'] ?? '';
        header("Location: ../view/paginaDeTreino.php?id_aluno=$id_aluno");
        exit();
    }
}
