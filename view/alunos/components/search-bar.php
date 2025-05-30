<?php
/**
 * Componente da barra de busca de alunos
 * @param string|null $termoBusca Termo atual da busca
 */
?>

<form method="GET" action="" class="search-bar">
    <div class="search-container">
        <input 
            type="text" 
            name="search" 
            placeholder="Pesquisar aluno pelo nome..." 
            value="<?= htmlspecialchars($termoBusca ?? '') ?>"
            class="search-input"
        >
        <button type="submit" class="search-button">
            <i class="search-icon">🔍</i>
            Pesquisar
        </button>
        
        <?php if (!empty($termoBusca)): ?>
            <a href="?" class="clear-search">
                <i class="clear-icon">✖</i>
                Limpar
            </a>
        <?php endif; ?>
    </div>
</form>
