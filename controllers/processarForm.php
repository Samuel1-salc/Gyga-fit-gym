<?php
require_once __DIR__ . '/../models/Form.class.php';
require_once __DIR__ . '/../models/Users.class.php';
require_once __DIR__ . '/../models/usuarioInstrutor.class.php';
session_start();
$countForm = 0;
$relacionamentoUsers = new aluno_instrutor();
$usuarios = new Users();
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $altura = trim($_POST['altura']);
    $peso = trim($_POST['peso']);
    $sexo = trim($_POST['sexo']);
    $id_user = $_SESSION['usuario']['id'];

    if(empty($altura) || empty($peso) || empty($sexo)){
        $_SESSION['error'] = "Preencha todos os campos!";
        header("Location: ../view/formulario.php");
    }

    $formulario = new Form();
    if(!($relacionamentoUsers->checkRelationshipUsers($id_user))){
        echo "voce ainda não tem personal!";
    }else{
        $formulario->cadastrarForm($altura,$peso,$sexo,$id_user);
        $_SESSION['aluno'] = $formulario->getFormById($_SESSION['usuario']['id']);
    }
    header("Location: ../view/telaPrincipal.php");
    exit();
}