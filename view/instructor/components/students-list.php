<?php
/**
 * Componente: Lista de alunos
 * Renderiza a grade de cards dos alunos
 */

$alunos = $dadosComponente['alunos'] ?? [];
$alunosOriginais = $dadosComponente['alunos_originais'] ?? [];
$mensagemFiltro = $dadosComponente['mensagem_filtro'] ?? '';
$temAlunos = !empty($alunos);
?>

<div class="alunos-section">
    <div class="section-header">
        <h2 class="section-title">
            <i data-lucide="users" class="header-icon"></i>
            Meus Alunos
        </h2>
    </div>

    <!-- Mensagem de filtro/resultado -->
    <?php if (!empty($mensagemFiltro)): ?>
        <?= $mensagemFiltro ?>
    <?php endif; ?>

    <?php if ($temAlunos): ?>
        <div class="alunos-grid">
            <?php foreach ($alunos as $aluno): ?>
                <?php 
                // Preparar dados para o componente do card
                $dadosCard = [
                    'aluno' => $aluno,
                    'alunos_originais' => $alunosOriginais
                ];
                
                // Incluir o componente do card
                $dadosComponente = $dadosCard;
                include __DIR__ . '/student-card.php';
                ?>
            <?php endforeach; ?>
        </div>
    <?php elseif (empty($mensagemFiltro)): ?>
        <!-- Estado vazio quando não há alunos -->
        <div class="empty-state">
            <div class="empty-icon">
                <i data-lucide="users-x" class="icon-large"></i>
            </div>
            <h3>Nenhum aluno encontrado</h3>
            <p>Você ainda não possui alunos cadastrados ou os filtros aplicados não retornaram resultados.</p>
            <a href="./view/alunos/lista.php" class="btn-primary">
                <i data-lucide="user-plus" class="btn-icon"></i>
                Adicionar Alunos
            </a>
        </div>
    <?php endif; ?>
</div>
