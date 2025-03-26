<?php
require_once __DIR__ . "/models/User.class.php";
$usuario = new User();


if($usuario->cadastrar("email@rmail", "samuelson", "12345678941", "123465")){
    echo "cadastrado com sucesso";
}else{
      echo "errado";
   }
?>