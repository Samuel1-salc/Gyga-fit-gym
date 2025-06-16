<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Agendamento.class.php';

// Verifica se os campos obrigatórios foram preenchidos
if (!empty($_POST['aluno']) && !empty($_POST['data_hora']) && !empty($_POST['id_instrutor'])) {
    $id_aluno = $_POST['aluno'];
    $id_instrutor = $_POST['id_instrutor'];
    $data_hora = $_POST['data_hora'];
    $observacao = $_POST['observacao'] ?? '';

    // Converter data_hora do formato HTML para o formato MySQL DATETIME
    if (strpos($data_hora, 'T') !== false) {
        $data_hora = str_replace('T', ' ', $data_hora);
    }
    // Adicionar segundos se não houver
    if (strlen($data_hora) == 16) {
        $data_hora .= ':00';
    }

    $agendamento = new Agendamento();
    
    // Tenta criar o agendamento
    if ($agendamento->criar($id_aluno, $id_instrutor, $data_hora, $observacao)) {
        $_SESSION['sucesso'] = "Consulta agendada com sucesso!";
        header("Location: ../view/perfilInstrutor.php");
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao agendar consulta. Por favor, tente novamente.";
        header("Location: ../view/perfilInstrutor.php");
        exit();
    }
} else {
    $_SESSION['erro'] = "Por favor, preencha todos os campos obrigatórios.";
    header("Location: ../view/perfilInstrutor.php");
    exit();
}
?>
