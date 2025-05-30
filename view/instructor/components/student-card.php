<?php
/**
 * Componente: Card de aluno
 * Exibe informações e ações para um aluno específico
 */

$aluno = $dadosComponente['aluno'] ?? [];
$alunosOriginais = $dadosComponente['alunos_originais'] ?? [];

// Usar o controller para obter dados calculados do aluno
if (!empty($controller)) {
    $alunoCompleto = $controller->getDadosComponente('aluno-card', [
        'aluno' => $aluno,
        'alunos_originais' => $alunosOriginais
    ]);
} else {
    $alunoCompleto = $aluno;
}

$status = $alunoCompleto['status_atual'] ?? '';
$solicitacoes = $alunoCompleto['formularios'] ?? null;
$podecriarTreino = $alunoCompleto['pode_criar_treino'] ?? false;
?>

<div class="card-aluno">
    <div class="aluno-card-header">
        <div class="aluno-avatar-small">
            <?= strtoupper(substr($alunoCompleto['nome_aluno'] ?? 'A', 0, 1)) ?>
        </div>
        <div class="aluno-info">
            <h4 class="aluno-nome-card">
                <?= htmlspecialchars($alunoCompleto['nome_aluno'] ?? 'Nome não informado') ?>
                <?php if (!empty($status) && $status === 'em andamento'): ?>
                    <span class="notification-dot"></span>
                <?php endif; ?>
            </h4>
            <div class="aluno-meta">
                <span class="meta-item">
                    <i data-lucide="calendar" class="icon"></i>
                    <?= htmlspecialchars($alunoCompleto['data_solicitacao'] ?? 'Data não informada') ?>
                </span>
                <span class="meta-item">
                    <i data-lucide="activity" class="icon"></i>
                    <?= $alunoCompleto['count_solicitacoes'] ?? 0 ?> solicitações
                </span>
            </div>
        </div>
    </div>

    <!-- Container oculto da solicitação -->
    <?php if (!empty($solicitacoes)): ?>
        <div id="solicitacao-<?= $alunoCompleto['id_aluno'] ?>" class="solicitacao-details" style="display: none;">
            <?php foreach ($solicitacoes as $sol): ?>
                <div class="solicitacao-item">
                    <div class="solicitacao-header">
                        <h5>Solicitação de Treino</h5>
                        <span class="status-badge <?= strtolower(str_replace(' ', '-', $sol['status'])) ?>">
                            <?= htmlspecialchars($sol['status']) ?>
                        </span>
                    </div>
                    
                    <div class="solicitacao-content">
                        <div class="info-row">
                            <span class="label">Data:</span>
                            <span class="value"><?= htmlspecialchars($sol['data_created']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Experiência:</span>
                            <span class="value"><?= htmlspecialchars($sol['experiencia']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Objetivo:</span>
                            <span class="value"><?= htmlspecialchars($sol['objetivo']) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Treinos/semana:</span>
                            <span class="value"><?= htmlspecialchars($sol['treinos']) ?></span>
                        </div>
                        <?php if (!empty($sol['peso']) || !empty($sol['altura'])): ?>
                            <div class="info-row">
                                <span class="label">Físico:</span>
                                <span class="value">
                                    <?= !empty($sol['peso']) ? $sol['peso'] . 'kg' : '' ?>
                                    <?= !empty($sol['altura']) ? ' / ' . $sol['altura'] . 'cm' : '' ?>
                                    <?= !empty($sol['sexo']) ? ' / ' . ucfirst($sol['sexo']) : '' ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Ações do card -->
    <div class="aluno-actions">
        <?php if (!empty($solicitacoes)): ?>
            <button class="btn-visualizar" onclick="toggleSolicitacao('<?= $alunoCompleto['id_aluno'] ?>')">
                <i data-lucide="eye" class="btn-icon"></i>
                Visualizar
            </button>

            <?php if ($podecriarTreino): ?>
                <form action="./controllers/processarNovoTreino.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id_alunoNovoTreino" value="<?= $alunoCompleto['id_aluno'] ?>">
                    <button type="submit" name="submit_NovoTreino" class="btn-novo-treino">
                        <i data-lucide="plus-circle" class="btn-icon"></i>
                        Novo Treino
                    </button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
