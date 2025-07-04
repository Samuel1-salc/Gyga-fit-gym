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
    <link rel="stylesheet" href="./view/style/stylePainel.css?v=<?= time() ?>">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      body { background: #111 !important; color: #f5f5f5 !important; }
      .container { background: #181818 !important; color: #f5f5f5 !important; }
      .aluno-card, .personal-card, .acad-card { background: #222 !important; color: #f5f5f5 !important; }
      .tab-btn { background: #222 !important; color: #f5f5f5 !important; }
      .tab-btn.active { background: #FF0000 !important; color: #fff !important; }
      .search-bar { background: #222 !important; border: 1px solid #333 !important; }
      .search-bar input { background: #181818 !important; color: #f5f5f5 !important; }
      .btn-cadastrar, .btn-editar { background: #FF0000 !important; color: #fff !important; }
      .painel-header h1, .tab-content h2 { color: #FF0000 !important; }
      .student-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ff0000;
        background: #181818;
      }
      .student-avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #222;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        border: 2px solid #ff0000;
      }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <form action="index.php?page=telaInicial" method="get" style="margin-left:auto;display:inline-block;">
                <button type="submit" style="background:#222;color:#fff;border:none;padding:8px 18px;border-radius:16px;font-weight:bold;cursor:pointer;margin-right:10px;">Menu Academia</button>
            </form>
            <form action="index.php?page=logout" method="post" style="display:inline-block;">
                <button type="submit" style="background:#ff0000;color:#fff;border:none;padding:8px 18px;border-radius:16px;font-weight:bold;cursor:pointer;">Logout</button>
            </form>
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
                            <?php if (!empty($al['foto'])): ?>
                                <img src="./view/uploads/<?= htmlspecialchars($al['foto']) ?>" alt="Foto do aluno" class="student-avatar">
                            <?php else: ?>
                                <div class="student-avatar-placeholder">
                                    <?= strtoupper(substr($al['username'] ?? 'U', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
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
                            <?php if (!empty($p['foto'])): ?>
                                <img src="./view/uploads/<?= htmlspecialchars($p['foto']) ?>" alt="Foto do instrutor" class="student-avatar">
                            <?php else: ?>
                                <div class="student-avatar-placeholder">
                                    <?= strtoupper(substr($p['username'] ?? 'U', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
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