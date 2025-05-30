<?php
/**
 * Componente: Dashboard de métricas
 * Exibe estatísticas principais do instrutor
 */

$metricas = $dadosComponente['metricas'] ?? [];
?>

<div class="instrutor-metricas">
    <div class="metrica-card alunos">
        <div class="metrica-icon">
            <i data-lucide="users" class="metric-icon"></i>
        </div>
        <div class="metrica-content">
            <div class="metrica-numero"><?= $metricas['total_alunos'] ?? 0 ?></div>
            <div class="metrica-label">Alunos Ativos</div>
        </div>
    </div>

    <div class="metrica-card pendentes">
        <div class="metrica-icon">
            <i data-lucide="clock" class="metric-icon"></i>
        </div>
        <div class="metrica-content">
            <div class="metrica-numero"><?= $metricas['alunos_pendentes'] ?? 0 ?></div>
            <div class="metrica-label">Pendentes</div>
        </div>
    </div>

    <div class="metrica-card atendidos">
        <div class="metrica-icon">
            <i data-lucide="check-circle" class="metric-icon"></i>
        </div>
        <div class="metrica-content">
            <div class="metrica-numero"><?= $metricas['alunos_atendidos'] ?? 0 ?></div>
            <div class="metrica-label">Atendidos</div>
        </div>
    </div>

    <div class="metrica-card disponibilidade">
        <div class="metrica-icon">
            <i data-lucide="<?= ($metricas['disponibilidade'] ?? 'disponível') === 'disponível' ? 'user-check' : 'user-x' ?>" class="metric-icon"></i>
        </div>
        <div class="metrica-content">
            <div class="metrica-texto"><?= ucfirst($metricas['disponibilidade'] ?? 'Disponível') ?></div>
            <div class="metrica-label">Status</div>
        </div>
    </div>
</div>
