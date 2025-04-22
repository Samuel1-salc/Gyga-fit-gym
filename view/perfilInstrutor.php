<?php
session_start();
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';

// Simulação de usuário logado
$_SESSION['usuario'] = [
    'id' => 1,
    'username' => 'Instrutor Teste',
    'tipo' => 'instrutor',
    'especialidade' => 'Musculação',
    'disponibilidade' => 'Seg a Sex - 8h às 18h'
];

$instrutor = $_SESSION['usuario'];
$usuarioInstrutor = new usuario_instrutor();

// Simulação de alunos
$alunos = [
    [
        'id_Aluno' => 101,
        'nome_aluno' => 'João da Silva',
        'contato_aluno' => 'joao@email.com',
        'data_solicitacao' => '2025-04-20',
        'status' => 'pendente'
    ],
    [
        'id_Aluno' => 102,
        'nome_aluno' => 'Maria Oliveira',
        'contato_aluno' => 'maria@email.com',
        'data_solicitacao' => '2025-04-19',
        'status' => 'em andamento'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Instrutor</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../style/Tela-Principal.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('/Gyga-fit-gym/view/img/0f0d989af5ce80b293f0de513b7a2bcb.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: transparent;
        }

        header h1 {
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }

        .perfil-instrutor {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .perfil-instrutor h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .perfil-instrutor p {
            font-size: 16px;
            margin: 6px 0;
        }

        .tabela-alunos {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .tabela-alunos th, .tabela-alunos td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .tabela-alunos th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .tabela-alunos tr:hover {
            background-color: #f9f9f9;
        }

        .tabela-alunos select, .tabela-alunos button {
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            margin-right: 5px;
        }

        .tabela-alunos button {
            background-color: #4CAF50;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .tabela-alunos button:hover {
            background-color: #45a049;
        }

        h3 {
            color: #fff;
            text-shadow: 1px 1px 2px #000;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 16px;
        }

    </style>
</head>
<body>

<header>
    <button class="btn-lateral btn-usuario" onclick="editarPerfil()">
        <i class="fas fa-user"></i>
    </button>

    <h1>GYGA FIT</h1>

    <button class="btn-lateral btn-config" onclick="abrirConfiguracoes()">
        <i class="fas fa-cog"></i>
    </button>
</header>

<div class="container">
    <div class="perfil-instrutor">
        <h2>Perfil do Instrutor</h2>
        <p><strong>Nome:</strong> <?= htmlspecialchars($instrutor['username']) ?></p>
        <p><strong>Especialidade:</strong> <?= htmlspecialchars($instrutor['especialidade'] ?? 'Não informada') ?></p>
        <p><strong>Quantidade de Alunos Atendidos:</strong> <?= count($alunos) ?></p>
        <p><strong>Disponibilidade:</strong> <?= htmlspecialchars($instrutor['disponibilidade'] ?? 'Não informada') ?></p>
    </div>

    <h3>Solicitações de Treino</h3>

    <?php if (!empty($alunos)): ?>
        <table class="tabela-alunos">
            <thead>
                <tr>
                    <th>Nome do Aluno</th>
                    <th>Contato</th>
                    <th>Data de Solicitação</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($alunos) as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['nome_aluno']) ?></td>
                        <td><?= htmlspecialchars($aluno['contato_aluno']) ?></td>
                        <td><?= htmlspecialchars($aluno['data_solicitacao'] ?? '---') ?></td>
                        <td><?= htmlspecialchars($aluno['status'] ?? 'pendente') ?></td>
                        <td>
                            <form method="POST" action="atualizarStatus.php">
                                <input type="hidden" name="id_aluno" value="<?= $aluno['id_Aluno'] ?>">
                                <select name="status">
                                    <option value="em andamento">Em Andamento</option>
                                    <option value="finalizada">Finalizada</option>
                                </select>
                                <button type="submit">Atualizar</button>
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

<script>
    function editarPerfil() {
        alert("Abrindo tela de edição de perfil...");
    }

    function abrirConfiguracoes() {
        alert("Abrindo configurações...");
    }
</script>

</body>
</html>
