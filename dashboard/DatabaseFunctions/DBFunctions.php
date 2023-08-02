<?php
class DBaccess
{
    protected $Hostname = 'localhost';
    public $Ausername = 'UAGAdmin';
    protected $Apassword = 'sFhgbUnua7IaNG7u';
    private $Database = 'employee_rosters';
    public $tableName = 'useradmins';
    public $DBConnect;
    public $username;

    public function __construct(string $username, string $passuser, bool $adduser = false)
    {
        $this->username = $username;
        $this->initialize($username, $passuser, $adduser);
    }
    protected function initialize(string $username, string $password, bool $adduser = false)
    {
        try {
            $this->DBConnect = new PDO("mysql:host=$this->Hostname;dbname=$this->Database;charset=utf8", $this->Ausername, $this->Apassword, array(PDO::ATTR_PERSISTENT => true));
            // set the PDO error mode to exception
            $this->DBConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<p>Connected successfully</p>";
        } catch (PDOException $eror) {
            echo "Connection failed: " . $eror->getMessage();
        }
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->Database . "." . $this->tableName . "
        (id INT NOT NULL AUTO_INCREMENT, 
            username TEXT NOT NULL,
          password TEXT NOT NULL,
          PRIMARY KEY (ID),
          UNIQUE(username)
        ) ENGINE=InnoDB;";
        $SendDB = $this->DBConnect->prepare($sql);
        try {
            $SendDB->execute();
        } catch (PDOException $eror) {
            print_r("$eror");
        }


        if ($adduser === true) {
            $this->createUser($username, $password);
        } else if ($this->authenticateUser($this->username, $password)) {
            return $this->DBConnect;
        } else {
            return false;
        }
    }
    public function authenticateUser(string $username, string $password)
    {
        if ($this->userExists($username)) {
            $storedPassword = $this->getUsersPassword($username);
            if (password_verify($password, $storedPassword)) {
                $authenticated = true;
            } else {
                $authenticated = false;
            }
        } else {
            $authenticated = false;
        }
        return $authenticated;
    }

    public function userExists(string $username)
    {
        $sql = "SELECT COUNT(*) AS 'count'
        FROM " . $this->tableName . "
        WHERE 'username' = '" . $username . "';";
        $SendDB = $this->DBConnect->prepare($sql);
        try {
            $SendDB->execute();
        } catch (PDOException $eror) {
            echo $eror;
        }
        $row = $SendDB->fetchArray(PDO::FETCH_ASSOC);
        $exists = ($row['count'] === 1) ? true : false;

        return $exists;
    }
    protected function getUsersPassword($username)
    {
        $sql = "SELECT password
            FROM :tablename
            WHERE 'username' = ':username';";
        $sendDBQ = $this->DBConnect->quote($sql);
        $SendDB = $this->DBConnect->prepare($sendDBQ);
        $SendDB->bindValue(':tablename', $this->tableName);
        $SendDB->bindValue(':username', $username);
        try {
            $SendDB->execute();
        } catch (PDOException $eror) {
            echo $eror;
        }
        $row = $SendDB->fetchArray(PDO::FETCH_ASSOC);
        $password = $row['password'];

        return $password;
    }

    public function createUser($username, $password)
    {
        $options = array('cost' => 10);
        $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
        $sql = "INSERT INTO " . $this->tableName . " (username,password)
            VALUES ('" . $username . "','" . $derivedPassword . "')
            ON DUPLICATE KEY UPDATE username='" . $username . "';";
        $sendDB = $this->DBConnect->prepare($sql);
        try {
            $sendDB->execute();
            return true;
        } catch (PDOException $eror) {
            echo $eror;
            return false;
        }
    }

    function create_table(string $table_name, $columns, $primary_key = null, $unique_keys = null)
    {

        $create_table_sql = "CREATE TABLE $table_name (";
        foreach ($columns as $column) {
            $create_table_sql .= "$column, ";
        }
        // Set the primary key if provided
        if ($primary_key) {
            $create_table_sql .= "PRIMARY KEY ($primary_key), ";
        }
        // Set the unique keys if provided
        if ($unique_keys) {
            foreach ($unique_keys as $key) {
                $create_table_sql .= "UNIQUE ($key), ";
            }
        }
        // Remove any trailing comma and space
        $create_table_sql = rtrim($create_table_sql, ", ") . ")";
        // Execute the SQL statement to create the table
        $sql = $this->DBConnect->prepare($create_table_sql);
        try {
            $sql->execute;
            echo "Table created successfully";
            return true;
        } catch (PDOException $eror) {
            echo "Error creating table: " . $eror;
            return false;
        }
    }

    public function closeConnection()
    {
        if ($this->DBConnect->close()) {
            return true;
        } else {
            return false;
        }
    }

    public function printDB()
    {
        print_r($this->DBConnect);
    }
}
