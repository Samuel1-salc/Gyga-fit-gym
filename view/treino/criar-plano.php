<?php

/**
 * Página de criação de plano de treino - Versão refatorada
 * Seguindo princípios de código limpo e separação de responsabilidades
 */

// Incluir o controller
require_once __DIR__ . '/../../controllers/TreinoController.php';

// Instanciar o controller e processar a requisição
$controller = new TreinoController();

try {
    $dados = $controller->exibirPaginaCriacaoTreino();

    if ($controller->temErro()) {
        throw new Exception($controller->getErro());
    }

    // Extrair dados para a view
    $aluno = $dados['aluno'];
    $grupos_musculares = $dados['grupos_musculares'];
    $exercicios = $dados['exercicios'];
} catch (Exception $e) {
    // Em caso de erro, redirecionar ou exibir mensagem
    $erro = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Criar Plano de Treino - GYGA FIT</title>
    <link rel="stylesheet" href="../style/stylePaginaDeTreino.css?v=<?= time(); ?>" />
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        /* Estilos adicionais para ícones e melhorias */
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

        .error-message {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <!-- Header Profissional -->
    <header>
        <div class="header-logo">
            <div class="header-icon-container">
                <i data-lucide="dumbbell" class="header-icon"></i>
            </div>
            <div>
                <h1>GYGA FIT</h1>
                <p class="header-subtitle">Sistema de Treinamento</p>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-header">
                <i data-lucide="eye" class="btn-icon"></i>
                Preview
            </button>
            <button type="button" class="btn-header">
                <i data-lucide="save" class="btn-icon"></i>
                Salvar Rascunho
            </button>
        </div>
    </header>

    <div class="container">
        <!-- Título da Página -->
        <div class="page-title">
            <h2>Criar Plano de Treino</h2>
            <p class="page-subtitle">Desenvolva um programa personalizado para seu aluno</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="error-message">
                <i data-lucide="alert-circle" class="icon"></i>
                <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
            </div>
            <div class="action-buttons">
                <a href="../perfilInstrutor.php" class="botao-progresso">
                    <i data-lucide="arrow-left" class="btn-icon"></i>
                    Voltar ao Painel
                </a>
            </div>
        <?php else: ?>
            <form id="formPlano" action="../../controllers/processarNovoTreino.php" method="POST">
                <!-- Informações do Aluno -->
                <?php include __DIR__ . '/components/aluno-info.php'; ?>

                <!-- Formulário de Treino -->
                <?php include __DIR__ . '/components/treino-form.php'; ?>
            </form>

            <!-- Mensagem de Sucesso -->
            <div id="mensagemSucesso" class="mensagem-sucesso">
                <i data-lucide="check-circle" class="icon"></i>
                Plano de treino criado com sucesso!
            </div>
        <?php endif; ?>
    </div>

    <!-- JavaScript -->
    <script src="assets/treino-form.js"></script>
</body>

</html>