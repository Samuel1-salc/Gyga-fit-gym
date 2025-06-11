<?php

/**
 * perfilInstrutor.php
 * Página de perfil para instrutores com design moderno e funcionalidade de agendamentos.
 */

require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
require_once __DIR__ . '/../models/Agendamento.class.php'; // Inclusão do modelo de Agendamento
require_once __DIR__ . '/../controllers/AgendamentoController.php'; // Inclusão do controlador de Agendamento

$instrutor = $_SESSION['usuario'];
$alunoInstrutor = new aluno_instrutor();
$solicitacaoTreino = new SolicitacaoTreino();
$agendamentoController = new AgendamentoController(); // Instância do controlador de agendamentos
$alunoOriginal = $alunoInstrutor->getAlunosByIdInstrutor($instrutor['id']);
$aluno = $alunoOriginal;

if (!empty($alunoOriginal)) {
    $alunosUnicos = [];
    $contadorSemId = 0;
    foreach ($alunoOriginal as $item) {
        if (!empty($item['id_aluno'])) {
            $alunosUnicos[$item['id_aluno']] = true;
        } else {
            $chaveUnica = 'sem_id_' . $contadorSemId . '_' . $item['nome_aluno'];
            $alunosUnicos[$chaveUnica] = true;
            $contadorSemId++;
        }
    }
    $countAlunos = count($alunosUnicos);
    $data_saida = $instrutor['data_saida'] ?? null;
    $disponibilidade = ($data_saida && $data_saida != '0000-00-00') ? "indisponível" : "disponível";

    // Funções existentes...

    // Função para listar agendamentos
    function listarAgendamentos($id_instrutor) {
        global $agendamentoController;
        return $agendamentoController->listarAgendamentos($id_instrutor);
    }

    // Função para criar um novo agendamento
    function criarAgendamento($id_aluno, $data) {
        global $agendamentoController;
        return $agendamentoController->criarAgendamento($id_aluno, $data);
    }

    // Função para cancelar um agendamento
    function cancelarAgendamento($id_agendamento) {
        global $agendamentoController;
        return $agendamentoController->cancelarAgendamento($id_agendamento);
    }

    // --- Código para exibir agendamentos e permitir criação de novos agendamentos ---
    $agendamentos = listarAgendamentos($instrutor['id']);
    // Aqui você pode adicionar o código HTML para exibir os agendamentos e um formulário para criar novos agendamentos

} else {
    $countAlunos = 0;
    $mensagemFiltro = '<div class="alert alert-error"><i data-lucide="users-x" class="icon"></i><strong>Nenhum aluno encontrado.</strong></div>';
    $alunoOriginal = [];
    $aluno = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Instrutor - GYGA FIT</title>
    <link rel="stylesheet" href="./view/style/perfilInstrutor.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="./view/style/agendamentos.css?v=<?= time(); ?>"> <!-- Inclusão do CSS de agendamentos -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="./public/js/calendario.js"></script> <!-- Inclusão do JS do calendário -->
    <style>
        /* Estilos adicionais, se necessário */
    </style>
</head>

<body>
    <!-- Header e Sidebar -->
    <!-- Código existente para o header e sidebar -->

    <div class="main-content">
        <!-- Perfil do Instrutor -->
        <!-- Código existente para o perfil do instrutor -->

        <!-- Seção de Agendamentos -->
        <div class="agendamentos-section">
            <h3>Agendamentos</h3>
            <div id="calendario"></div> <!-- Calendário dinâmico -->
            <form id="form-agendamento" method="POST" action="">
                <select name="aluno" required>
                    <option value="">Selecione um aluno</option>
                    <?php foreach ($alunoOriginal as $aluno): ?>
                        <option value="<?= $aluno['id_aluno'] ?>"><?= htmlspecialchars($aluno['nome_aluno']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="date" name="data" required>
                <button type="submit">Agendar Consulta</button>
            </form>
            <div class="agendamentos-lista">
                <h4>Meus Agendamentos</h4>
                <?php if (!empty($agendamentos)): ?>
                    <ul>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <li>
                                <?= htmlspecialchars($agendamento['data']) ?> - <?= htmlspecialchars($agendamento['nome_aluno']) ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="id_agendamento" value="<?= $agendamento['id'] ?>">
                                    <button type="submit" name="cancelar">Cancelar</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Nenhum agendamento encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <img src="./view/img/logo.png" alt="Logo GYGA FIT" class="footer-logo">
            <p>&copy; <?php echo date('Y'); ?> GYGA FIT. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Inicialização do calendário
        document.addEventListener("DOMContentLoaded", function() {
            // Código para inicializar o calendário
        });
    </script>
</body>

</html>