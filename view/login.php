<?php
session_start();
$_SESSION['usuario'] = [
    'id' => 1,
    'username' => 'Instrutor João',
    'perfil' => 'instrutor'
];
header("Location: perfilInstrutor.php");
exit;