<?php
require_once __DIR__ . '/../models/NovoTreino.class.php';
require_once __DIR__ . '/../models/Usuarios.class.php';
$novoTreino = new NovoTreino();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST['submit_PaginaDeTreino'])){
    $username = $_POST['aluno'] ?? '';
    $dia_da_semana = $_POST['diaSemana'] ?? '';
    $exercicio = $_POST['exercicio'] ?? '';
    $series = $_POST['series'] ?? '';
    $repeticoes = $_POST['repeticoes'] ?? '';
    $observacoes = $_POST['observacoes'] ?? '';
    $data = new DateTime();
    $dataNovoTreino = $data -> format('Y-m-d H:i:s');
    $id_aluno = $_POST['id_alunoNovoTreinoSalvar'] ?? '';
    
    $username = new Users();
    $nome_aluno = $username -> getAlunoById($id_aluno);
    $name_aluno = $nome_aluno['username'] ?? '';

    $novoTreino -> enviarTreino($id_aluno,$name_aluno, $dia_da_semana, $exercicio, $series, $repeticoes, $observacoes, $dataNovoTreino);
    }elseif(isset($_POST['submit_NovoTreino'])){
        $id_aluno = $_POST['id_alunoNovoTreino'] ?? '';
        header("Location: ../view/paginaDeTreino.php?id_aluno=$id_aluno");
        exit();
    }
    
}
