<?php
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
require_once __DIR__ . '/../models/Usuarios.class.php';

$relacaoAlunoInstrutor = new aluno_instrutor();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_aluno = $_POST['id_aluno'] ?? '';
    $processo = $_POST['processo'] ?? '';




    $data_solicitacao = $relacaoAlunoInstrutor->dataDeSolicitacao();

    if (!empty($relacaoAlunoInstrutor->checkRelationshipUsers($id_aluno))) {
        $_SESSION['error'] = "Este aluno já tem instrutor!";
        //exit();
    } else {
        $relacaoAlunoInstrutor->adicionarAluno_Instrutor($id_aluno, $processo, $data_solicitacao);
        $_SESSION['success'] = "Solicitação enviada com sucesso!";
        header("Location: ../view/alunoSucessoInstrutor.php");
        exit();

    }


} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user = new Users();
    $search = $_GET['search'] ?? '';
    $alunos = $user->getDataAlunosByNome($search);
    if (empty($alunos)) {
        $_SESSION['error'] = "Nenhum aluno encontrado!";
    } else {
        header("Location: ../view/alunos.php?search=$search");
    }
} else {
    $_SESSION['error'] = "Método de requisição inválido!";
}
