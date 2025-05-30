<?php

class Database
{
    private $driver;
    private $host;
    private $dbname;
    private $username;
    private $pass;
    private $port;
    private static $instance = null;
    private $con;

    function __construct($host, $port, $username, $password, $dbname)
    {
        try {
            $this->con = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        } catch (PDOException $e) {
            die("Falha ao conectar com o banco: " . $e->getMessage());
        }
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/db-config.php';
            self::$instance = new self(
                $config['database']['host'],
                $config['database']['port'],
                $config['database']['user'],
                $config['database']['password'],
                $config['database']['dbname']
            );
        }
        return self::$instance;
    }


    function getConexao()
    {
        return $this->con;
    }
}
