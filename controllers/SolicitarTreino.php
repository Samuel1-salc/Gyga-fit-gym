<?php
session_start();
    require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
    $SolicitarTreino = new SolicitacaoTreino();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $experiencia = $_POST['experiencia'] ?? '';
    $objetivo = $_POST['objetivo'] ?? '';
    $treinos = $_POST['treinos'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $peso = $_POST['peso'] ?? '';
    $altura = $_POST['altura'] ?? '';
    $id_user = 2;
    $SolicitarTreino->SolicitarTreino($id_user, $experiencia, $objetivo, $treinos, $sexo, $peso, $altura);  
}
?>
