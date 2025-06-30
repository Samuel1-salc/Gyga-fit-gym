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
        <!-- Estilos do marcador de exercício concluído -->
    <style>
      .complete-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #ccc;
      }
      .exercise-card {
        position: relative;
      }
      .exercise-card.completed {
        opacity: 0.6;
      }
      .exercise-card.completed .exercise-name {
        text-decoration: line-through;
      }
      .exercise-card.completed .complete-btn {
        color: #2ecc71;
      }
    </style>

    <!-- Dark mode styles -->
<style>
  /* Variáveis de cor */
  :root {
    --bg: #fff;
    --fg: #333;
    --card-bg: #fafafa;
    --accent: #2ecc71;
  }
  body.dark-mode {
    --bg: #222;
    --fg: #eee;
    --card-bg: #2c2c2c;
    --accent: #9b59b6;
  }
/* aplica cor clara somente nos containers principais (não nas métricas) */
body.dark-mode .header-modern,
body.dark-mode .sidebar-modern,
body.dark-mode .student-info-card,
body.dark-mode .workout-schedule-card,
body.dark-mode .exercise-card,
body.dark-mode .workout-tab,
body.dark-mode .workout-tabs-container button {
  background-color: var(--card-bg) !important;
  color: var(--fg) !important;
}

/* sidebar links */
body.dark-mode .sidebar-modern .sidebar-link {
  color: var(--fg) !important;
}

/* badges/accentos continuam legíveis */
body.dark-mode .badge-plan,
body.dark-mode .badge-active {
  background-color: var(--accent) !important;
  color: #fff !important;
}

/* restaura o estilo padrão só dentro da área de métricas */
body.dark-mode .metrics-grid * {
  color: inherit !important;
  background: none !important;
}

  /* Aplicando as variáveis */
  body {
    background-color: var(--bg);
    color: var(--fg);
  }
  .header-modern,
  .sidebar-modern,
  .student-info-card,
  .workout-schedule-card,
  .exercise-card {
    background-color: var(--card-bg);
  }
  .badge-plan {
    background-color: var(--accent);
    color: #fff;
  }
  /* …repita para outros elementos que precisem inverter cores… */
  /* Ajustes de contraste no dark mode */
body.dark-mode {
  --bg: #222;
  --fg: #eee;
  --card-bg: #2c2c2c;
  --accent: #9b59b6;
}

/* forçar texto claro em todos títulos, parágrafos e botões */
body.dark-mode *,
body.dark-mode .sidebar-link,
body.dark-mode .notification-button,
body.dark-mode button,
body.dark-mode h1,
body.dark-mode h2,
body.dark-mode h3,
body.dark-mode h4,
body.dark-mode h5,
body.dark-mode h6,
body.dark-mode p,
body.dark-mode span,
body.dark-mode a {
  color: var(--fg) !important;
}

/* e garantir que os cartões e abas fiquem legíveis */
body.dark-mode .workout-tab,
body.dark-mode .workout-tabs-container button,
body.dark-mode .workout-card-modern,
body.dark-mode .exercise-card {
  background-color: var(--card-bg) !important;
  color: var(--fg) !important;
}

/* sidebar */
body.dark-mode .sidebar-modern {
  background-color: var(--card-bg) !important;
}
body.dark-mode .sidebar-modern .sidebar-link {
  color: var(--fg) !important;
}

/* indicadores/accentos */
body.dark-mode .badge-plan,
body.dark-mode .badge-active {
  background-color: var(--accent) !important;
  color: #fff !important;
}

</style>

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
    <!-- 1) botão de notificações (inalterado) -->
    <button class="notification-button">
        <i class="fas fa-bell"></i>
        <span class="notification-badge">3</span>
    </button>

    <!-- 2) botão de modo escuro adicionado -->
    <button id="darkToggle" class="notification-button" title="Modo escuro" style="margin-left: 0.5rem;">
        <i class="fas fa-moon"></i>
    </button>

    <!-- 3) avatar do usuário (inalterado) -->
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
            <a href="./configuracoes.php" class="sidebar-link">
                <i class="fas fa-cog"></i>
                <span>Configurações</span>
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
                                        <?php $exercicioInfoArr = $treinosUser->getExerciciosById($treino['nome_exercicio']); ?>
                                        <?php $exercicioInfo = $exercicioInfoArr[0] ?? null; ?>
                                        <?php if ($exercicioInfo): ?>
                                            <div class="exercise-card">
                                                <button class="complete-btn" title="Marcar concluído">
  <i class="fas fa-circle"></i>
</button>

                                                <div class="exercise-header">
                                                    <div class="exercise-number"><?= $exerciseIndex++; ?></div>
                                                    <div class="exercise-info">
                                                        <h5 class="exercise-name">
                                                            <?= htmlspecialchars($exercicioInfo['nome_exercicio']) ?>
                                                        </h5>
                                                        <div class="exercise-category">
                                                            <?php
                                                            $category = '';
                                                            $exerciseName = strtolower($exercicioInfo['nome_exercicio']);

                                                            $category = $exercicioInfo['grupo_muscular'] ?? 'Outros';

                                                            ?>
                                                            <span
                                                                class="category-badge category-<?= strtolower($category); ?>"><?= $category; ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="exercise-stats">
                                                    <div class="stat-item">
                                                        <i class="fas fa-redo"></i>
                                                        <div>
                                                            <span class="stat-label">Séries</span>
                                                            <span class="stat-value"><?= htmlspecialchars($treino['series']) ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="stat-item">
                                                        <i class="fas fa-chart-line"></i>
                                                        <div>
                                                            <span class="stat-label">Repetições</span>
                                                            <span
                                                                class="stat-value"><?= htmlspecialchars($treino['repeticoes']) ?></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="exercise-description">
                                                    <i class="fas fa-info-circle"></i>
                                                    <div>
                                                        <span class="description-label">Instruções:</span>
                                                        <p class="description-text">
                                                            <?= htmlspecialchars($exercicioInfo['descricao_exercicio']) ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="stat-exercise-image">
                                                    <?php if (!empty($exercicioInfo['url_img'])): ?>
                                                        <img src="<?= htmlspecialchars($exercicioInfo['url_img']) ?>"
                                                            alt="<?= htmlspecialchars($exercicioInfo['nome_exercicio']) ?>">
                                                    <?php else: ?>
                                                        <div class="exercise-placeholder">
                                                            <i class="fas fa-dumbbell"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
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


                    <!-- Footer Modernizado -->
                    <div class="footer-modern">
                        <div class="footer-content">
                            <div class="footer-brand">
                                <div class="footer-logo">
                                    <i class="fas fa-dumbbell"></i>
                                </div>
                                <div class="footer-text">
                                    <h4>GYGA FIT</h4>
                                    <p>Transformando vidas através do fitness</p>
                                </div>
                            </div>

                            <div class="footer-center">
                                <div class="footer-links">
                                    <a href="#"><i class="fas fa-phone"></i> Fale Conosco</a>
                                    <span class="divider">|</span>
                                    <a href="#"><i class="fas fa-shield-alt"></i> Política de Privacidade</a>
                                </div>
                            </div>

                            <div class="footer-social">
                                <p>Siga-nos nas redes sociais</p>
                                <div class="social-links-modern">
                                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </main>

    <script>
        window.toggleSidebar = function () {
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
            treinos.forEach(function (card) {
                card.style.display = (card.id === treinoId) ? 'block' : 'none';
            });
        }

        // Gera os botões de treino dinamicamente
        function gerarBotoesTreino(letrasTreino) {
            const container = document.getElementById('dias-semana');
            letrasTreino.forEach(function (letraObj, index) {
                const button = document.createElement('button');
                button.innerHTML = '<i class="fas fa-dumbbell"></i> Treino ' + letraObj.letra_treino;
                button.className = 'workout-tab' + (index === 0 ? ' active' : '');
                button.onclick = function () {
                    mostrarTreino('treino' + letraObj.letra_treino);
                };
                container.appendChild(button);
            });
        }

        // Passa o array PHP para o JS
        const letrasTreino = <?php echo json_encode($letrasTreino); ?>;

        // Gera os botões e exibe o primeiro treino ao carregar a página
        window.onload = function () {
            gerarBotoesTreino(letrasTreino);
            if (letrasTreino.length > 0 && letrasTreino[0].letra_treino) {
                mostrarTreino('treino' + letrasTreino[0].letra_treino);
            }
        };

        // Adiciona animações de entrada
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.student-info-card, .workout-schedule-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in-up');
            });
        });
    </script>
    <script>
  document.getElementById('darkToggle').addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    // opcional: trocar ícone
    const ico = document.querySelector('#darkToggle i');
    ico.classList.toggle('fa-moon');
    ico.classList.toggle('fa-sun');
  });
</script>

<script>
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