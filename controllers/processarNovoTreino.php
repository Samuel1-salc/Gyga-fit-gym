<?php
require_once __DIR__ . '/../models/NovoTreino.class.php';
$novoTreino = new NovoTreino();
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $username = $_POST['aluno'] ?? '';
    $dia_da_semana = $_POST['diaSemana'] ?? '';
    $exercicio = $_POST['exercicio'] ?? '';
    $series = $_POST['series'] ?? '';
    $repeticoes = $_POST['repeticoes'] ?? '';
    $observacoes = $_POST['observacoes'] ?? '';
    $data = new DateTime();
    $dataNovoTreino = $data -> format('Y-m-d H:i:s');

    $novoTreino -> enviarTreino($username, $dia_da_semana, $exercicio, $series, $repeticoes, $observacoes, $dataNovoTreino);
}
