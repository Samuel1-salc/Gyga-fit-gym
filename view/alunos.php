<?php
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
    <link rel="stylesheet" href="./style/perfilAlunos.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>

<header>
    <div class="header-logo">
        <img src="img/logo.png" alt="Logo GYGA FIT">
        <h1>GYGA FIT - Painel de alunos</h1>
    </div>
</header>

<div class="fundo-vermelho">
    <div class="container">
       
        <div class="solicitacoes">
            <h3>Alunos</h3>
            <form method="GET" action="" class="search-bar">
                <input type="text" name="search" placeholder="Pesquisar aluno..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Pesquisar</button>
            </form>
        <?php if (!empty($alunos)): ?>
            <div class="card-aluno">
                <div class="aluno-box">
                    <table>
                        <thead>
                            <tr>
                                <th>Nome do Aluno</th>
                                <th>Contato</th>
                                <th>Plano</th>
                                <th>Disponibilidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($alunos) as $aluno): ?>
                                <tr>
                                
                                    <td><?= htmlspecialchars($aluno['username']) ?></td>
                                    <td><?= htmlspecialchars($aluno['email']) ?></td>
                                    <td><?= htmlspecialchars(plano($aluno['plano']) ?? '---') ?></td>
                                    <td><?= disponiblidade($aluno['id']) ?></td>

                                    <td>
                                
                                    <?php if (disponiblidade($aluno['id']) == 'Disponível'): ?>
                                            <form method="POST" action="../controllers/processsarAdicionarAluno.php">
                                                <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($aluno['id'] ?? '') ?>">
                                                <button type="submit">Adicionar</button>
                                            </form>
                                        <?php else: ?>
                                            
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <p>Nenhuma solicitação de treino encontrada.</p>
        <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>