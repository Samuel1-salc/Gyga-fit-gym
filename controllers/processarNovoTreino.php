<?php
require_once __DIR__ . '/../models/Treino.class.php';
require_once __DIR__ . '/../models/Usuarios.class.php';
$novoTreino = new Treino();
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    
        
        if(isset($_POST['submit_PaginaDeTreino'])){
            $id_instrutor = $_SESSION['usuario']['id'] ?? null;
            $id_aluno = $_POST['id_alunoNovoTreino'] ?? null;
            $id_treino_criado = $_POST['quantTreinos'] ?? null;
            $grupo_treino = $_POST['grupo'] ?? null;
            $exercicio = $_POST['numero'] ?? null;
            $nome_exercicio = $_POST['exercicio'] ?? null;
            $series = $_POST['series'] ?? null;
            $repeticoes = $_POST['reps'] ?? null;
            $observacoes = $_POST['hiddenObs'] ?? null;
            $data = new DateTime();
            $data_criacao = $data -> format('Y-m-d H:i:s');

            $novoTreino -> cadastrarTreino($id_instrutor,$id_aluno,$id_treino_criado, $grupo_treino, $exercicio, $nome_exercicio, $series, $repeticoes,$observacoes, $data_criacao);
        
                
        }
        elseif(isset($_POST['submit_NovoTreino'])){
            $id_aluno = $_POST['id_alunoNovoTreino'] ?? '';
            header("Location: ../view/paginaDeTreino.php?id_aluno=$id_aluno");
            exit();
        }
    
}
