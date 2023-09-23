
<?php

class DatabaseConnector
{
    private $dbConnection = null;

    private $host;
    private $db;
    private $user;
    private $pass;
    private $port;

    public function __construct()
    {
       $this->host = Dbconfig::HOST;
       $this->db   = DbConfig::DB_NAME;
       $this->user = DbConfig::DB_USERNAME;
       $this->pass = DbConfig::DB_PASSWORD;
       $this->port = DbConfig::DB_PORT;

       try {
        $this->dbConnection = new PDO('pgsql:host=' . $this->host . ';dbname=' . $this->db. ';port=' .$this->port, $this->user, $this->pass);
        $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}

?>