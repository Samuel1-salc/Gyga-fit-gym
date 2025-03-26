<?php
require_once __DIR__ . "/models/Form.class.php";
$usuario = new Form();
if ($usuario->getForm("180", "80", "M", "1")) {
   echo "cadastrado com sucesso";
} else {
   echo "errado";
}
?>