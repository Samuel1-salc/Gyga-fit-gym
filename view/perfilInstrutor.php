<?php
session_start();
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'instrutor') {
    header('Location: login.php');
    exit;
}

$instrutor = $_SESSION['usuario'];
if (!isset($instrutor['especialidade'])) {
    $instrutor['especialidade'] = 'Musculação e Funcional';
}
if (!isset($instrutor['disponibilidade'])) {
    $instrutor['disponibilidade'] = 'Seg a Sex, 08h às 18h';
}

$usuarioInstrutor = new usuario_instrutor();

$alunos = [
    [
        'id_Aluno' => 101,
        'nome_aluno' => 'João da Silva',
        'contato_aluno' => 'joao@email.com',
        'data_solicitacao' => '2025-04-20',
        'status' => 'pendente',
        'foto_aluno' => 'img/joao.jpg'
    ],
    [
        'id_Aluno' => 102,
        'nome_aluno' => 'Maria Oliveira',
        'contato_aluno' => 'maria@email.com',
        'data_solicitacao' => '2025-04-19',
        'status' => 'em andamento',
        'foto_aluno' => 'img/maria.jpg'
    ]
];

usort($alunos, function ($a, $b) {
    return strtotime($b['data_solicitacao']) - strtotime($a['data_solicitacao']);
});
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Instrutor</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f2f2f2;
        }

        .fundo-vermelho {
            background-color:rgb(255, 0, 0);
            padding: 40px 0;
        }

        header {
            background-color: #000;
            color: white;
            padding: 10px 30px;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-logo img {
            height: 50px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        .perfil-instrutor {
            border-bottom: 2px solid #ccc;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .perfil-instrutor h2 {
            color: red;
            margin-bottom: 10px;
        }

        .perfil-conteudo {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 15px;
        }

        .foto-instrutor {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid red;
        }

        .perfil-instrutor p {
            margin: 5px 0;
            font-size: 16px;
        }

        .solicitacoes h3 {
            color: red;
            margin-bottom: 20px;
        }

        .card-aluno {
            border: 2px solid red;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .card-info img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .card-info p {
            margin: 0;
        }

        .card-botoes {
            display: flex;
            gap: 10px;
        }

        .card-botoes form {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-status {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            background-color: red;
            color: white;
            cursor: pointer;
        }

        select {
            padding: 5px 10px;
            border-radius: 6px;
        }
    </style>
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
                    <p><strong>Especialidade:</strong> <?= htmlspecialchars($instrutor['especialidade']) ?></p>
                    <p><strong>Quantidade de Alunos Atendidos:</strong> <?= count($alunos) ?></p>
                    <p><strong>Disponibilidade:</strong> <?= htmlspecialchars($instrutor['disponibilidade']) ?></p>
                </div>
            </div>
        </div>

        <div class="solicitacoes">
            <h3>Solicitações de Treino</h3>
            <?php foreach ($alunos as $aluno): ?>
                <div class="card-aluno">
                    <div class="card-info">
                        <img src="<?= htmlspecialchars($aluno['foto_aluno']) ?>" alt="Foto do aluno">
                        <div>
                            <p><strong><?= htmlspecialchars($aluno['nome_aluno']) ?></strong></p>
                            <p><?= htmlspecialchars($aluno['data_solicitacao']) ?></p>
                            <p>Status: <?= htmlspecialchars($aluno['status']) ?></p>
                        </div>
                    </div>
                    <div class="card-botoes">
                        <form method="POST" action="atualizarStatus.php">
                            <input type="hidden" name="id_aluno" value="<?= $aluno['id_Aluno'] ?>">
                            <select name="status">
                                <option value="em andamento" <?= $aluno['status'] === 'em andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="finalizada" <?= $aluno['status'] === 'finalizada' ? 'selected' : '' ?>>Finalizada</option>
                            </select>
                            <button class="btn-status" type="submit"><i class="fas fa-check-circle"></i></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>
