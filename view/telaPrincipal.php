<?php

/**
 * Página principal do aluno no sistema GYGA FIT.
 * Exibe informações do aluno logado, incluindo plano, tempo restante, instrutor responsável e unidade.
 * Permite ao aluno visualizar o cronograma de treinos, solicitar novo treino e acessar funcionalidades do menu lateral.
 *
 * Funcionalidades:
 * - Exibe nome, plano, tempo restante do plano, nome do instrutor e unidade do aluno.
 * - Mostra cronograma de treinos com botões para alternar entre Treino A, B e C.
 * - Permite solicitar um novo treino através de botão dedicado.
 * - Oferece menu lateral com opções de alterar perfil, configurações e menu da academia.
 * - Exibe informações institucionais e links de contato no rodapé.
 *
 * Dependências:
 * - Usuarios.class.php: Classe para operações gerais de usuários.
 *
 * Fluxo:
 * 1. Inicia a sessão e carrega os dados do usuário logado.
 * 2. Define funções auxiliares para exibir nome do plano, calcular tempo restante e buscar nome do instrutor.
 * 3. Renderiza informações do aluno e cronograma de treinos.
 * 4. Disponibiliza botões para solicitar novo treino e concluir ações.
 * 5. Exibe rodapé com informações da empresa e links sociais.
 *
 * Observações:
 * - O acesso a esta página pressupõe que o usuário esteja autenticado e com dados válidos na sessão.
 * - O layout utiliza CSS externo e fontes do Google Fonts.
 * - Funções JavaScript são usadas para manipular a sidebar.
 *
 * @package view
 * @author
 * @version 1.0
 */

require_once __DIR__ . '/../models/Usuarios.class.php';
require_once __DIR__ . '/../models//Treino.class.php';
$usuarios = new Users();
function plano($plano)
{
    if ($plano == 1) {
        return "Mensal";
    } elseif ($plano == 2) {
        return "semestral";
    } elseif ($plano == 3) {
        return "Anual";
    }
}
//************************************************************************************** */
function diffData($dataInicio, $dataTermino)
{
    $data_inicio = $dataInicio;
    $data_termino = $dataTermino;

    $inicio = new DateTime($data_inicio);
    $fim = new DateTime($data_termino);

    $intervalo = $inicio->diff($fim);

    // Verifica se há meses na diferença
    if ($intervalo->m > 0 || $intervalo->y > 0) {
        // Calcula o total de meses, incluindo os anos convertidos em meses
        $meses = ($intervalo->y * 12) + $intervalo->m;
        return " {$meses} meses";

        if ($intervalo->d > 0) {
            return " e {$intervalo->d} dias";
        }
    } else {
        return " {$intervalo->d} dias";
    }
}
function nomeInstrutor($id_aluno)
{
    $usuarios = new Users();
    $nomeInstrutor = $usuarios->getNomePersonalByAluno($id_aluno);
    return $nomeInstrutor['nome_instrutor'] ?? 'Não disponível';
}
//************************************************************************************** */
if (session_status() == PHP_SESSION_NONE) {
    // Sessão ainda não foi iniciada

    session_start();
}
//iniciando a parte de treinos
$treinosUser = new Treino();
$id_treino_criado_arr = $treinosUser->getIdTreinoCriado($_SESSION['usuario']['id']);
$id_ultimo_treino = $id_treino_criado_arr['id_treino_criado'] ?? null;
$id_ultimo_treino = $id_ultimo_treino ? (int) $id_ultimo_treino : null;

// Só busca treinos/letras se houver id_treino_criado
if ($id_ultimo_treino) {
    $treinos = $treinosUser->getTreinoByIdTreino($id_ultimo_treino);
    $letrasTreino = $treinosUser->getLetrasDotreino($id_ultimo_treino);
} else {
    $treinos = [];
    $letrasTreino = [];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYGA FIT - Cronograma de Treinos</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./view/style//Tela-Principal.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="./view/style/Tela-Principal-darkmode.css?v=<?= time(); ?>">
</head>

<body>
    <!-- Header Profissional Modernizado -->
    <header class="header-modern">
        <div class="header-container-modern">
            <div class="header-left">
                <button type="button" class="menu-button-modern" onclick="window.toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo-container-modern">
                    <div class="logo-icon-modern">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <div class="logo-text-modern">
                        <h1>GYGA FIT</h1>
                        <span>Seu Cronograma</span>
                    </div>
                </div>
            </div>

            <div class="header-right">
                <div class="user-avatar">
                    <?php if (!empty($_SESSION['usuario']['foto'])): ?>
                        <img src="./view/uploads/<?php echo htmlspecialchars($_SESSION['usuario']['foto']); ?>"
                            alt="Avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <?= strtoupper(substr($_SESSION['usuario']['username'] ?? 'U', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <button id="toggle-dark-mode" class="btn-dark-mode" title="Alternar modo escuro"
                    style="margin-left:16px;padding:10px 18px;border-radius:10px;background:rgba(30,41,59,0.7);color:#fff;font-size:15px;font-weight:600;box-shadow:0 2px 8px #0002;cursor:pointer;display:flex;align-items:center;gap:8px;border:none;transition:all 0.3s;">
                    <i class="fas fa-moon"></i> <span>Modo Escuro</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Sidebar Moderna -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar-modern" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-user-circle"></i> Menu</h3>
            <button class="close-button-modern" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="./view/editar_perfil.php" class="sidebar-link">
                <i class="fas fa-user-edit"></i>
                <span>Alterar Perfil</span>
            </a>
            <a href="./index.php?page=telaInicial" class="sidebar-link">
                <i class="fas fa-dumbbell"></i>
                <span>Menu da Academia</span>
            </a>
            <a href="./view/logout.php" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>log-out</span>
            </a>
        </nav>
    </div>

    <main class="main-content-modern">
        <div class="container-modern">
            <!-- Card de Informações do Aluno Modernizado -->
            <div class="student-info-card">
                <div class="card-header-accent"></div>
                <div class="student-info-content">
                    <div class="student-profile">
                        <div class="student-avatar-container">
                            <?php if (!empty($_SESSION['usuario']['foto'])): ?>
                                <img src="./view/uploads/<?php echo htmlspecialchars($_SESSION['usuario']['foto']); ?>"
                                    alt="Foto do aluno" class="student-avatar">
                            <?php else: ?>
                                <div class="student-avatar-placeholder">
                                    <?= strtoupper(substr($_SESSION['usuario']['username'] ?? 'U', 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="online-indicator"></div>
                        </div>

                        <div class="student-details">
                            <h2 class="student-name">
                                <?= htmlspecialchars($_SESSION['usuario']['username']) ?? 'Usuário não autenticado'; ?>
                            </h2>
                            <div class="student-badges">
                                <span class="badge badge-active">
                                    <i class="fas fa-user"></i> Aluno Ativo
                                </span>
                                <span class="badge badge-plan">
                                    <i class="fas fa-crown"></i>
                                    <?= htmlspecialchars(plano($_SESSION['usuario']['plano'])) ?? 'Não disponível'; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de Métricas Modernizado -->
                    <div class="metrics-grid">
                        <div class="metric-card metric-time">
                            <div class="metric-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Tempo Restante</span>
                                <span
                                    class="metric-value"><?= htmlspecialchars(diffData($_SESSION['usuario']['data_inicio'], $_SESSION['usuario']['data_termino'])) ?? 'Não disponível'; ?></span>
                            </div>
                        </div>

                        <div class="metric-card metric-instructor">
                            <div class="metric-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Instrutor</span>
                                <span
                                    class="metric-value"><?= htmlspecialchars((string) nomeInstrutor($_SESSION['usuario']['id'])) ?? 'Não disponível'; ?></span>
                            </div>
                        </div>

                        <div class="metric-card metric-unit">
                            <div class="metric-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Unidade</span>
                                <span
                                    class="metric-value"><?= htmlspecialchars($_SESSION['usuario']['unidade']) ?></span>
                            </div>
                        </div>

                        <div class="metric-card metric-workouts">
                            <div class="metric-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="metric-content">
                                <span class="metric-label">Treinos</span>
                                <span class="metric-value"><?= count($letrasTreino); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cronograma de Treinos Modernizado -->
            <div class="workout-schedule-card">
                <div class="workout-header">
                    <div class="workout-title">
                        <i class="fas fa-calendar-alt"></i>
                        <div>
                            <h3>Cronograma de Treinos</h3>
                            <p>Confira seu cronograma de treinos personalizado!</p>
                        </div>
                    </div>
                    <div class="workout-status">
                        <span class="status-badge status-active">
                            <i class="fas fa-check-circle"></i> Ativo
                        </span>
                    </div>
                </div>

                <!-- Abas de Treinos Modernizadas -->
                <div class="workout-tabs-container">
                    <div class="workout-tabs" id="dias-semana"></div>
                </div>

                <!-- Cards de Exercícios Modernizados -->
                <div class="workouts-container">
                    <?php foreach ($letrasTreino as $letraObj): ?>
                        <div class="workout-card-modern" id="treino<?= $letraObj['letra_treino'] ?>" style="display: none;">
                            <div class="workout-card-header">
                                <h4>
                                    <i class="fas fa-dumbbell"></i>
                                    Treino <?= $letraObj['letra_treino'] ?>
                                </h4>
                                <span class="exercise-count">
                                    <?php
                                    $exerciseCount = 0;
                                    foreach ($treinos as $treino) {
                                        if ($treino['letra_treino'] == $letraObj['letra_treino']) {
                                            $exerciseCount++;
                                        }
                                    }
                                    echo $exerciseCount;
                                    ?> exercícios
                                </span>
                            </div>

                            <div class="exercises-list">
                                <?php
                                $exerciseIndex = 1;
                                foreach ($treinos as $treino):
                                ?>
                                    <?php if ($treino['letra_treino'] == $letraObj['letra_treino']): ?>
                                        <?php if (isset($treino['nome_exercicio'])): ?>
                                            <?php $exercicioInfoArr = $treinosUser->getExerciciosById($treino['nome_exercicio']); ?>
                                            <?php $exercicioInfo = $exercicioInfoArr[0] ?? null; ?>
                                            <?php if ($exercicioInfo): ?>
                                                <div class="exercise-card">
                                                    <button class="complete-btn" title="Marcar concluído"
                                                        style="position:absolute;top:16px;right:16px;z-index:2;background:rgba(0,0,0,0.12);border:none;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.2s;">
                                                        <i class="fas fa-circle" style="font-size:20px;color:#fff;"></i>
                                                    </button>
                                                    <div class="exercise-header">
                                                        <div class="exercise-number"><?= $exerciseIndex++; ?></div>
                                                        <div class="exercise-info">
                                                            <h5 class="exercise-name">
                                                                <?= htmlspecialchars($exercicioInfo['exercicio'] ?? 'Exercício não informado') ?>
                                                            </h5>
                                                            <div class="exercise-category">
                                                                <?php
                                                                $category = '';
                                                                $exerciseName = strtolower($exercicioInfo['exercicio'] ?? '');
                                                                $category = $exercicioInfo['grupo_muscular'] ?? 'Outros';
                                                                ?>
                                                                <span class="category-badge category-<?= strtolower($category); ?>"><?= $category; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="exercise-stats">
                                                        <div class="stat-item">
                                                            <i class="fas fa-redo"></i>
                                                            <div>
                                                                <span class="stat-label">Séries</span>
                                                                <span class="stat-value"><?= htmlspecialchars($treino['series'] ?? '-') ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="stat-item">
                                                            <i class="fas fa-chart-line"></i>
                                                            <div>
                                                                <span class="stat-label">Repetições</span>
                                                                <span class="stat-value"><?= htmlspecialchars($treino['repeticoes'] ?? '-') ?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="exercise-description">
                                                        <i class="fas fa-info-circle"></i>
                                                        <div>
                                                            <span class="description-label">Instruções:</span>
                                                            <p class="description-text">
                                                                <?= htmlspecialchars($exercicioInfo['descricao'] ?? 'Sem instruções cadastradas.') ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="stat-exercise-image">
                                                        <?php if (!empty($exercicioInfo['url_da_imagem'])): ?>
                                                            <img src="<?= htmlspecialchars($exercicioInfo['url_da_imagem']) ?>"
                                                                alt="<?= htmlspecialchars($exercicioInfo['exercicio'] ?? '') ?>">
                                                        <?php else: ?>
                                                            <div class="exercise-placeholder">
                                                                <i class="fas fa-dumbbell"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>


                    <!-- Botões de Novo Treino -->
                    <div class="new-workout-section double-buttons">
                        <div class="new-workout-section">

                            <button class="new-workout-button"
                                onclick="window.location.href='./index.php?page=solicitacaoTreino'">
                                <i class="fas fa-plus"></i>
                                <span>Solicitar Novo Treino</span>
                            </button>

                            <button class="new-workout-button"
                                onclick="window.location.href='./index.php?page=telaCriarTreinoAluno'">
                                <i class="fas fa-pen-nib"></i>
                                <span>Criar Meu Próprio Treino</span>
                            </button>
                        </div>


                        <button class="new-workout-button"
                            onclick="window.location.href='/Gyga-fit-gym/controllers/gerarTreinoPdf.php?alunoId=<?= $_SESSION['usuario']['id'] ?>'">
                            <i class="fas fa-file-pdf"></i><span>Baixar Treino PDF</span>
                        </button>
                    </div>
                </div>
    </main>

    <script>
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) {
                console.log('Sidebar ou overlay não encontrados!');
                return;
            }
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Função para mostrar apenas o treino selecionado
        function mostrarTreino(treinoId) {
            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.workout-tab');
            buttons.forEach(button => button.classList.remove('active'));

            // Add active class to clicked button
            event.target.classList.add('active');

            // Show/hide workout cards
            const treinos = document.querySelectorAll('.workout-card-modern');
            treinos.forEach(function(card) {
                card.style.display = (card.id === treinoId) ? 'block' : 'none';
            });
        }

        // Gera os botões de treino dinamicamente
        function gerarBotoesTreino(letrasTreino) {
            const container = document.getElementById('dias-semana');
            letrasTreino.forEach(function(letraObj, index) {
                const button = document.createElement('button');
                button.innerHTML = '<i class="fas fa-dumbbell"></i> Treino ' + letraObj.letra_treino;
                button.className = 'workout-tab' + (index === 0 ? ' active' : '');
                button.onclick = function() {
                    mostrarTreino('treino' + letraObj.letra_treino);
                };
                container.appendChild(button);
            });
        }

        // Passa o array PHP para o JS
        const letrasTreino = <?php echo json_encode($letrasTreino); ?>;

        // Gera os botões e exibe o primeiro treino ao carregar a página
        window.onload = function() {
            gerarBotoesTreino(letrasTreino);
            if (letrasTreino.length > 0 && letrasTreino[0].letra_treino) {
                mostrarTreino('treino' + letrasTreino[0].letra_treino);
            }
        };

        // Adiciona animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.student-info-card, .workout-schedule-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in-up');
            });
        });

        // Alternar modo escuro instantaneamente
        document.addEventListener('DOMContentLoaded', function() {
            const darkBtn = document.getElementById('toggle-dark-mode');
            const body = document.body;
            // Carrega preferncia salva
            if (localStorage.getItem('gygafit-darkmode') === '1') {
                body.classList.add('dark-mode');
            } else {
                body.classList.remove('dark-mode');
            }
            function updateDarkBtn() {
                if (!darkBtn) return;
                if (body.classList.contains('dark-mode')) {
                    darkBtn.innerHTML = '<i class="fas fa-sun"></i> <span>Modo Claro</span>';
                } else {
                    darkBtn.innerHTML = '<i class="fas fa-moon"></i> <span>Modo Escuro</span>';
                }
            }
            if (darkBtn) {
                darkBtn.addEventListener('click', function() {
                    body.classList.toggle('dark-mode');
                    if (body.classList.contains('dark-mode')) {
                        localStorage.setItem('gygafit-darkmode', '1');
                    } else {
                        localStorage.removeItem('gygafit-darkmode');
                    }
                    updateDarkBtn();
                });
                updateDarkBtn();
            }
        });

        // Marca exercício como concluído
  document.addEventListener('click', e=>{
    if (e.target.closest('.complete-btn')) {
      const btn  = e.target.closest('.complete-btn');
      const card = btn.closest('.exercise-card');
      const icon = btn.querySelector('i');
      card.classList.toggle('completed');
      if (card.classList.contains('completed')) {
        icon.classList.replace('fa-circle','fa-check-circle');
      } else {
        icon.classList.replace('fa-check-circle','fa-circle');
      }
    }
  });
    </script>
</body>

</html>