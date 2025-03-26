<?php
require_once __DIR__ . "/models/User.class.php";
$usuario = new User();
if ($usuario->cadastrar("samuellclash@gmail.com","samuelPingas", "05274963161","12345", "12345")) {
   echo "cadastrado com sucesso";
} else {
   echo "errado";
}
?>