<?php
/**
 * Script responsável por processar a adição de um aluno a um instrutor.
 * Realiza validações dos dados recebidos via POST, verifica se o aluno já possui instrutor,
 * cadastra o relacionamento aluno-instrutor e armazena mensagens de sucesso ou erro na sessão.
 * Também permite buscar alunos pelo nome via GET.
 *
 * Dependências:
 * - usuarioInstrutor.class.php: Classe para operações de relacionamento entre aluno e instrutor.
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 *
 * Fluxo:
 * 1. Se POST:
 *    - Recebe o ID do aluno e o status do processo.
 *    - Verifica se o aluno já possui instrutor.
 *    - Se não, cadastra o relacionamento e redireciona para tela de sucesso.
 *    - Se sim, armazena mensagem de erro na sessão.
 * 2. Se GET:
 *    - Busca alunos pelo nome.
 *    - Redireciona para a tela de alunos ou armazena mensagem de erro se não encontrar.
 * 3. Se outro método, armazena mensagem de erro na sessão.
 *
 * @package controllers
 */

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
