<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ .  '/../models/Usuarios.class.php';
require_once __DIR__ .  '/../models/usuarioInstrutor.class.php';

$users      = new Users();
$instrModel = new aluno_instrutor();

// Recupera parâmetros de busca e aba ativa
$search    = $_GET['search'] ?? '';
$activeTab = $_GET['tab']    ?? 'alunos';

// Carrega dados (filtrados se houver busca)
if ($search !== '') {
    if ($activeTab === 'personais') {
        $personais = $instrModel->getInstrutoresByNome($search);
    } else {
        $alunos = $users->getDataAlunosByNome($search);
    }
} else {
    $alunos    = $users->getDataAlunosForPerfilAlunos();
    $personais = $instrModel->getAllInstrutores();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gyga Fit - Painel Administrativo</title>
    <link rel="stylesheet" href="./view/style/stylePainel.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="user-icon"><i class="fas fa-user"></i></div>
            <div class="logo"><img src="./view/img/logo.png" alt="Gyga Fit Logo" class="logo-img"></div>
            <div class="menu-icon"><i class="fas fa-bars"></i></div>
        </div>
    </header>

    <main class="container">
        <div class="painel-header">
            <h1>Painel Administrativo</h1>
        </div>

        <div class="tabs">
            <button class="tab-btn" data-tab="alunos">Alunos</button>
            <button class="tab-btn" data-tab="personais">Personais</button>
            <button class="tab-btn" data-tab="academia">Academia</button>
        </div>

        <form method="get" class="search-bar">
            <input type="text" name="search" placeholder="Buscar..."
                value="<?= htmlspecialchars($search) ?>">
            <input type="hidden" name="tab" id="tabInput"
                value="<?= htmlspecialchars($activeTab) ?>">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Aba Alunos -->
        <div class="tab-content" id="alunos-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>PAINEL DE ALUNOS</h2>
                <button class="btn-cadastrar"
                    onclick="location.href='view/telaCadastroAluno.php'">
                    <i class="fas fa-plus"></i><span>Cadastrar Aluno</span>
                </button>
            </div>

            <?php if (empty($alunos)): ?>
                <p>Nenhum aluno cadastrado.</p>
                <?php else: foreach ($alunos as $al): ?>
                    <div class="aluno-card">
                        <div class="aluno-avatar">
                            <img src="./view/img/<?php echo htmlspecialchars($al['foto']); ?>" alt="Avatar">
                        </div>
                        <div class="aluno-info">
                            <h3><?= htmlspecialchars($al['username']) ?></h3>
                            <p><strong>Email:</strong> <?= htmlspecialchars($al['email']) ?></p>
                            <p><strong>CPF:</strong> <?= htmlspecialchars($al['cpf']) ?></p>
                            <p><strong>Unidade:</strong> <?= htmlspecialchars($al['unidade']) ?></p>
                            <p><strong>Telefone:</strong> <?= htmlspecialchars($al['phone']) ?></p>
                            <p><strong>Plano:</strong> <?= htmlspecialchars($al['plano']) ?></p>
                            <?php
                            $tFim     = new DateTime($al['data_termino']);
                            $hoje     = new DateTime();
                            $interval = $hoje->diff($tFim);
                            ?>
                            <p><strong>Tempo restante:</strong>
                                <?= $interval->m ?> mês(es) e <?= $interval->d ?> dia(s)
                            </p>
                        </div>
                        <div class="aluno-action">
                            <button class="btn-editar"
                                onclick="location.href='editarAluno.php?id=<?= $al['id'] ?>'">
                                <i class="fas fa-cog"></i><span>Editar</span>
                            </button>
                        </div>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>

        <!-- Aba Personais -->
        <div class="tab-content" id="personais-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>PAINEL DE PERSONAIS</h2>
                <button class="btn-cadastrar"
                    onclick="location.href='telaCadastroInstrutor.php'">
                    <i class="fas fa-plus"></i><span>Cadastrar Personal</span>
                </button>
            </div>

            <?php if (empty($personais)): ?>
                <p>Nenhum personal cadastrado.</p>
                <?php else: foreach ($personais as $p): ?>
                    <div class="personal-card">
                        <div class="personal-avatar">
                            <img src="./view/img/<?php echo htmlspecialchars($p['foto']); ?>" alt="Avatar">>
                        </div>
                        <div class="personal-info">
                            <h3><?= htmlspecialchars($p['username']) ?></h3>
                            <p>Email: <?= htmlspecialchars($p['email']) ?></p>
                            <p>Alunos ativos: <?= $instrModel->quantidadeAlunosAtendidos($p['id']) ?></p>
                        </div>
                        <div class="personal-action">
                            <button class="btn-editar"
                                onclick="location.href='editarPersonal.php?id=<?= $p['id'] ?>'">
                                <i class="fas fa-cog"></i><span>Editar</span>
                            </button>
                        </div>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>

        <!-- Aba Academia -->
        <div class="tab-content" id="academia-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>PAINEL DE ACADEMIAS</h2>
                <button class="btn-cadastrar"
                    onclick="location.href='telaCadastroAcademia.php'">
                    <i class="fas fa-plus"></i><span>Cadastrar Academia</span>
                </button>
            </div>
            <!-- cards estáticos -->
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            const tabInput = document.getElementById('tabInput');

            // inicializa abas conforme GET
            tabButtons.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.tab === tabInput.value);
            });
            tabContents.forEach(c => {
                c.style.display = c.id === tabInput.value + '-content' ? 'block' : 'none';
            });

            // clique nas abas
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    tabInput.value = tabName;
                    tabButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    tabContents.forEach(c => c.style.display = 'none');
                    document.getElementById(tabName + '-content').style.display = 'block';
                });
            });
        });
    </script>
</body>

</html>