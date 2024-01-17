<?php
class Database
{
    protected static $isCalled = false;
    private $Hostname = 'localhost';
    private $Apassword = 'N0@dminP@ss';
    private $Database = 'MyFirstDB';
    private $Ausername = 'Admin';
    public $tableName = 'Users';
    private $DBconnection;
    public $responseText;
    //Contruct functions

    /**
     * initialises the variables saved in the ./DBv.json needed for the database connection
     * custom values should be added to connect to your custom domain and database.
     * the variables are only initialized once the object is called and you need to 
     * use the call_destruct method to close object and re-initialize the variables.
     * Let the object initialized its own user table, but you can still add a custom name at $userNameTable 
     * (find more info of this at CreatUserTable method)
     * @param string|null $userNameTable is the custom table name used for user table creation at initialization
     * @param array $options -> 'Ausername' = username for database , 'Apassuser' = Password for DB , 
     * 'Hostname' = hostname or ip of DB, 'Database' = Name of the database to connect to.
     */
    function __construct(string $userNameTable = null, $options = ['Ausername' => null, 'Apassuser' => null, 'Hostname' => null, 'Database' => null])
    {
        if (!self::$isCalled) {
            $this->Ausername = ($options['Ausername'] == null) ? $this->Ausername : $options['Ausername'];//database User Name
            $this->Hostname = ($options['Hostname'] == null) ? $this->Ausername : $options['Hostname'];//Hostname of database
            $this->Apassword = ($options['Apassuser'] == null) ? $this->Ausername : $options['Apassuser'];//Password for database
            $this->Database = ($options['Database'] == null) ? $this->Ausername :$options['Database'];//Database name
            $this->tableName = ($userNameTable == null) ? $this->tableName : $userNameTable;//custom name for user table
            $this->CreateUsersTable();
            self::$isCalled = true;
            $this->responseText['initialized'] = "Initialized done";
        } else {
            $this->responseText['initialized'] = "Already initialized";
        }
    }
    /**
     * destructs the object
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

    /**
     * This connects to DB using initiliased values.
     * @return string|false
     */
    public function connect() :string|false
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
            return $this->DBconnection;
        } catch (PDOException $error) {
            $this->responseText['connection'] = "Connection failed: " . $error->getMessage();
            return false;
        }
    }
    /**
     * Disconnects the connection but doesn't close the object
     * @return null
     */
    public function disconnect()
    {
        $this->DBconnection = null;
        return $this->DBconnection;
    }



/**
 * query and return SQL otherwise the error
 *
 * @param String $sql
 * @return String
 */
    public function query($sql)
    {
        try {
            $sendDb = $this->DBconnection->query($sql);
            $this->responseText['QueryError'] = "No errors";
        } catch (PDOException $error) {
            $this->responseText['QueryError'] = "Error: {$error->getMessage()}";
            return false;
        }
        return $sendDb;
    }
    
    
    /**
     * create user Data table
     * creates the users table with the following variables
     * -> '*' are unique
     * -> '+' primary key
     * -> '!' notnull
     * -> '^' Auto increment
     * thr variables:
     * 'user_id INT' + = users ID.
     * 'username TEXT' *! = username.
     * 'full_name TEXT' ! = full name of user.
     * 'email TEXT' ! = email of user.
     * 'pass_hash TEXT ! = The password saved as hash file.
     * 'access_tables JSON' ! = Acces of Table Names.
     * 'last_login DATETIME' ! = for record keeping of time login.
     * 'geolocation TEXT' = 'optional' used for extra security.
     * 'extra_data JSON' = 'optional' a json file to save data.
     * @return void
     */
    function CreateUsersTable():void
    {
        $this->createTable(
            $this->tableName,
            [
            'user_id INT(255)', 
            'username TEXT', 
            'full_name TEXT',
            'email_address TEXT', 
            'pass_hash TEXT', 
            'acces_tables JSON', 
            'last_login DATETIME', 
            'geolocation TEXT',
            'extra_data JSON'], 
            'user_id',
            [1], 
            [0], 
            [0,1,2,3,4,5,6]);
    }

    /**
     * Creates a new user with the provided username and password.
     * The access level is optional, its a json file with all the table names the user can access.
     * When a user is created a corrosponding table with a '_user_perm' extension
     * for permissions to the table is created or user is added.
     * The permissions is read,write or admin.
     * admin can alter table.
     * dafault value is "Dev-Admin" for access level to all.
     * @param STRING $username The username for the new user.
     * @param STRING $password The password for the new user.
     * @param JSON $access The JSON file with the table names. Default is 'Dev-Admin'. 
     * @return BOOL Returns true if the user is created successfully, false otherwise.
    */
    public function createUser($username,$full_name,$email_address, $password, $access = "")
    {
        $checkUsername = '/^(?=.*[a-zA-Z])(?=.[0-9])[a-zA-Z0-9!@#$%^&*]+$/';
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';
        if (preg_match($checkUsername, $username)) {//Check if username contains alphabunericals.
            if (preg_match($checkPassword, $password)) { //ckeck if password contains alphanumericals and a symbols.
                $options = array('cost' => 10);
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $sql = "INSERT INTO $this->tableName  (username,pass_hash,access_tables)
                VALUES ($username, $derivedPassword,$access)
                ON DUPLICATE KEY UPDATE ( username = $username, access_table = $access;";
                try {
                    $sendDB = $this->query($sql);
                    $this->responseText['createUser'] = "User created successfully";
                    $this->updateUserMetadata($username);
                    
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

        $SendDB = $this->query($sql);

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
    /**
     * GetUserAcces()
     * Access_level_table is a separate Table wchich has a
     * access_id   which is a foreign key for User Tables 
     * access_name which is a readable name of level
     * access_levels which has a json file which is contains the levels of access
     * This method get user access_level_id or access_level_name and returns or
     * it can return the json of level in categories
     * @param string $username to find the user access_id
     * @param int $Return has 2 options 0 = return access_name, 1 = returns access_contents default is id if none is given
     * @param string $accessLevelTable is only used if you have used a custom name for your access_table
     * @param string $userTable is used only if you have used a custom table name in creation of this class
     * @return mixed returns string if successfull otherwise returns a false
     * 
     *
     */
    function GetUserAccess(string $username, int $Return = null, $accessLevelTable = "access_level_table", $userTable = "user_table")
    {
        $accessType = null;
        //check if want access id
        $sql = "SELECT access_id
            FROM $userTable
            WHERE username = $username;";
        $sendDB = $this->query($sql);
        if ($sendDB->rowCount > 0) {
            $row = $sendDB->fetch(PDO::FETCH_ASSOC);
            $access_id = $row['access_id'];
        }
        //find access types or name 
        switch ($Return) {
            case 0:
                $accessType = 'access_name';
                $sql = "SELECT  $accessType;
                FROM $accessLevelTable
                WHERE  access_id = $access_id;";
                break;
            case 1:
                $accessType = 'access_levels';
                $sql = "SELECT $accessType
                FROM $accessLevelTable
                WHErE  access_id = $access_id;";
                break;
        }

        $levelTableName = $this->query($sql);

        if ($levelTableName->rowCount() > 0) {
            $row2 = $levelTableName->fetch(PDO::FETCH_ASSOC);
            if (is_array(json_decode($row2[$accessType],1))) {
                return json_decode($row2[$accessType], 1);
            } else {
                return $row2[$accessType];
            }
        }
        return false;
    }
    protected function SetUserAccess($username,$otherUser, $accessLevel, $userTable = "user_table")
    {
        $userA_AccessTo = $this->GetUserAccess($username,1);
        foreach ($userA_AccessTo as $key) {
            if($key === $accessLevel){
                $otherUserAccess =  $this->GetUserAccess($otherUser,1);
                $otherUserAccess[] = "";
            }
        }


    //     $AccessTypes2 = $this->GetUserAccess($username, 1);
    //     foreach ($AccessTypes2 as $key=>$value) {
    //         $sql = "UPDATE $accessLevelTable SET access_level = $accessLevel
    //     WHERE username = $username;";
    //         !$this->query($sql);
    //     }
     }

    private function updateUserMetadata($username): bool
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
