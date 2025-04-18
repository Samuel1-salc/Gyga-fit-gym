<?php
require_once __DIR__ . "../models/usuarioInstrutor.class.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_aluno = $_POST['id_aluno'] ?? '';
    $id_instrutor = $_POST['id_instrutor'] ?? '';

    $usuario_instrutor = new usuario_instrutor();

    if($usuario_instrutor->checkRelationshipUsers($id_aluno)){
        $_SESSION['error'] = "Esse aluno já tem personal!";
        header("Location: ../view/telaPrincipal.php");
        exit();
    }else{
        $usuario_instrutor->adicionarAluno_Instrutor($id_aluno);
        $_SESSION['success'] = "Aluno cadastrado  com sucesso!";
        exit();
    }
}