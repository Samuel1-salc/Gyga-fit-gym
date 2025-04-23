<?php
require_once __DIR__ . '/../models/Usuarios.class.php';
session_start();
$users = new Users();
$alunos = [];
$alunos = $users->getDataAlunosForPerfilAlunos();

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Painel do Instrutor</title>
    <link rel="stylesheet" href="./style/perfilInstrutor.css">
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
            <?php if (!empty($alunos)): ?>
        <table class="tabela-alunos">
            <thead>
                <tr>
                    <th>Nome do Aluno</th>
                    <th>Contato</th>
                    <th>Plano</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($alunos) as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['username']) ?></td>
                        <td><?= htmlspecialchars($aluno['email']) ?></td>
                        <td><?= htmlspecialchars($aluno['plano'] ?? '---') ?></td>
                        <td>
                            <form method="POST" action="../controllers/processsarAdicionarAluno.php">
                                <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($aluno['id'] ?? '') ?>">
                                <select name="processo">
                                    <option value="em andamento">Em Andamento</option>
                                    <option value="finalizada">Finalizada</option>
                                </select>
                                <button type="submit">Adicionar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma solicitação de treino encontrada.</p>
    <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>