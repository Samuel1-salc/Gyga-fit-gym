<?php 
    require_once ("./config/database.class.php");
    $con = new Database ();
    $link = $con->getConexao ();
    $stmt = $link->prepare("INSERT INTO usuarios(email, username, senha, CPF) VALUES ('teste@gmail.com', 'Teste1', '12345678', '01234567891') ");
    $stmt->execute();    
    