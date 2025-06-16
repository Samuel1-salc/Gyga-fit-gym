<?php
// Script para renomear arquivos de instrutor sem extensão para .jpg
$uploadsDir = __DIR__ . '/uploads/';
$arquivos = scandir($uploadsDir);
foreach ($arquivos as $arquivo) {
    if (preg_match('/^instrutor_\d+$/', $arquivo)) {
        $novoNome = $arquivo . '.jpg';
        if (!file_exists($uploadsDir . $novoNome)) {
            rename($uploadsDir . $arquivo, $uploadsDir . $novoNome);
            echo "Arquivo $arquivo renomeado para $novoNome\n";
        }
    }
}
echo "Renomeação concluída.";
