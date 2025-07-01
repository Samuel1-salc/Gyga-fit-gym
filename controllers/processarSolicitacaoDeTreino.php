<?php

/**
 * Script responsável por processar a solicitação de treino de um aluno.
 * Realiza validações dos dados recebidos via POST, cadastra a solicitação de treino no banco de dados,
 * atualiza o status do relacionamento aluno-instrutor e armazena mensagens de erro na sessão quando necessário.
 *
 * Dependências:
 * - SolicitacaoTreino.class.php: Classe para operações de solicitações de treino.
 * - usuarioInstrutor.class.php: Classe para operações de relacionamento entre aluno e instrutor.
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 *
 * Fluxo:
 * 1. Recebe dados do formulário via POST.
 * 2. Recupera o ID do aluno da sessão e a data/hora atual.
 * 3. Verifica se o aluno possui relacionamento com um instrutor.
 * 4. Se sim, cadastra a solicitação de treino e atualiza o status do processo para "em andamento".
 * 5. Se não, armazena mensagem de erro na sessão.
 *
 * @package controllers
 */


require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
require_once __DIR__ . '/../models/Usuarios.class.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // se for necessário
$users = new Users();
$SolicitarTreino = new SolicitacaoTreino();
$checkRelacao = new aluno_instrutor();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $experiencia = $_POST['experiencia'] ?? '';
    $objetivo = $_POST['objetivo'] ?? '';
    $treinos = $_POST['treinos'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $peso = $_POST['peso'] ?? '';
    $altura = $_POST['altura'] ?? '';
    $id_aluno = $_SESSION['usuario']['id'] ?? '';
    $data_created = $users->dataAtual();

    if ($checkRelacao->checkRelationshipUsers($id_aluno)) {
        $SolicitarTreino->SolicitarTreino($data_created, $id_aluno, $experiencia, $objetivo, $treinos, $sexo, $peso, $altura, $status = "em andamento");
        $processo = 'em andamento';
        $checkRelacao->adcStatus($processo, $id_aluno);
        header("Location: /Gyga-fit-gym/view/sucessoSolicitacaoTreino.php");
        exit;
    } else {
        $_SESSION['error'] = "voce ainda não tem instrutor!";
        header("Location: /Gyga-fit-gym/index.php?page=telaPrincipal&erro=sem_instrutor");
        exit;
    }
}
