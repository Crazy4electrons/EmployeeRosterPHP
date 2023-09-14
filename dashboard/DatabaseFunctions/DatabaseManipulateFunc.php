<?php
class Database {
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function disconnect() {
        $this->connection = null;
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function createTable($tableName, $columns) {
        $sql = "CREATE TABLE IF NOT EXISTS $tableName ($columns)";
        return $this->query($sql);
    }

    public function dropTable($tableName) {
        $sql = "DROP TABLE IF EXISTS $tableName";
        return $this->query($sql);
    }

    // Add more methods for other table operations like insert, update, delete, select, etc.
}

?>