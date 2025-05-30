<?php
/**
 * Componente para exibir a lista de alunos
 * @param array $alunos Lista de alunos
 */
?>

<div class="solicitacoes">
    <?php if (empty($alunos)): ?>
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h3>Nenhum aluno encontrado</h3>
            <p>Tente ajustar os filtros de busca ou verifique se há alunos cadastrados no sistema.</p>
        </div>
    <?php else: ?>
        <?php foreach (array_reverse($alunos) as $aluno): ?>
            <div class="card-aluno <?= $aluno['disponivel'] ? 'disponivel' : 'indisponivel' ?>">
                <div class="card-info">
                    <div class="aluno-header">
                        <h3 class="aluno-nome"><?= htmlspecialchars($aluno['username']) ?></h3>
                        <span class="status-badge <?= $aluno['disponivel'] ? 'status-disponivel' : 'status-indisponivel' ?>">
                            <?= $aluno['disponibilidade'] ?>
                        </span>
                    </div>
                    
                    <div class="aluno-detalhes">
                        <div class="detalhe-item">
                            <span class="detalhe-label">📧 Contato:</span>
                            <span class="detalhe-valor"><?= htmlspecialchars($aluno['email']) ?></span>
                        </div>
                        
                        <div class="detalhe-item">
                            <span class="detalhe-label">💳 Plano:</span>
                            <span class="detalhe-valor plano-<?= strtolower($aluno['plano_nome']) ?>">
                                <?= htmlspecialchars($aluno['plano_nome']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-actions">
                    <?php if ($aluno['disponivel']): ?>
                        <form method="POST" action="../controllers/processsarAdicionarAluno.php" class="form-adicionar">
                            <input type="hidden" name="id_aluno" value="<?= htmlspecialchars($aluno['id']) ?>">
                            <button type="submit" class="btn-adicionar" onclick="return confirmarAdicao('<?= htmlspecialchars($aluno['username']) ?>')">
                                <span class="btn-icon">➕</span>
                                Adicionar Aluno
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="aluno-indisponivel">
                            <span class="indisponivel-icon">🔒</span>
                            <span class="indisponivel-text">Já possui instrutor</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function confirmarAdicao(nomeAluno) {
    return confirm(`Tem certeza que deseja adicionar ${nomeAluno} como seu aluno?`);
}
</script>
