<?php

/**
 * Página do Painel do Instrutor - Versão refatorada
 * Seguindo princípios de código limpo e separação de responsabilidades
 */

// Incluir o controller
require_once __DIR__ . '/../../controllers/InstructorController.php';

// Instanciar o controller e processar a requisição
$controller = new InstructorController();

try {
    $dados = $controller->exibirPainelInstrutor();

    if ($controller->temErro()) {
        throw new Exception($controller->getErro());
    }

    // Extrair dados formatados para a view
    $dadosFormatados = $controller->getDadosFormatados();
    $instrutor = $dadosFormatados['instrutor'];
    $alunos = $dadosFormatados['alunos'];
    $alunosOriginais = $dadosFormatados['alunos_originais'];
    $metricas = $dadosFormatados['metricas'];
    $mensagemFiltro = $dadosFormatados['mensagem_filtro'];
    $filtros = $dadosFormatados['filtros'];
    $temAlunos = $dadosFormatados['tem_alunos'];
} catch (Exception $e) {
    // Em caso de erro, definir valores padrão
    $erro = $e->getMessage();
    $instrutor = $_SESSION['usuario'] ?? null;
    $alunos = [];
    $alunosOriginais = [];
    $metricas = ['total_alunos' => 0, 'alunos_pendentes' => 0, 'alunos_atendidos' => 0, 'disponibilidade' => 'disponível'];
    $mensagemFiltro = '<div class="alert alert-error"><i data-lucide="alert-triangle" class="icon"></i><strong>Erro ao carregar dados: ' . htmlspecialchars($erro) . '</strong></div>';
    $filtros = [];
    $temAlunos = false;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Instrutor - GYGA FIT</title>
    <link rel="stylesheet" href="../../view/style/perfilInstrutor.css?v=<?= time(); ?>">
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

        .icon-large {
            width: 48px;
            height: 48px;
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state h3 {
            margin: 20px 0 10px;
            color: #374151;
        }

        .empty-state p {
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .notification-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 50%;
            margin-left: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <?php
        $dadosComponente = ['instrutor' => $instrutor];
        include __DIR__ . '/components/sidebar.php';
        ?>

        <div class="main-content">
            <!-- Header do Perfil do Instrutor -->
            <?php
            $dadosComponente = [
                'instrutor' => $instrutor,
                'metricas' => $metricas
            ];
            include __DIR__ . '/components/instructor-header.php';
            ?>

            <!-- Dashboard de Métricas -->
            <?php
            $dadosComponente = ['metricas' => $metricas];
            include __DIR__ . '/components/metrics-dashboard.php';
            ?>

            <!-- Barra de Busca e Filtros -->
            <?php
            $dadosComponente = ['filtros' => $filtros];
            include __DIR__ . '/components/search-filters.php';
            ?>

            <!-- Lista de Alunos -->
            <?php
            $dadosComponente = [
                'alunos' => $alunos,
                'alunos_originais' => $alunosOriginais,
                'mensagem_filtro' => $mensagemFiltro
            ];
            include __DIR__ . '/components/students-list.php';
            ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Inicializar ícones Lucide
        lucide.createIcons();

        // Função para alternar visualização de solicitações
        function toggleSolicitacao(idAluno) {
            const elemento = document.getElementById('solicitacao-' + idAluno);
            if (elemento) {
                elemento.style.display = elemento.style.display === 'none' ? 'block' : 'none';
            }
        }

        // Função para a sidebar responsiva
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }

        // Auto-submit do formulário quando mudar o select de status
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.querySelector('.filter-select');
            if (filterSelect) {
                filterSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });

        // Animações suaves para os cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-aluno');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in');
            });
        });
    </script>

    <style>
        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -300px;
                transition: left 0.3s ease;
                z-index: 1000;
            }

            .sidebar.active {
                left: 0;
            }
        }
    </style>
</body>

</html>