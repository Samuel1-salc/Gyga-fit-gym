<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Agendamento.class.php';

// Verifica se os campos obrigatórios foram preenchidos
if (!empty($_POST['aluno']) && !empty($_POST['data_hora'])) {
    $id_aluno = $_POST['aluno'];
    $data_hora = $_POST['data_hora'];
    $observacao = $_POST['observacao'] ?? '';

    $id_instrutor = $_SESSION['usuario']['id'];
    $agendamento = new Agendamento();
    
    // Tenta criar o agendamento
    if ($agendamento->criar($id_instrutor, $id_aluno, $data_hora, $observacao)) {
        $_SESSION['sucesso'] = "Consulta agendada com sucesso!";
        header("Location: ../index.php?page=perfilInstrutor");
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao agendar consulta. Por favor, tente novamente.";
        header("Location: ../index.php?page=perfilInstrutor");
        exit();
    }
} else {
    $_SESSION['erro'] = "Por favor, preencha todos os campos obrigatórios.";
    header("Location: ../index.php?page=perfilInstrutor");
    exit();
}
