<?php

namespace Config;

class Environment
{
    /**
     * Carrega as variáveis de ambiente do arquivo .env
     *@param string $fir Caminho do diretório onde o arquivo .env está localizado
     */

    function loadEnv($path)
    {
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            $value = trim($value, "'\"");

            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
