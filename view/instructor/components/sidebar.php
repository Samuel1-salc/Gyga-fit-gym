<?php
/**
 * Componente: Sidebar de navegação
 * Menu lateral para navegação do instrutor
 */

$instrutor = $dadosComponente['instrutor'] ?? [];
?>

<div class="sidebar">
    <div class="sidebar-header">
        <h3>
            <i data-lucide="settings" class="icon"></i>
            Menu
        </h3>
    </div>
    
    <nav class="sidebar-nav">
        <a href="./view/editar_perfil_instrutor.php" class="sidebar-link">
            <i data-lucide="user-cog" class="icon"></i>
            <span>Alterar Perfil</span>
        </a>
        
        <a href="./configuracoes.php" class="sidebar-link">
            <i data-lucide="settings" class="icon"></i>
            <span>Configurações</span>
        </a>
        
        <a href="./view/alunos/lista.php" class="sidebar-link">
            <i data-lucide="user-plus" class="icon"></i>
            <span>Adicionar Alunos</span>
        </a>
        
        <a href="./index.php?page=telaInicial" class="sidebar-link">
            <i data-lucide="home" class="icon"></i>
            <span>Menu da Academia</span>
        </a>
        
        <a href="./view/logout.php" class="sidebar-link logout">
            <i data-lucide="log-out" class="icon"></i>
            <span>Sair</span>
        </a>
    </nav>
</div>
