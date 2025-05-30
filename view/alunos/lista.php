<?php

/**
 * Página de listagem de alunos - Versão refatorada
 * Seguindo princípios de código limpo e separação de responsabilidades
 */

// Incluir o controller
require_once __DIR__ . '/../../controllers/AlunoController.php';

// Instanciar o controller e processar a requisição
$controller = new AlunoController();

try {
    $dados = $controller->exibirListaAlunos();

    if ($controller->temErro()) {
        throw new Exception($controller->getErro());
    }

    // Extrair dados formatados para a view
    $dadosFormatados = $controller->getDadosFormatados();
    $alunos = $dadosFormatados['alunos'];
    $termoBusca = $dadosFormatados['termo_busca'];
    $estatisticas = $dadosFormatados['estatisticas'];
    $mensagemBusca = $controller->getMensagemBusca();
} catch (Exception $e) {
    $erro = $e->getMessage();
    $alunos = [];
    $termoBusca = null;
    $estatisticas = null;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos - Painel do Instrutor | GYGA FIT</title>    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/listaAlunos.css?v=<?= time(); ?>">
    
    <!-- Adicionando ícones Lucide via CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        .icon {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
        }
        
        .error-message {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #ef4444;
            color: #dc2626;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            text-align: center;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        
        .message-banner {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #3b82f6;
            color: #1e40af;
            padding: 16px 20px;
            border-radius: 12px;
            margin: 20px 0;
            text-align: center;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            margin: 32px 0;
        }

        .estatistica-label {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .card-aluno {
            border-left: 4px solid #ddd;
            transition: all 0.3s ease;
        }

        .card-aluno.disponivel {
            border-left-color: #27ae60;
        }

        .card-aluno.indisponivel {
            border-left-color: #e74c3c;
            opacity: 0.8;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-disponivel {
            background: #d4edda;
            color: #155724;
        }

        .status-indisponivel {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-adicionar {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-adicionar:hover {
            background: #219a52;
        }

        .message-banner {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="user-icon"></div>
            <div class="logo">
                <img src="../img/logo.png" alt="Gyga Fit Logo" class="logo-img">
            </div>
            <button class="header-button" onclick="toggleSidebar()">☰</button>
        </div>
    </header>

    <div class="main-container">
        <div class="page-header">
            <h3>Gerenciar Alunos</h3>
            <p class="page-subtitle">Visualize e adicione alunos ao seu grupo de treinamento</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="error-message">
                <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
            </div>
            <div class="action-buttons">
                <a href="../perfilInstrutor.php" class="btn-back">
                    ← Voltar ao Painel
                </a>
            </div>
        <?php else: ?>
            <!-- Barra de Busca -->
            <?php include __DIR__ . '/components/search-bar.php'; ?>

            <!-- Mensagem de resultado -->
            <?php if (isset($mensagemBusca)): ?>
                <div class="message-banner">
                    <?= htmlspecialchars($mensagemBusca) ?>
                </div>
            <?php endif; ?>

            <!-- Estatísticas (apenas quando não há busca específica) -->
            <?php if (empty($termoBusca)): ?>
                <?php include __DIR__ . '/components/estatisticas.php'; ?>
            <?php endif; ?>

            <!-- Lista de Alunos -->
            <?php include __DIR__ . '/components/lista-alunos.php'; ?>
        <?php endif; ?>
    </div>

    <script>
        // Função para o toggle do sidebar (se necessário)
        function toggleSidebar() {
            // Implementar se houver sidebar
            console.log('Toggle sidebar');
        }

        // Auto-focus no campo de busca
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }
        });
    </script>
</body>

</html>