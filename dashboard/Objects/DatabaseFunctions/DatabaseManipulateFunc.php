<?php
class Database
{
    protected static $isInitialised = false;
    private $defaultValues = [
        'Hostname' => 'localhost', //Hostname
        'Apassword' => '@dminP@ss', //Apassword
        'Database' => 'MyFirstDB', //Database
        'Ausername' => 'Admin', //Ausername
    ];
    public $AdminTable = 'Users_Dev_admin';
    private $DBconnection;
    public $responseText;
    //Contruct functions

    /**
     * initialises the variables saved in the ./DBv.json needed for the database connection
     * custom values should be added to connect to your custom domain and database.
     * the variables are only initialized once the object is called and you need to
     * use the call_destruct method to close object and re-initialize the variables.
     * On first start of application let object initialized an admin user table for full access and setup of application.
     * This is also how you should setup your user tables(see more @method mixed createuser())
     * @param string|null $userNameTable -> is the custom table name used for user table creation at initialization
     * @param array $options -> 'Ausername' -> username for database , 'Apassuser' -> Password for DB ,
     * 'Hostname' -> hostname or ip of DB, 'Database' -> Name of the database to connect to.
     */
    protected function __construct($Ausername = null, $Apassword = null, $Hostname = null, $Database = null)
    {
        if (!self::$isInitialised) {
            $this->initialiseDataBaseVariables([$Hostname, $Database, $Ausername, $Apassword]);
            $this->connect();
            self::$isInitialised = true;
            $this->responseText['initialized'] = 'Initialized done';
        } else {
            $this->responseText['initialized'] = 'Already initialized';
        }
    }

    protected function initialiseDataBaseVariables($DatabaseVariables = ['Hostname' => null, 'Database' => null, 'user' => null, 'Password' => null])
    {
        $jsonFilePath = './DBv.json'; // Specify the path to your JSON file
        if (file_exists($jsonFilePath)) {
            // Check if the file exists
            $jsonData = file_get_contents($jsonFilePath);
            $dataArray = json_decode($jsonData, true);

            if ($dataArray === null) {
                // if file has no data, delete it.
                unlink($jsonFilePath);
            }
        }
        $dataArray = array_map(function ($value) use ($DatabaseVariables) {
            // Create new data array and write to file
            return $$DatabaseVariables[$value] ?? $this->{$value};
        }, array_keys($this->defaultValues));

        $jsonData = json_encode($dataArray, JSON_PRETTY_PRINT); //Encode data to json format

        if (file_put_contents($jsonFilePath, $jsonData) === false) {
            //Save file and check if successfull
            die('Error creating new file.');
        }
        $this->defaultValues = $dataArray;
        $this->responseText['DB_Access'] = 'New file created successfully with default data.';
    }

    /**
     * destructs the object
     * @return void
     */
    function call_destruct()
    {
        $this->__destruct();
    }
    private function __destruct()
    {
        if (self::$isInitialised) {
            if ($this->DBconnection != null) {
                $this->disconnect();
            }
            unset($this->responseText);
            $this->responseText = null;
            self::$isInitialised = false;
        }
    }

    //connection functions

    /**
     * This connects to DB using initiliased values.
     * it binds the connection to a public variable called $DBConnection
     * which is accessible by calling $DBConnection
     * @return string|false
     */
    function connect(): string|false
    {
        try {
            $this->DBconnection = new PDO(
                "mysql:host=$this->defaultValues['hostname'];
                        dbname=$this->defaultValues['Database';
                        charset=utf8",
                $this->defaultValues['Ausername'],
                $this->defaultValues['Apassword'],
                [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => 'ERRMODE_EXCEPTION'],
            );
            $this->responseText['connection'] = 'Connected';
            return true;
        } catch (PDOException $error) {
            $this->responseText['connection'] = 'Connection failed: ' . $error->getMessage();
            return false;
        }
    }
    /**
     * Disconnects the connection but doesn't close the object
     * @return true on success ,otherwise @return false
     */
    function disconnect(): bool
    {
        unset($this->DBconnection);
        if (!isset($this->DBconnection)) {
            return true;
        }
        return false;
    }
    /**
     * send query and return SQL otherwise the error
     * @param String $sql
     * @return String
     */
    function Sendquery($sql)
    {
        try {
            $sendDb = $this->DBconnection->query($sql);
            $this->responseText['QueryError'] = 'No errors';
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
     * 'user_id INT' +^ = users ID.
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
    function CreateUsersTable(): void
    {
        $this->createTable($this->AdminTable, ['user_id INT(255)', 'username TEXT', 'first_name TEXT', 'last_name TEXT', 'email TEXT', 'pass_hash TEXT', 'access JSON', 'last_login DATETIME', 'geolocation TEXT', 'extra_data JSON'], 'user_id', [1], [0], [0, 1, 2, 3, 4, 5, 6]);
    }
    //user Auth functions

    /**
     * Creates a new user with the provided username and password.
     * The access level is optional, its a json file with all the table names the user can access.
     * When a user is created and assigned a table a corrosponding table with a '_user_perm' extension is created.
     * this is used to retrieve modify and create permisssions.
     * The permissions is read,write or admin.
     * admin can alter table, The rest can only read or modify data.
     * dafault value is "End-user" for access to table.
     * @param STRING $username -> The username for the new user.
     * @param STRING $first_name -> The user First Name.
     * @param STRING $Last_name -> The last name of user
     * @param STRING $email -> email of user for passsword recovery
     * @param STRING $password -> The password for the new user.
     * @param JSON $access -> The JSON file with the table names.
     * @return BOOL false/true -> Returns true if the user is created successfully, false otherwise.
     *  ----------------------------------------- Table Structure -----------------------------------------------
     *  |_______________________________________| Users|________________ ______________________________________
     *  |user_id[INT]|username[txt]|first_name[txt]|last_name[txt]|email[txt]|password[txt]|table_access[json]|-->
     *   ->|always updated||optional: updated using updateUserData() method|<-
     *      ___________________________________________________________|
     *  -->|last_login[datetime]||geo_location[txt] | extra_data[json] |
     */
    public function createUser($username, $first_name, $last_name, $email, $password, $access = '')
    {
        //done!
        $checkUsername = '/^(?=.*[a-zA-Z])(?=.[0-9])[a-zA-Z0-9!@#$%^&*]+$/';
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';
        if (preg_match($checkUsername, $username)) {
            //Check if username contains alphabunericals.
            if (preg_match($checkPassword, $password)) {
                //ckeck if password contains alphanumericals and a symbols.
                $options = ['cost' => 10];
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $sql = "INSERT INTO $this->tableName  (username,first_name,last_name,email,pass_hash,access)
                VALUES ($username,$first_name,$last_name,$email,$derivedPassword,$access);";
                try {
                    $sendDB = $this->Sendquery($sql);
                    $this->responseText['createUser'] = 'User created successfully';
                    $this->updateUserMetadata($username);
                    return true;
                } catch (PDOException $error) {
                    $this->responseText['createUser'] = 'error: ' . $error->getMessage();
                }
            } else {
                $this->responseText['createUser'] = 'user password is not secure enough, missing alphanumerical and/or symbols!';
            }
        } else {
            $this->responseText['createUser'] = 'username must contain alphanumercial';
        }
        return false;
    }
    /**
     * Authenticates user on DB
     * First check if user exists and then authenticates the user.
     * @param STRING $username -> username of user
     * @param STRING $password -> password of user
     * @return BOOL
     */
    public function authenticateUser(string $username, string $password): bool
    {
        if ($this->userExists($username)) {
            $storedPassword = $this->getUsersPassword($username);
            if (password_verify($password, $storedPassword)) {
                $this->responseText['authenticated'] = 'Authenticated';
                return true;
            } else {
                $this->responseText['authenticated'] = 'Password or username combination miss matched!!';
            }
        } else {
            $this->responseText['authenticated'] = 'User does not exist!';
        }
        return false;
    }
    /**
     * Checks if a user with the provided username exists in the database.
     *
     * @param string $username The username to check.
     * @return bool Returns true if the user exists, false otherwise.
     */
    protected function userExists(string $username): bool
    {
        $sql = "SELECT COUNT(*) AS 'count'
        FROM $this->tableName
        WHERE username = $username;";
        $SendDB = $this->Sendquery($sql);
        $row = $SendDB->fetch(PDO::FETCH_ASSOC);
        $exists = $row['count'] === 1 ? true : false;
        return $exists;
        $sql = "SELECT COUNT(*) AS count FROM your_table WHERE username = $username;";
        $SendDB = $this->Sendquery($sql);

        if ($SendDB) {
            $row = $SendDB->fetch(PDO::FETCH_ASSOC);

            if ($row !== false) {
                $exists = $row['count'] === 1 ? true : false;
                return $exists;
            } else {
                // Handle the case where fetch was not successful
                // You might want to log an error or take appropriate action
                return false;
            }
        } else {
            // Handle the case where the query was not successful
            // You might want to log an error or take appropriate action
            return false;
        }
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

        $SendDB = $this->Sendquery($sql);

        if ($SendDB->rowCount() > 0) {
            $row = $SendDB->fetch(PDO::FETCH_ASSOC);
            $password = $row['pass_hash'];
            return $password;
        } else {
            $this->responseText['getpassworduser'] = 'error: password not found';
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
    function updateUserPassword($username, $password, $newPassword): bool
    {
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';

        // User existence check
        if ($this->userExists($username)) {
            // Update password
            $storedPassword = $this->getUsersPassword($username);
            if (password_verify($password, $storedPassword)) {
                if (preg_match($checkPassword, $newPassword)) {
                    $options = ['cost' => 10];
                    $derivedPassword = password_hash($newPassword, PASSWORD_BCRYPT, $options);
                    $sql = "UPDATE $this->tableName SET pass_hash = $derivedPassword  WHERE username = $username;";
                    // Execute the update statement
                    $SendDB = $this->Sendquery($sql);
                    if ($SendDB->rowCount() > 0) {
                        $this->responseText['UpdatePassword'] = 'Password was updated successfully';
                        $this->SetLastlogin($username);
                        return true;
                    } else {
                        $this->responseText['UpdatePassword'] = 'No user found with the specified ID.';
                        return false;
                    }
                } else {
                    $this->responseText['UpdatePassword'] = 'Password does not meet requirements';
                    return false;
                }
            } else {
                $this->responseText['UpdatePassword'] = 'Old password does not match';
                return false;
            }
        } else {
            $this->responseText['UpdatePassword'] = 'User does not exist';
            return false;
        }
    }
    function removeUser($username): bool
    {
        $sql = "DELETE FROM $this->tableName WHERE user_id = $username;";
        try {
            $SendDB = $this->Sendquery($sql);
        } catch (PDOException $th) {
            echo $th;
        }
        if ($SendDB->rowCount() > 0) {
            $this->responseText['removeUser'] = 'User has successfully been removed';
            return true;
        } else {
            $this->responseText['removeUser'] = 'User has  been removed';
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
    function GetUserAccess(string $username, int $Return = null, $accessLevelTable = 'access_level_table', $userTable = 'user_table')
    {
        $accessType = null;
        //check if want access id
        $sql = "SELECT access_id
            FROM $userTable
            WHERE username = $username;";
        if ($$this->Sendquery($sql)->rowCount > 0) {
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

        $levelTableName = $this->Sendquery($sql);

        if ($levelTableName->rowCount() > 0) {
            $row2 = $levelTableName->fetch(PDO::FETCH_ASSOC);
            if (is_array(json_decode($row2[$accessType], 1))) {
                return json_decode($row2[$accessType], 1);
            } else {
                return $row2[$accessType];
            }
        }
        return false;
    }
    protected function SetUserAccess($username, $otherUser, $accessLevel, $userTable = 'user_table')
    {
        $userA_AccessTo = $this->GetUserAccess($username, 1);
        foreach ($userA_AccessTo as $key) {
            if ($key === $accessLevel) {
                $otherUserAccess = $this->GetUserAccess($otherUser, 1);
                $otherUserAccess[] = '';
            }
        }

        //     $AccessTypes2 = $this->GetUserAccess($username, 1);
        //     foreach ($AccessTypes2 as $key=>$value) {
        //         $sql = "UPDATE $accessLevelTable SET access_level = $accessLevel
        //     WHERE username = $username;";
        //         !$this->query($sql);
        //     }
    }

    private function updateUserMetadata($username, bool $geolocation = false, string $extraData = null): bool
    {
        $queryToSend = 'UPDATE';
        $currentDateTime = date('d-m-Y H:i:s');

        if ($geolocation == true) {
            //find location and add to sql
        }
        if ($extraData != null) {
            //convert array to json or save json file
        }
        if ($this->userExists($username)) {
            $sql = "UPDATE $this->tableName SET last_login = $currentDateTime
                    WHERE usename = $username;";
            $this->Sendquery($sql);
            return true;
        }
        return false;
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
                $columns[$notnullindex] .= ' NOT_NULL';
            }
        }
        if (!empty($AutoIncriment)) {
            foreach ($AutoIncriment as $columnindex) {
                $columns[$columnindex] .= ' AUTO_INCREMENT';
            }
        }
        $create_table_sql = 'CREATE TABLE IF NOT EXISTS ' . $this->defaultValues['Database'] . " \. $tablesName  (";
        foreach ($columns as $column) {
            $create_table_sql .= "$column, ";
        }
        // Set the primary key if provided
        if ($primary_key) {
            $create_table_sql .= "PRIMARY KEY ($primary_key), ";
        }
        // Set the unique keys if provided
        $keysToadd = '';
        if ($unique_keys) {
            foreach ($unique_keys as $key) {
                $keysToadd .= $columns[$key] . ',';
            }
            $create_table_sql .= "UNIQUE ($keysToadd)";
        }
        $create_table_sql .= ') ENGINE = InnoDB;';
        // Remove any trailing comma and space
        $create_table_sql = rtrim($create_table_sql, ', ') . ')';
        // Execute the SQL statement to create the table
        try {
            $this->Sendquery($create_table_sql);
            $this->responseText['TableCreate'] = 'Table created successfully';
            return true;
        } catch (PDOException $error) {
            $this->responseText['TableCreate'] = 'Error creating table: ' . $error->getMessage();
        }
        return false;
    }

    public function dropTable($tableName)
    {
        $sql = "DROP TABLE IF EXISTS $tableName";
        return $this->Sendquery($sql);
    }
    // insert
    /**
     * Inserts data into the specified table.
     *
     * @param string $tableName The name of the table.
     * @param array $data An associative array where keys are column names and values are data to be inserted.
     * @return bool Returns true if the insertion is successful, false otherwise.
     */
    public function insertData(string $tableName, array $data)
    {
        // Your code for inserting data goes here
    }

    // update
    /**
     * Updates data in the specified table based on the given conditions.
     *
     * @param string $tableName The name of the table.
     * @param array $data An associative array where keys are column names and values are data to be updated.
     * @param array $conditions An associative array specifying the conditions for the update.
     * @return bool Returns true if the update is successful, false otherwise.
     */
    public function updateData(string $tableName, array $data, array $conditions)
    {
        // Your code for updating data goes here
    }

    // delete
    /**
     * Deletes data from the specified table based on the given conditions.
     *
     * @param string $tableName The name of the table.
     * @param array $conditions An associative array specifying the conditions for the deletion.
     * @return bool Returns true if the deletion is successful, false otherwise.
     */
    public function deleteData(string $tableName, array $conditions)
    {
        // Your code for deleting data goes here
    }

    // select
    /**
     * Selects data from the specified table based on the given conditions.
     *
     * @param string $tableName The name of the table.
     * @param array $conditions An associative array specifying the conditions for the selection.
     * @return array|bool Returns an array of selected data if successful, false otherwise.
     */
    public function selectData(string $tableName, array $conditions)
    {
        // Your code for selecting data goes here
    }

    // alter
    /**
     * Alters the specified table by adding, modifying, or dropping columns.
     *
     * @param string $tableName The name of the table.
     * @param string $alterType The type of alteration (ADD, MODIFY, DROP).
     * @param array $alterations An associative array specifying the alterations to be made.
     * @return bool Returns true if the alteration is successful, false otherwise.
     */
    public function alterTable(string $tableName, string $alterType, array $alterations)
    {
        // Your code for altering the table goes here
    }

    // show table
    /**
     * Shows the list of tables in the database.
     *
     * @return array Returns an array containing the names of tables in the database.
     */
    public function showTables()
    {
        // Your code for showing tables goes here
    }

    // show columns
    /**
     * Shows the columns of the specified table.
     *
     * @param string $tableName The name of the table.
     * @return array Returns an array containing the names of columns in the specified table.
     */
    public function showColumns(string $tableName)
    {
        // Your code for showing columns goes here
    }

    // if exists
    /**
     * Checks if the specified table exists in the database.
     *
     * @param string $tableName The name of the table.
     * @return bool Returns true if the table exists, false otherwise.
     */
    public function tableExists(string $tableName)
    {
        // Your code for checking if the table exists goes here
    }
}
