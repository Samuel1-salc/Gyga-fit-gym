<?php

class Database {
    private $driver;
    private $host;
    private $dbname;
    private $username;
    private $pass;
    private $port;

    private $con;

    function __construct() {
        $this->driver = "mysql";
        $this->host = "maglev.proxy.rlwy.net";
        $this->port = "42686";
        $this->dbname = "railway";
        $this->username = "root";
        $this->pass = "iNnLkZTxghKQoOdRxvcjfHVKJRatKiUM";
    }

    function getConexao(){
        try {
            $this->con = new PDO(
                "{$this->driver}:host={$this->host};port={$this->port};dbname={$this->dbname}",
                $this->username,
                $this->pass
            );

            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->con;

        } catch(PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
