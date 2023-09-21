<?php
class Database
{
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
            $this->createTable($this->tableName, ['user_id INT(255)', 'username VARCHAR(20)', 'pass_hash VARCHAR(255)', 'access_level VARCHAR(255)', 'last_login DATETIME', 'geolocation TEXT'], 'user_id', [1], [0], [0, 1,]);
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
    //user Auth functions
    public function authenticateUser(string $username, string $password)
    {
        if ($this->userExists($username)) {
            $storedPassword = $this->getUsersPassword($username);
            if (password_verify($password, $storedPassword)) {
                $this->responseText['authenticated'] = "Authenticated";
                return true;
            } else {
                $this->responseText['authenticated'] = "Password not verified";
            }
        } else {
            $this->responseText['authenticated'] = "User does not exist!";
        }
        return false;
    }
    /**
     * Checks if a user with the provided username exists in the database.
     *
     * @param string $username The username to check.
     * @return bool Returns true if the user exists, false otherwise.
     */
    private function userExists(string $username): bool
    {
        $sql = "SELECT COUNT(*) AS 'count'
        FROM $this->tableName
        WHERE username = $username;";
        $SendDB = $this->query($sql);
        $row = $SendDB->fetch(PDO::FETCH_ASSOC);
        $exists = ($row['count'] === 1) ? true : false;
        return $exists;
    }

    /**
     * Retrieves the stored password for a given username.
     *
     * @param string $username The username to retrieve the password for.
     * @return string|null Returns the stored password if the user exists, null otherwise.
     */
    protected function getUsersPassword($username): string|bool
    {

        $sql = "SELECT password
            FROM  $this->tableName
            WHERE username = $username;";
        try {
            $SendDB = $this->query($sql);
        } catch (PDOException $error) {
            $this->responseText['getpassworduser'] = "error: " . $error->getMessage();
            return false;
        }
        if ($SendDB->rowCount() > 0) {
            $row = $SendDB->fetch(PDO::FETCH_ASSOC);
            $password = $row['pass_hash'];
            return $password;
        } else {
            $this->responseText['getpassworduser'] = "error: password not found";
            return false;
        }
    }

    /**
     * Updates a user's password in the admin authentication system.
     * @param string $username The username of the user.
     * @param string $password The current password of the user.
     * @param string $newPassword The new password for the user.
     * @return bool Returns true if the password was updated successfully, false otherwise.
     */
    public function updateUserPassword($username, $password, $newPassword): bool
    {
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';

        // User existence check
        if ($this->userExists($username)) {
            // Update password
            $storedPassword = $this->getUsersPassword($username);
            if (password_verify($password, $storedPassword)) {
                if (preg_match($checkPassword, $newPassword)) {
                    $options = array('cost' => 10);
                    $derivedPassword = password_hash($newPassword, PASSWORD_BCRYPT, $options);
                    $sql = "UPDATE $this->tableName SET pass_hash = $derivedPassword  WHERE username = $username;";
                    // Execute the update statement
                    $SendDB = $this->query($sql);
                    if ($SendDB->rowCount() > 0) {
                        $this->responseText['UpdatePassword'] = "Password was updated successfully";
                        $this->SetLastlogin($username);
                        return true;
                    } else {
                        $this->responseText['UpdatePassword'] = "No user found with the specified ID.";
                        return false;
                    }
                } else {
                    $this->responseText['UpdatePassword'] = "Password does not meet requirements";
                    return false;
                }
            } else {
                $this->responseText['UpdatePassword'] = "Old password does not match";
                return false;
            }
        } else {
            $this->responseText['UpdatePassword'] = "User does not exist";
            return false;
        }
    }
    // user minupulate
    private function GetUserAccess($username, $password, $IsOtherUser = null, $Return): string|bool
    {
        if (!$IsOtherUser) {
            $sql = "SELECT access_level 
        FROM $this->tableName
        WHERE username = $username;";
            $SendDB = $this->query($sql);
            if ($SendDB->rowCount() > 0) {
                $row = $SendDB->fetch(PDO::FETCH_ASSOC);
                $access_level = $row['access_level'];
            }
        }else{
            if($this->authenticateUser($username,$password)){
                
            }
        }
        if ($Return) {
            $sql = "SELECT ";
        } else {
            return $access_level;
        }
        return false;
    }
    private function SetLastlogin($username): bool
    {
        $currentDateTime = date('d-m-Y H:i:s');
        if ($this->userExists($username)) {
            $sql = "UPDATE $this->tableName SET last_login = $currentDateTime
        WHERE usename = $username;";
            $this->query($sql);
            return true;
        }
        return false;
    }
    /**
     * Creates a new user with the provided username and password.
     *
     * @param string $username The username for the new user.
     * @param string $password The password for the new user.
     * @return bool Returns true if the user is created successfully, false otherwise.
     */
    public function createUser($username, $password, $access = "end-user")
    {
        $checkUsername = '/^(?=.*[a-zA-Z])(?=.[0-9])[a-zA-Z0-9!@#$%^&*]+$/';
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';
        if (preg_match($checkUsername, $username)) {
            if (preg_match($checkPassword, $password)) {
                $options = array('cost' => 10);
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);

                $sql = "INSERT INTO $this->tableName  (username,pass_hash,access_to)
            VALUES ($username, $derivedPassword,$access);";
                //ON DUPLICATE KEY UPDATE username = $username;";
                try {
                    $sendDB = $this->query($sql);
                    $this->responseText['createUser'] = "User created successfully";
                    $this->SetLastlogin($username);
                    return true;
                } catch (PDOException $error) {
                    $this->responseText['createUser'] = "error: " . $error->getMessage();
                }
            } else {
                $this->responseText['createUser'] = "user password is not secure enough";
            }
        } else {
            $this->responseText['createUser'] = "username is must contain alphanumercial";
        }
        return false;
    }
    function removeUser($username): bool
    {
        $sql = "DELETE FROM $this->tableName WHERE user_id = $username;";
        try {
            $SendDB = $this->query($sql);
        } catch (PDOException $th) {
            echo $th;
        }
        if ($SendDB->rowCount() > 0) {
            $this->responseText['removeUser'] = "User has successfully been removed";
            return true;
        } else {
            $this->responseText['removeUser'] = "User has  been removed";
            return false;
        }
    }
    protected function SetUserAccess($username, $accessLevel)
    {
        if ($this->GetUserAccess($username)) {
            # code...
        }
        $sql = "UPDATE $this->tableName SET access_level = $accessLevel
        WHERE username = $username;";
        if (!$this->query($sql)) {
        }
    }
    //table functions
    /**
     * Creates a table with the provided name, columns, primary key, and unique keys.
     *
     * @param string $table_name The name of the table to create.
     * @param array $columns An array of column definitions.
     * @param string|null $primary_key The primary key column name.
     * @param array|null $unique_keys An array of unique key column names.
     * @param array|null $Autoincrement 
     * @param array|null $notnull 
     * @return bool Returns true if the table is created successfully, false otherwise.
     */

    public function createTable(string $tablesName, array $columns, $primary_key = null, array $unique_keys, array $AutoIncriment = null, array $Notnull = null)
    {
        if (!empty($notnull)) {
            foreach ($notnull as $notnullindex) {
                $columns[$notnullindex] .= " NOT_NULL";
            }
        }
        if (!empty($AutoIncriment)) {
            foreach ($AutoIncriment as $columnindex) {
                $columns[$columnindex] .= " AUTO_INCREMENT";
            }
        }
        $create_table_sql = "CREATE TABLE IF NOT EXISTS $this->Database \. $tablesName  (";
        foreach ($columns as $column) {
            $create_table_sql .= "$column, ";
        }
        // Set the primary key if provided
        if ($primary_key) {
            $create_table_sql .= "PRIMARY KEY ($primary_key), ";
        }
        // Set the unique keys if provided
        $keysToadd = "";
        if ($unique_keys) {
            foreach ($unique_keys as $key) {

                $keysToadd .= $columns[$key] . ",";
            }
            $create_table_sql .= "UNIQUE ($keysToadd)";
        }
        $create_table_sql .= ") ENGINE = InnoDB;";
        // Remove any trailing comma and space
        $create_table_sql = rtrim($create_table_sql, ", ") . ")";
        // Execute the SQL statement to create the table
        try {
            $this->query($create_table_sql);
            $this->responseText['TableCreate'] = "Table created successfully";
            return true;
        } catch (PDOException $error) {
            $this->responseText['TableCreate'] = "Error creating table: " . $error->getMessage();
        }
        return false;
    }

    public function dropTable($tableName)
    {
        $sql = "DROP TABLE IF EXISTS $tableName";
        return $this->query($sql);
    }



    // Add more methods for other table operations like insert, update, delete, select, etc.
}
