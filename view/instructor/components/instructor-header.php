<?php
/**
 * Componente: Header do perfil do instrutor
 * Exibe informações básicas e foto do instrutor
 */

$instrutor = $dadosComponente['instrutor'] ?? [];
$metricas = $dadosComponente['metricas'] ?? [];
?>

<div class="perfil-instrutor">
    <div class="instrutor-header">
        <div class="instrutor-avatar">
            <?= strtoupper(substr($instrutor['username'] ?? 'I', 0, 1)) ?>
        </div>
        <div class="instrutor-info">
            <h1 class="instrutor-nome"><?= htmlspecialchars($instrutor['username'] ?? 'Instrutor') ?></h1>
            <p class="instrutor-email"><?= htmlspecialchars($instrutor['email'] ?? '') ?></p>
            <div class="instrutor-badges">
                <span class="badge unidade">
                    <i data-lucide="map-pin" class="icon"></i>
                    <?= htmlspecialchars($instrutor['unidade'] ?? 'Unidade não informada') ?>
                </span>
                <span class="badge disponibilidade <?= $metricas['disponibilidade'] === 'disponível' ? 'disponivel' : 'indisponivel' ?>">
                    <i data-lucide="<?= $metricas['disponibilidade'] === 'disponível' ? 'check-circle' : 'clock' ?>" class="icon"></i>
                    <?= ucfirst($metricas['disponibilidade'] ?? 'disponível') ?>
                </span>
            </div>
        </div>
    </div>
</div>
