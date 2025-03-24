<?php

class Database {
    private $driver;
    private $host;
    private $dbname;
    private $username;

    private $con;//cinnection

    function __construct() {
        $this->driver = "mysql";
        $this->username = "localhost";
        $this->dbname = "teste";
        $this->username = "root";
    }

    function getConexao(){
        try{
            $this->con = new PDO(
                "{$this->driver}:host={$this->host};dbname={$this->dbname}",
                $this->username,
                
            );
            echo "conexao bem sussedida\n";

            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->con;

        }catch(Exeption $e){
            echo "Erro: ".$e->getMessage();
        }
    }
}