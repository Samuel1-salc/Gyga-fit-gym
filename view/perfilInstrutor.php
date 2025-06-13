<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * painelInstrutor.php
 * Página de painel para instrutores com design moderno
 */



require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models/Treino.class.php';

// --- Funções auxiliares (coloque aqui, ANTES do if) ---
function countPendentes()
{
    $alunos = extrairAlunosUnicos();
    $status = 'em andamento';
    $countPendentes = 0;
    foreach ($alunos as $item) {
        if ($item['status'] == $status) {
            $countPendentes++;
        }
    }
    return $countPendentes;
}

function countSolicitacaoTreino($id_aluno)
{
    global $aluno;
    $countSolicitacoes = 0;
    foreach ($aluno as $item) {
        if (!empty($id_aluno) && $item['id_aluno'] == $id_aluno && !empty($item['data_created'])) {
            $countSolicitacoes++;
        }
    }
    return $countSolicitacoes;
}

function getStatus($id_aluno)
{
    global $aluno;
    foreach ($aluno as $item) {
        if (!empty($id_aluno) && $item['id_aluno'] == $id_aluno) {
            return $item['status'];
        }
    }
    return null;
}

function extrairAlunosUnicos($dadosAlunos = null)
{
    global $alunoOriginal;
    $dados = $dadosAlunos ?? $alunoOriginal;
    $alunosUnicos = [];
    $idsProcessados = [];
    $contadorSemId = 0;

    foreach ($dados as $item) {
        $idAluno = $item['id_aluno'] ?? $item['id'] ?? null;
        if (empty($idAluno)) {
            $chaveUnica = 'sem_id_' . $contadorSemId . '_' . $item['nome_aluno'];
            $contadorSemId++;
        } else {
            $chaveUnica = $idAluno;
        }

        if (!in_array($chaveUnica, $idsProcessados)) {
            $alunosUnicos[] = [
                'id_aluno' => $idAluno,
                'nome_aluno' => $item['nome_aluno'],
                'data_solicitacao' => $item['data_solicitacao'],
                'contato_aluno' => $item['contato_aluno'],
                'processo' => $item['processo'],
                'status' => $item['status'],
            ];
            $idsProcessados[] = $chaveUnica;
        }
    }
    return $alunosUnicos;
}

function aplicarFiltroStatus(&$aluno, $statusFiltro)
{
    $statusFiltro = strtolower($statusFiltro);
    if ($statusFiltro === 'todos')
        return;

    $aluno = array_filter($aluno, function ($item) use ($statusFiltro) {
        return !empty($item['status']) && strtolower($item['status']) === $statusFiltro;
    });
}

function aplicarPesquisa(&$aluno, $search)
{
    $search = trim($search);
    if (empty($search))
        return;

    $aluno = array_filter($aluno, function ($item) use ($search) {
        return isset($item['nome_aluno']) && stripos($item['nome_aluno'], $search) !== false;
    });
}

// --- Fim das funções auxiliares ---

$instrutor = $_SESSION['usuario'];
$alunoInstrutor = new aluno_instrutor();
$solicitacaoTreino = new SolicitacaoTreino();
$alunoOriginal = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']);
$aluno = $alunoOriginal;

if (!empty($alunoOriginal)) {
    $alunosUnicos = [];
    $contadorSemId = 0;
    foreach ($alunoOriginal as $item) {
        if (!empty($item['id_aluno'])) {
            $alunosUnicos[$item['id_aluno']] = true;
        } else {
            $chaveUnica = 'sem_id_' . $contadorSemId . '_' . $item['nome_aluno'];
            $alunosUnicos[$chaveUnica] = true;
            $contadorSemId++;
        }
    }
    $countAlunos = count($alunosUnicos);
    $data_saida = $instrutor['data_saida'] ?? null;
    $disponibilidade = ($data_saida && $data_saida != '0000-00-00') ? "indisponível" : "disponível";

    // --- Fim do processamento inicial ---
} else {
    $countAlunos = 0;
    $disponibilidade = "indisponível"; // Adicione esta linha
    $mensagemFiltro = '<div class="alert alert-error"><i data-lucide="users-x" class="icon"></i><strong>Nenhum aluno encontrado.</strong></div>';
    $alunoOriginal = [];
    $aluno = [];
}

// Processamento do agendamento
if (isset($_POST['agendar_consulta'])) {
    $id_instrutor = $instrutor['id'];
    $id_aluno = isset($_POST['id_aluno']) ? intval($_POST['id_aluno']) : 0;
    $data_agendamento = isset($_POST['data_agendamento']) ? trim($_POST['data_agendamento']) : '';
    $observacao = isset($_POST['observacao']) ? trim($_POST['observacao']) : '';

    if ($id_aluno > 0 && !empty($data_agendamento)) {
        $config = require __DIR__ . '/../config/db-config.php';
        $db = $config['database'];
        $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $db['user'], $db['password']);
        $stmt = $pdo->prepare("INSERT INTO agendamentos (id_instrutor, id_aluno, data, observacao) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$id_instrutor, $id_aluno, $data_agendamento, $observacao])) {
            $msgAgendamento = '<div class="alert alert-success">Agendamento realizado com sucesso!</div>';
        } else {
            $msgAgendamento = '<div class="alert alert-error">Erro ao agendar. Tente novamente.</div>';
        }
    } else {
        $msgAgendamento = '<div class="alert alert-error">Preencha todos os campos obrigatórios.</div>';
    }
}

// Lista de Agendamentos
$config = require __DIR__ . '/../config/db-config.php';
$db = $config['database'];
$dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset=utf8mb4";
$pdo = new PDO($dsn, $db['user'], $db['password']);

// Consulta correta: apenas compromissos futuros do instrutor logado
$stmt = $pdo->prepare("SELECT a.*, u.username FROM agendamentos a
    JOIN aluno u ON a.id_aluno = u.id
    WHERE a.id_instrutor = ? AND a.data_hora >= NOW()
    ORDER BY a.data_hora ASC");
$stmt->execute([$instrutor['id']]);
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getFormulariosByAluno($id_aluno)
{
    $config = require __DIR__ . '/../config/db-config.php';
    $db = $config['database'];
    $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $db['user'], $db['password']);

    $stmt = $pdo->prepare("SELECT * FROM formulario WHERE id_aluno = ?");
    $stmt->execute([$id_aluno]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function veryFyStatus($solicitacoes)
{
    // Retorna true se houver pelo menos uma solicitação com status "em andamento"
    foreach ($solicitacoes as $sol) {
        if (isset($sol['status']) && strtolower($sol['status']) === 'em andamento') {
            return true;
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Instrutor - GYGA FIT</title>
    <link rel="stylesheet" href="./view/style/perfilInstrutor.css?v=<?= time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Adicionando ícones Lucide via CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        .icon {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
        }

        .header-icon {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .metric-icon {
            width: 32px;
            height: 32px;
        }

        .btn-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin: 16px 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #10b981;
            color: #047857;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #3b82f6;
            color: #1e40af;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            color: #92400e;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #ef4444;
            color: #dc2626;
        }

        /* Estilos para o formulário de agendamento */
        .agendamento-section {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            margin: 16px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-agendamento {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-agendamento label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-agendamento input,
        .form-agendamento select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .btn-agendar {
            grid-column: span 2;
            padding: 12px;
            background-color: #10b981;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-agendar:hover {
            background-color: #059669;
        }

        /* Estilos para a lista de agendamentos */
        .lista-agendamentos {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            margin: 16px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .lista-agendamentos h4 {
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 600;
        }

        .lista-agendamentos ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .lista-agendamentos li {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .lista-agendamentos li:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <!-- Header Profissional -->
    <header>
        <div class="header-logo">
            <button class="header-button" onclick="toggleSidebar()">
                <i data-lucide="menu" class="header-icon"></i>
            </button>

            <img src="./view/img/logo.png"></img>

            <div>
                <h1>GYGA FIT</h1>
                <p class="header-subtitle">Painel do Instrutor</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-header">
                <i data-lucide="bell" class="btn-icon"></i>
                Notificações
            </button>
        </div>
    </header>

    <!-- Sidebar Melhorada -->
    <div class="sidebar" id="sidebar">
        <button class="close-button" onclick="toggleSidebar()">
            <i data-lucide="x" class="icon"></i>
        </button>
        <h3>
            <i data-lucide="settings" class="icon"></i>
            Menu
        </h3>
        <a href="./view/editar_perfil_instrutor.php">
            <i data-lucide="user-cog" class="icon"></i>
            Alterar Perfil
        </a>
        <a href="./configuracoes.php">
            <i data-lucide="settings" class="icon"></i>
            Configurações
        </a>
        <a href="./index.php?page=telaInicial" class="sidebar-link">
            <i data-lucide="log-out" class="icon"></i>
            <span>Menu da Academia</span>
        </a>
        <a href="./view/logout.php" class="sidebar-link">
            <i data-lucide="log-out" class="icon"></i>
            <span>log-out</span>
        </a>
    </div>

    <div class="main-content">
        <!-- Perfil do Instrutor Melhorado -->
        <div class="perfil-instrutor">
            <div class="instrutor-header">
                <div class="instrutor-avatar">
                    <?php if (!empty($instrutor['foto'])): ?>
                        <img src="./view/uploads/<?php echo htmlspecialchars($instrutor['foto']); ?>"
                            alt="Foto do Instrutor" class="foto-instrutor">
                    <?php else: ?>
                        <div class="avatar-circle">
                            <?= strtoupper(substr($instrutor['username'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="instrutor-info-header">
                    <h2 class="instrutor-nome"><?= htmlspecialchars($instrutor['username']) ?></h2>
                    <div class="instrutor-badges">
                        <span class="badge badge-primary">
                            <i data-lucide="star" class="icon"></i>
                            <?= htmlspecialchars($instrutor['servico'] ?? 'Personal Trainer') ?>
                        </span>
                        <span
                            class="badge <?= $disponibilidade === 'disponível' ? 'badge-success' : 'badge-warning' ?>">
                            <i data-lucide="<?= $disponibilidade === 'disponível' ? 'check-circle' : 'clock' ?>"
                                class="icon"></i>
                            <?= ucfirst($disponibilidade) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="instrutor-metricas">
                <div class="metrica-card alunos">
                    <div class="metrica-icon">
                        <i data-lucide="users" class="metric-icon"></i>
                    </div>
                    <div class="metrica-content">
                        <div class="metrica-numero"><?= $countAlunos ?></div>
                        <div class="metrica-label">Alunos Ativos</div>
                    </div>
                </div>

                <div class="metrica-card pendentes">
                    <div class="metrica-icon">
                        <i data-lucide="clock" class="metric-icon"></i>
                    </div>
                    <div class="metrica-content">
                        <div class="metrica-numero"><?= countPendentes() ?></div>
                        <div class="metrica-label">Pendentes</div>
                    </div>
                </div>

                <div class="metrica-card concluidos">
                    <div class="metrica-icon">
                        <i data-lucide="check-circle" class="metric-icon"></i>
                    </div>
                    <div class="metrica-content">
                        <div class="metrica-numero"><?= $countAlunos - countPendentes() ?></div>
                        <div class="metrica-label">Atendidos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Pesquisa Melhorada -->
        <div class="search-section">
            <form method="GET" action="./index.php " class="search-bar">
                <?php
                $search = $_GET['search'] ?? '';
                $statusSelecionado = $_GET['status'] ?? '';
                ?>

                <div class="search-input-container">
                    <i data-lucide="search" class="search-icon"></i>
                    <input type="text" name="search" placeholder="Pesquisar aluno por nome..."
                        value="<?= htmlspecialchars($search) ?>" class="search-input">
                </div>

                <!-- Sempre incluir o status atual como campo oculto se existir -->
                <button type="submit" class="btn-search">
                    <i data-lucide="search" class="btn-icon"></i>

                    Pesquisar
                </button>
                <!-- Mudar o nome para "status" para corresponder ao parâmetro usado no PHP -->
                <select name="status" id="status_filter" class="filter-select" onchange="applyFilter(this.value)">
                    <option value="">
                        <i data-lucide="filter" class="icon"></i>
                        Filtros
                    </option>
                    <option value="em andamento" <?= $statusSelecionado === 'em andamento' ? 'selected' : '' ?>>Pendente
                    </option>
                    <option value="atendido" <?= $statusSelecionado === 'atendido' ? 'selected' : '' ?>>Atendido</option>
                    <option value="todos" <?= $statusSelecionado === 'todos' ? 'selected' : '' ?>>Todos</option>
                </select>


                <?php if (!empty($statusSelecionado)): ?>
                    <a href="<?= "index.php?page=perfilInstrutor " ?><?= !empty($search) ? '?search=' . urlencode($search) : '' ?>"
                        class="btn-clear-filter">
                        <i data-lucide="x" class="btn-icon"></i>
                        Limpar Filtros
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <!-- Solicitações Melhoradas -->
        <div class="solicitacoes">
            <div class="solicitacoes-header">
                <h3>
                    <i data-lucide="calendar" class="icon"></i>
                    Solicitações de Treino
                </h3>
            </div>

            <?php if (!empty($mensagemFiltro)): ?>
                <?= $mensagemFiltro ?>
            <?php endif; ?>

            <div class="alunos-grid">
                <?php $alunosInfo = extrairAlunosUnicos($aluno); ?>

                <?php if (!empty($alunosInfo)): ?>
                    <?php foreach ($alunosInfo as $alunoAtual): ?>
                        <div class="card-aluno">
                            <?php $solicitacoes = getFormulariosByAluno($alunoAtual['id_aluno']); ?>
                            <?php $status = getStatus($alunoAtual['id_aluno']); ?>

                            <div class="aluno-card-header">
                                <div class="aluno-avatar-small">
                                    <?= strtoupper(substr($alunoAtual['nome_aluno'], 0, 1)) ?>
                                </div>
                                <div class="aluno-info">
                                    <h4 class="aluno-nome-card">
                                        <?= htmlspecialchars($alunoAtual['nome_aluno']) ?>
                                        <?php if (!empty($status) && $status === 'em andamento'): ?>
                                            <span class="notification-dot"></span>
                                        <?php endif; ?>
                                    </h4>
                                    <div class="aluno-meta">
                                        <span class="meta-item">
                                            <i data-lucide="calendar" class="icon"></i>
                                            <?= htmlspecialchars($alunoAtual['data_solicitacao']) ?>
                                        </span>
                                        <span class="meta-item">
                                            <i data-lucide="activity" class="icon"></i>
                                            <?= countSolicitacaoTreino($alunoAtual['id_aluno']) ?> solicitações
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Container oculto da solicitação -->
                            <?php if (!empty($solicitacoes)): ?>
                                <div id="solicitacao-<?= $alunoAtual['id_aluno'] ?>" class="solicitacao-details"
                                    style="display: none;">
                                    <?php foreach ($solicitacoes as $sol): ?>
                                        <div class="solicitacao-item">
                                            <div class="detail-grid">
                                                <div class="detail-item">
                                                    <span class="detail-label">Experiência:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($sol['experiencia']) ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Objetivo:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($sol['objetivo']) ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Dias de treino:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($sol['treinos']) ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Peso:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($sol['peso']) ?>kg</span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Altura:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($sol['altura']) ?>cm</span>
                                                </div>
                                            </div>

                                            <?php
                                            $statusClass = '';
                                            switch (strtolower($sol['status'])) {
                                                case 'em andamento':
                                                    $statusClass = 'status-pendente';
                                                    break;
                                                case 'atendido':
                                                    $statusClass = 'status-atendido';
                                                    break;
                                                default:
                                                    $statusClass = 'status-default';
                                            }
                                            ?>
                                            <div class="status-badge <?= $statusClass ?>">
                                                <i data-lucide="<?= strtolower($sol['status']) === 'em andamento' ? 'clock' : 'check-circle' ?>"
                                                    class="icon"></i>
                                                <?= htmlspecialchars($sol['status']) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="card-actions">
                                <?php if (!empty($solicitacoes)): ?>
                                    <button class="btn-visualizar" onclick="toggleSolicitacao('<?= $alunoAtual['id_aluno'] ?>')">
                                        <i data-lucide="eye" class="btn-icon"></i>
                                        Visualizar
                                    </button>

                                    <?php if (veryFyStatus($solicitacoes)): ?>
                                        <form action="./controllers/processarNovoTreino.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="id_alunoNovoTreino" value="<?= $alunoAtual['id_aluno'] ?>">
                                            <button type="submit" name="submit_NovoTreino" class="btn-novo-treino">
                                                <i data-lucide="plus-circle" class="btn-icon"></i>
                                                Novo Treino
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($alunosInfo)): ?>
                <div class="add-aluno-section">
                    <button class="btn-add-aluno" onclick="window.location.href='./view/alunos.php'">
                        <i data-lucide="user-plus" class="btn-icon"></i>
                        Adicionar Novo Aluno
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Formulário de Agendamento Personalizado -->
        <div class="agendamento-section">
            <h3>
                <i data-lucide="calendar-plus" class="icon"></i>
                Agendar Consulta Personalizada
            </h3>
            <?php if (isset($msgAgendamento))
                echo $msgAgendamento; ?>
            <form class="form-agendamento" method="POST" action="../controllers/controllerAgendamento.php">
                <label for="aluno">Selecione o Aluno:</label>
                <select name="aluno" id="aluno" required>
                    <option value="">Selecione um aluno</option>
                    <?php foreach ($alunoOriginal as $aluno): ?>
                        <option value="<?= $aluno['id_aluno'] ?>"><?= htmlspecialchars($aluno['nome_aluno']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="data_hora">Data e Hora:</label>
                <input type="datetime-local" name="data_hora" id="data_hora" required>
                <label for="observacao">Observação:</label>
                <input type="text" name="observacao" id="observacao" placeholder="Observações (opcional)">
                <button type="submit" name="agendar_consulta" class="btn-agendar">
                    <i data-lucide="calendar-check" class="btn-icon"></i>
                    Agendar Consulta
                </button>
            </form>
        </div>

        <!-- Lista de Agendamentos -->
        <div class="lista-agendamentos">
            <h4>Próximos Agendamentos</h4>
            <ul>
                <?php
                // filepath: [perfilInstrutor.php](http://_vscodecontentref_/0)
                $config = require __DIR__ . '/../config/db-config.php';
                $db = $config['database'];
                $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['dbname']};charset=utf8mb4";
                $pdo = new PDO($dsn, $db['user'], $db['password']);
                $stmt = $pdo->prepare("SELECT a.*, u.username FROM agendamentos a
                    JOIN aluno u ON a.id_aluno = u.id
                    WHERE a.id_instrutor = ? AND a.data_hora >= NOW()
                    ORDER BY a.data_hora ASC");
                $stmt->execute([$instrutor['id']]);
                $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($agendamentos):
                    foreach ($agendamentos as $ag):
                        ?>
                        <?php
                        // Defina os dados do evento
                        $start = date('Ymd\THis', strtotime($ag['data_hora']));
                        $end = date('Ymd\THis', strtotime($ag['data_hora'] . ' +1 hour'));
                        $title = urlencode('Consulta com ' . $ag['username']);
                        $details = urlencode($ag['observacao'] ?? '');
                        $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text=$title&dates=$start/$end&details=$details";
                        ?>
                        <li>
                            <strong><?= htmlspecialchars($ag['username']) ?></strong> -
                            <?= date('d/m/Y H:i', strtotime($ag['data'])) ?>
                            <?php if ($ag['observacao']): ?>
                                <em>(<?= htmlspecialchars($ag['observacao']) ?>)</em>
                            <?php endif; ?>
                            <a href="<?= $googleCalendarUrl ?>" target="_blank" class="btn-agendar-google"
                                style="margin-left:10px;">
                                Adicionar ao Google Agenda
                            </a>
                        </li>
                        <?php
                    endforeach;
                else:
                    echo "<li>Nenhum agendamento futuro.</li>";
                endif;
                ?>
            </ul>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <img src="./view/img/logo.png" alt="Logo GYGA FIT" class="footer-logo">
            <p>&copy; <?php echo date('Y'); ?> GYGA FIT. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Import or declare the lucide variable before using it
        const lucide = window.lucide || {};

        function applyFilter(filterValue) {
            if (filterValue) {
                // Obter parâmetros de URL atuais
                const urlParams = new URLSearchParams(window.location.search);

                // Definir o novo valor de status
                urlParams.set('status', filterValue);

                // Redirecionar com todos os parâmetros
                window.location.href = window.location.pathname + '?' + urlParams.toString();
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        function toggleSolicitacao(id) {
            const element = document.getElementById('solicitacao-' + id);
            const button = event.target.closest('.btn-visualizar');

            if (element.style.display === 'none' || element.style.display === '') {
                element.style.display = 'block';
                button.innerHTML = '<i data-lucide="eye-off" class="btn-icon"></i>Ocultar';
            } else {
                element.style.display = 'none';
                button.innerHTML = '<i data-lucide="eye" class="btn-icon"></i>Visualizar';
            }

            // Reinicializa os ícones
            lucide.createIcons();
        }

        // Inicializa os ícones quando a página carrega
        document.addEventListener("DOMContentLoaded", function () {
            lucide.createIcons();

            // Sincronizar o valor do select com o parâmetro da URL
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');

            if (statusParam) {
                const statusSelect = document.getElementById('status_filter');
                if (statusSelect) {
                    statusSelect.value = statusParam;
                }
            }
        });
    </script>
</body>

</html>