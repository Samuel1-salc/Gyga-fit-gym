<?php
/**
 * Componente: Barra de busca e filtros
 * Interface para buscar e filtrar alunos
 */

$filtros = $dadosComponente['filtros'] ?? [];
$termoBusca = $filtros['search'] ?? '';
$statusFiltro = $filtros['status'] ?? '';

$opcoesStatus = [
    'todos' => 'Todos os alunos',
    'em andamento' => 'Pendentes',
    'atendido' => 'Atendidos'
];
?>

<div class="search-section">
    <form method="GET" class="search-form">
        <div class="search-group">
            <div class="search-input-wrapper">
                <i data-lucide="search" class="search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Buscar aluno por nome..." 
                    value="<?= htmlspecialchars($termoBusca) ?>"
                    class="search-input"
                >
            </div>
            
            <select name="status" class="filter-select">
                <?php foreach ($opcoesStatus as $valor => $label): ?>
                    <option value="<?= $valor ?>" <?= $statusFiltro === $valor ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="btn-search">
                <i data-lucide="filter" class="btn-icon"></i>
                Filtrar
            </button>
            
            <?php if (!empty($termoBusca) || (!empty($statusFiltro) && $statusFiltro !== 'todos')): ?>
                <a href="?" class="btn-clear">
                    <i data-lucide="x" class="btn-icon"></i>
                    Limpar
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>
