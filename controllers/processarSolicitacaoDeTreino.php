<?php
session_start();
    require_once __DIR__ . '/../models/SolicitacaoTreino.class.php';
    require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
    $SolicitarTreino = new SolicitacaoTreino();
    $checkRelacao = new aluno_instrutor();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $experiencia = $_POST['experiencia'] ?? '';
    $objetivo = $_POST['objetivo'] ?? '';
    $treinos = $_POST['treinos'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $peso = $_POST['peso'] ?? '';
    $altura = $_POST['altura'] ?? '';
    $id_aluno = $_SESSION['usuario']['id'] ?? '';

    if($checkRelacao->checkRelationshipUsers($id_aluno)){
        $SolicitarTreino->SolicitarTreino($id_aluno, $experiencia, $objetivo, $treinos, $sexo, $peso, $altura); 
        $processo = 'em andamento';
        $checkRelacao->adcStatus($processo,$id_aluno); 
    }else{
        $_SESSION['error'] = "voce ainda não tem instrutor!";
    }
    
}
?>