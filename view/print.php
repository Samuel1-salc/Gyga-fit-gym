<?php
require_once __DIR__ . '/../models/Usuarios.class.php';
$user = new Users();
$tabela = $user->printDadosUser(1);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYGA FIT - Impressão de Treino</title>
    <link rel="stylesheet" href="../view/style/print.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>
    <body>
        <div>
           <h6><?= htmlspecialchars($tabela['username']) ?> </h6>
        </div>
    </body>