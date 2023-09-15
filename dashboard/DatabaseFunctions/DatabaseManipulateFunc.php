<?php
class Database {
    protected static $isCalled = false;
    private $Hostname = 'localhost';
    private $Apassword = 'N0@dminP@ss';
    private $Database = 'MyFirstDB';
    private $Ausername = 'Admin';
    public $tableName = 'userAdmins';
    private $DBConnection;
    public $responseText;

    public function __construct(string $userNameTable = null, $options = ['Ausername' => null, 'Apassuser' => null, 'Hostname' => null, '$Database' => null]) {
         /**
         * if class is start all values must be set before hand 
         * otherwise it will use default values and function needs to _destruct 
         * before values can be modified.
         * @param __destruct is call at end of the php script or 
         * if you use call_destruct() methode
         */
        if (!self::$isCalled) {
            $this->Ausername = ($options['Ausername'] == null) ? $this->Ausername : $options['Ausername'];
            $this->Hostname = ($options['Hostname'] == null) ? $this->Ausername : $options['Ausername'];
            $this->Apassword = ($options['Apassuser'] == null) ? $this->Ausername : $options['Ausername'];
            $this->Database = ($options['Database'] == null) ? $this->Ausername : $options['Ausername'];
            $this->tableName = ($userNameTable == null) ? $this->tableName : $userNameTable;
            $this->initialize();
            self::$isCalled = true;
            $this->responseText['initialize'] = true;
            return true;
        }
        $this->responseText['initialize'] = false;
        return false;
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