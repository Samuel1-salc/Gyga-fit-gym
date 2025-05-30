<?php

/**
 * Componente do formulário de criação de treino
 * @param array $grupos_musculares Lista de grupos musculares
 * @param array $exercicios Lista de exercícios
 */
?>

<!-- Container de Treinos -->
<div id="treinosContainer" class="treinos-container"></div>

<!-- Botões de Ação -->
<div class="action-buttons">
    <button type="button" class="botao-mais-treino" onclick="adicionarTreino()">
        <i data-lucide="plus" class="btn-icon"></i>
        Adicionar Novo Treino
    </button>

    <button type="submit" name="submit_plano" class="botao-progresso">
        <i data-lucide="check-circle" class="btn-icon"></i>
        Finalizar e Enviar Plano
    </button>
</div>

<!-- Dicas Rápidas -->
<div class="dicas-card">
    <h3 class="dicas-title">
        <i data-lucide="info" class="icon"></i>
        Dicas para um Plano Eficaz
    </h3>
    <div class="dicas-grid">
        <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Varie os exercícios para evitar adaptação</span>
        </div>
        <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Considere o nível de experiência do aluno</span>
        </div>
        <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Inclua tempo de descanso nas observações</span>
        </div>
        <div class="dica-item">
            <i data-lucide="check-circle" class="dica-icon"></i>
            <span>Ajuste a intensidade conforme o objetivo</span>
        </div>
    </div>
</div>

<script>
    // Dados para JavaScript
    const dadosTreino = {
        gruposMusculares: <?= json_encode($grupos_musculares, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
        exercicios: <?= json_encode($exercicios, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>
    };
</script>