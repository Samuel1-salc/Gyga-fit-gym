<?php
/**
 * Página responsável por exibir a lista de alunos para o painel do instrutor.
 * Permite pesquisar alunos pelo nome, exibe informações de contato, plano e disponibilidade,
 * e possibilita adicionar um aluno ao instrutor caso esteja disponível.
 *
 * Dependências:
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 * - usuarioInstrutor.class.php: Classe para operações de relacionamento entre aluno e instrutor.
 *
 * Fluxo:
 * 1. Inicia a sessão e instancia as classes necessárias.
 * 2. Se houver busca por nome, filtra os alunos; caso contrário, exibe todos.
 * 3. Função `disponiblidade($id_aluno)`: Verifica se o aluno já possui instrutor.
 * 4. Função `plano($plano)`: Retorna o nome do plano conforme o valor.
 * 5. Exibe os alunos em cards, mostrando informações e botão para adicionar se disponível.
 *
 * @package view
 */
require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
session_start();
$users = new Users();

$alunos = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $alunos = $users->getDataAlunosByNome($search);
} else {
    $alunos = $users->getDataAlunosForPerfilAlunos();
}
 function disponiblidade($id_aluno){
    $relacao = new aluno_instrutor();
    $chekRelacao = $relacao->checkRelationshipUsers($id_aluno);
    if ($chekRelacao && !empty($chekRelacao['id_instrutor'])) {
        return "Indisponível";
    } else {
        return "Disponível";
    }
 }

 function plano($plano){
    if ($plano == 1) {
        return "Mensal";
    } elseif($plano == 2){
        return "semestral";
    } elseif($plano == 3){
        return "Anual";
    }
 }

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Painel do Instrutor</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap">
    <link rel="stylesheet" href="./style/perfilAlunos.css?v=<?= time(); ?>">
</head>
<body>

    <header>
        <div class="header-container">
            <div class="user-icon">
               
            </div>
            <div class="logo">
                <img src="./img/logo.png" alt="Gyga Fit Logo" class="logo-img">
            </div>
            <button class="header-button" onclick="toggleSidebar()">☰</button>
                
            </div>
        </div>
    </header>

<div class="main-container">
            <h3>Alunos</h3>
            <form method="GET" action="" class="search-bar">
                <input type="text" name="search" placeholder="Pesquisar aluno..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Pesquisar</button>
            </form>
            <div class="solicitacoes">
        <?php foreach (array_reverse($alunos) as $aluno): ?>
            <div class="card-aluno">
                <div class="card-info">               
                    <p><strong>Aluno: <?= htmlspecialchars($aluno['username']) ?></strong></p>
                    <p><strong>Contato: <?= htmlspecialchars($aluno['email']) ?></strong></p>
                    <p><strong>Plano: <?= htmlspecialchars(plano($aluno['plano']) ?? '---') ?></strong></p>
                    <p><?= disponiblidade($aluno['id']) ?></p>
                </div>
                
                <?php if (disponiblidade($aluno['id']) == 'Disponível'): ?>
                    <form method="POST" action="../controllers/processsarAdicionarAluno.php">
                        <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($aluno['id'] ?? '') ?>">
                        <button type="submit">Adicionar</button>
                    </form>
                <?php endif; ?>
            </div> <!-- Fechando card-aluno dentro do foreach -->
        <?php endforeach; ?>
    </div> <!-- solicitacoes -->
</div><!-- main -->

</body>
</html>