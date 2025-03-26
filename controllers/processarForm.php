<?php
require_once __DIR__ . '/../models/Form.class.php';
session_start();

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
    $formulario->getForm($altura,$peso,$sexo,$id_user);
    header("Location: ../view/telaPrincipal.php");
    exit();
}