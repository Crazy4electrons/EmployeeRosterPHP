<?php
/**
 * db_connection.php
 * Manages the database connection.
 */

/**
 * Establishes a database connection using PDO.
 * 
 * @param string $host       Database host.
 * @param string $dbname     Database name.
 * @param string $username   Database username.
 * @param string $password   Database password.
 * @return PDO              The PDO object representing the database connection.
 */
// function connectToDatabase($host, $dbname, $username, $password) {
//     try {
//         $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         return $pdo;
//     } catch (PDOException $e) {
//         die("Connection failed: " . $e->getMessage());
//     }
// }
  
//

Class 
    protected static $isCalled = false;
    private $Hostname = 'localhost';
    private $Apassword = 'N0@dminP@ss';
    private $Database = 'MyFirstDB';
    private $Ausername = 'Admin';
    public $tableName = 'userAdmins';
    private $DBconnection;
    public $responseText;
    //Contruct functions
    function __construct(string $userNameTable = null, $options = ['Ausername' => null, 'Apassuser' => null, 'Hostname' => null, '$Database' => null])
    {
        /**
         *  Variables must be set at object call 
         * if not set default values will be used
         * then @param function call_destruct() must be called to close
         * object and then reinitialized the variables to custom values.
         * @param string tableName must be set to access users table
         * used by the object for authentication
         * and for the first initialization please let the object create
         * the user database  but you can alway specify you custom name.
         */
        if (!self::$isCalled) {
            $this->Ausername = ($options['Ausername'] == null) ? $this->Ausername : $options['Ausername'];
            $this->Hostname = ($options['Hostname'] == null) ? $this->Ausername : $options['Ausername'];
            $this->Apassword = ($options['Apassuser'] == null) ? $this->Ausername : $options['Ausername'];
            $this->Database = ($options['Database'] == null) ? $this->Ausername : $options['Ausername'];
            $this->tableName = ($userNameTable == null) ? $this->tableName : $userNameTable;
            $this->CreateUsersTable();
            self::$isCalled = true;
            $this->responseText['initialize'] = true;
        } else {
            $this->responseText['initialize'] = false;
        }
    }
    /**
     * Call function destruct to change access variables in one script
     *
     * @return void
     */
    function call_destruct()
    {
        $this->__destruct();
    }
    private  function __destruct()
    {
        if (self::$isCalled) {
            if ($this->DBconnection != null) {
                $this->disconnect();
            }
            unset($this->responseText);
            $this->responseText['initialize'] = 'empty';
            self::$isCalled = false;
        }
    }
    //connection functions
    public function connect()
    {
        try {
            $this->DBconnection = new PDO(
                "mysql:host=$this->Hostname;
                    dbname=$this->Database;
                    charset=utf8",
                $this->Ausername,
                $this->Apassword,
                array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => 'ERRMODE_EXCEPTION')
            );
            $this->responseText['connection'] = "Connected";
            return true;
        } catch (PDOException $error) {
            $this->responseText['connection'] = "Connection failed: " . $error->getMessage();
            return true;
        }
    }

    public function disconnect()
    {
        $this->DBconnection = null;
    }

    public function query($sql)
    {
        try {
            $sendDb = $this->DBconnection->query($sql);
        } catch (PDOException $error) {
            $this->responseText['QueryError'] = "Error: {$error->getMessage()}";
            return false;
        }
        return $sendDb;
    }
?>
