<?php
require_once __DIR__ . '/../models/Treino.class.php';
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
$novoTreino = new Treino();
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_plano'])) {
        $id_aluno = filter_input(INPUT_POST, 'id_aluno', FILTER_VALIDATE_INT);
        if (!$id_aluno) {
            die("ID do aluno inválido.");
        }

        // Recupera o ID do instrutor
        $id_instrutor = $_SESSION['usuario']['id'] ?? '';
        if (!$id_instrutor) {
            die("ID do instrutor não encontrado.");
        }

        // Recupera o último ID de treino criado e incrementa
        if (!isset($_SESSION['id_treino_criado']) || empty($novoTreino->getIdTreinoCriado($id_aluno))) {	
            $_SESSION['id_treino_criado'] = 0;
        }

        if(!empty($novoTreino->getIdTreinoCriado($id_aluno))){
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
        // Verifica se os dados estão vazios
        if (!empty($dados)) {
            foreach ($dados as $letra => $exercicios) {
                
                echo "Processando letra: $letra<br>";
                $observacao = $observacoes[$letra] ?? ''; // Observação específica para o treino
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
                    ));
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
            header("Location: ./../../view/alunoSucessoInstrutor.php");
            exit();
        } else {
            echo "Erro ao cadastrar o plano de treino!";
            echo '<pre>';
            print_r($dados);
            echo '</pre>';
            
        }
    }

    if (isset($_POST['submit_NovoTreino'])) {
        $id_aluno = $_POST['id_alunoNovoTreino'] ?? '';
        header("Location: ../view/paginaDeTreino.php?id_aluno=$id_aluno");
        exit();
    }
}
