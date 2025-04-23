<?php
session_start();
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';


$instrutor = $_SESSION['usuario'];
$alunoInstrutor = new aluno_instrutor();

$aluno = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']);
$countAlunos = $alunoInstrutor->quantidadeAlunosAtendidos($instrutor['id']);

$data_saida = $instrutor['data_saida'] ?? null;

if ($data_saida && $data_saida != '0000-00-00') {
    $disponibilidade = "indisponível";
} else {
    $disponibilidade = "disponível";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
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
        <h1>GYGA FIT - Painel do Instrutor</h1>
    </div>
</header>

<div class="fundo-vermelho">
    <div class="container">
        <div class="perfil-instrutor">
            <h2>Perfil do Instrutor</h2>
            <div class="perfil-conteudo">
                <img class="foto-instrutor" src="img/instrutor.jpg" alt="Foto do Instrutor">
                <div>
                    <p><strong>Nome:</strong> <?= htmlspecialchars($instrutor['username']) ?></p>
                    <p><strong>Especialidade:</strong> <?= htmlspecialchars($instrutor['servico'] ?? 'Não informado') ?></p>
                    <p><strong>Quantidade de Alunos Atendidos:</strong> <?= htmlspecialchars($countAlunos ?? 'Nenhum aluno encontrado') ?></p>
                    <p><strong>Disponibilidade:</strong> <?= htmlspecialchars($disponibilidade) ?></p>
                </div>
            </div>
        </div>

        <div class="solicitacoes">
            <h3>Solicitações de Treino</h3>
            <?php foreach ($aluno as $aluno): ?>
                <div class="card-aluno">
                    <div class="card-info">
                        
                        <div>
                            <p><strong><?= htmlspecialchars($aluno['nome_aluno']) ?></strong></p>
                            <p><?= htmlspecialchars($aluno['data_solicitacao']) ?></p>
                            <p>Status: <?= htmlspecialchars($aluno['processo']) ?></p>
                        </div>
                    </div>
                    <div class="card-botoes">
                        
                            <select name="processo">
                                <option value="em andamento" <?= $aluno['processo'] === 'em andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="finalizada" <?= $aluno['processo'] === 'finalizada' ? 'selected' : '' ?>>Finalizada</option>
                            </select>
                            <button class="btn-status" type="submit"><i class="fas fa-check-circle"></i></button>
                            <form action="../controllers/processarNovoTreino.php" method="POST">
                                <input type="hidden" name="id_alunoNovoTreino" value="<?= $aluno['id_aluno'] ?>">
                                <button class="btn-status" name = "submit_NovoTreino" type="submit">Novo treino</button>
                            </form>
                    </div>
                </div>
                
            <?php endforeach; ?>
        </div>
        <button class="btn-status" onclick="window.location.href='./alunos.php'">Adicionar Aluno</button>
    </div>
    
</div>

</body>
</html>