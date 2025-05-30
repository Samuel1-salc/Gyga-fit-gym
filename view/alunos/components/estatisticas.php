<?php
/**
 * Componente para exibir estatísticas dos alunos
 * @param array $estatisticas Dados estatísticos dos alunos
 */
?>

<?php if ($estatisticas): ?>
<div class="estatisticas-container">
    <div class="estatistica-card total">
        <div class="estatistica-numero"><?= $estatisticas['total'] ?></div>
        <div class="estatistica-label">Total de Alunos</div>
    </div>
    
    <div class="estatistica-card disponiveis">
        <div class="estatistica-numero"><?= $estatisticas['disponiveis'] ?></div>
        <div class="estatistica-label">Disponíveis</div>
    </div>
    
    <div class="estatistica-card indisponiveis">
        <div class="estatistica-numero"><?= $estatisticas['indisponiveis'] ?></div>
        <div class="estatistica-label">Com Instrutor</div>
    </div>
    
    <div class="planos-resumo">
        <h4>Distribuição por Plano:</h4>
        <div class="planos-grid">
            <?php foreach ($estatisticas['planos'] as $plano => $quantidade): ?>
                <div class="plano-item">
                    <span class="plano-nome"><?= $plano ?></span>
                    <span class="plano-quantidade"><?= $quantidade ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
