<?php

/**
 * Componente para exibir informações do aluno
 * @param array $aluno Dados do aluno
 */
?>

<div class="card-aluno">
    <div class="aluno-header">
        <div class="aluno-avatar">
            <div class="avatar-circle">
                <?= strtoupper(substr($aluno['nome'], 0, 1)) ?>
            </div>
        </div>
        <div class="aluno-info-header">
            <h3 class="aluno-nome"><?= htmlspecialchars($aluno['nome']) ?></h3>
            <div class="aluno-badges">
                <span class="badge badge-primary">Aluno Ativo</span>
            </div>
        </div>
    </div>

    <div class="aluno-metricas">
        <div class="metrica-card dias">
            <div class="metrica-icon">
                <i data-lucide="calendar" class="metric-icon"></i>
            </div>
            <div class="metrica-content">
                <div class="metrica-numero"><?= htmlspecialchars($aluno['treinos']) ?></div>
                <div class="metrica-label">Dias por Semana</div>
            </div>
        </div>

        <div class="metrica-card objetivo">
            <div class="metrica-icon">
                <i data-lucide="target" class="metric-icon"></i>
            </div>
            <div class="metrica-content">
                <div class="metrica-numero"><?= htmlspecialchars($aluno['objetivo']) ?></div>
                <div class="metrica-label">Objetivo</div>
            </div>
        </div>

        <div class="metrica-card experiencia">
            <div class="metrica-icon">
                <i data-lucide="trending-up" class="metric-icon"></i>
            </div>
            <div class="metrica-content">
                <div class="metrica-numero"><?= htmlspecialchars($aluno['experiencia']) ?></div>
                <div class="metrica-label">Experiência</div>
            </div>
        </div>
    </div>

    <!-- Campos ocultos para o formulário -->
    <input type="hidden" name="id_solicitacao" value="<?= $aluno['id_solicitacao'] ?>">
    <input type="hidden" name="id_aluno" value="<?= $aluno['id'] ?>">
</div>