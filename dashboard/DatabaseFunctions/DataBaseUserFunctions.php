<?php
class DBaccess
{
    /**
     * This class can be used with js for dynamically update web content and as well
     * as with php to confirm host access only and change host acces acordingly
     * @param mixed $Password -> Please use HashPassGen($password) with php to 
     * generate a stong hash file to save Passwords on DB 
     * when  object is started Hostname,Database,user_for_database,and Pass_for_user
     * otherwise object will initialise with dafault and cant 
     * be change unless you call the _destruct method
     * 
     */

    protected static $isCalled = false;
    protected $Hostname = 'localhost';
    protected $Apassword = 'N0@dminP@ss';
    private $Database = 'MyFirstDB';
    public $Ausername = 'Admin';
    public $tableName = 'userAdmins';
    public $DBConnect;
    public $responseText;

    /**
     * Constructor for DBaccess class.
     *
     * @param string $username The username to authenticate.
     * @param string $passuser The password for the user.
     * @param bool $adduser Flag to indicate if a new user should be created.
     */
    public function __construct(string $userNameTable = null, $options = ['Ausername' => null, 'Apassuser' => null, 'Hostname' => null, '$Database' => null])
    {
        /**
         * if class is start all values must be set before hand 
         * otherwise it will use default values and function needs to _destruct 
         * before values can be modified.
         * @param __destruct is call at end of the php script or 
         * if you use call_destruct() methode
         */
        if (!self::$isCalled) {
            $this->Ausername = $options['Ausername'] ??;
            foreach ($options as $key => $value) {
                if ($value != null) {
                    switch ($key) {
                        case 'Ausername':
                            $this->Ausername = $value;
                            break;
                        case 'Apassuser':
                            $this->Apassword = $value;
                            break;
                        case 'Hostname':
                            $this->Hostname = $value;
                            break;
                        case 'Database':
                            $this->Database = $value;
                            break;
                    }
                }
            }
            if ($userNameTable != null) {
                $this->tableName = $userNameTable;
            }

            $this->initialize();
            self::$isCalled = true;
            $this->responseText['initialize'] = true;
            return true;
        }
        $this->responseText['initialize'] = false;
        return false;
    }
    function call_destruct()
    {
        $this->__destruct();
    }
    private  function __destruct()
    {
        if (self::$isCalled) {
            if ($this->DBConnect != null) {
                $this->closeConnection();
                unset($this->DBConnect);
            }
            unset($this->responseText);
            $this->responseText['initialize'] = 'empty';
            self::$isCalled = false;
        }
    }

    /**
     * Initializes the database connection and creates the user table if it doesn't exist.
     *
     * @param string $username The username to authenticate.
     * @param string $password The password for the user.
     * @param bool $adduser Flag to indicate if a new user should be created.
     * @return PDO|bool Returns the database connection object on successful authentication, or false otherwise.
     */
    protected function initialize()
    {
        try {
            $this->DBConnect = new PDO(
                "mysql:host=$this->Hostname;
            dbname=$this->Database;charset=utf8",
                $this->Ausername,
                $this->Apassword,
                array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => 'ERRMODE_EXCEPTION')
            );
            // Set the PDO error mode to exception
            //$this->DBConnect->setAttribute();
            $this->responseText['connection'] = "Connected";
            return true;
        } catch (PDOException $error) {
            $this->responseText['connection'] = "Connection failed: " . $error->getMessage();
            return true;
        }
    }

    /**
     * Authenticates a user by checking if the provided username and password match the stored values.
     *
     * @param string $username The username to authenticate.
     * @param string $password The password for the user.
     * @return bool Returns true if the user is authenticated, false otherwise.
     */
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

    /**
     * Checks if a user with the provided username exists in the database.
     *
     * @param string $username The username to check.
     * @return bool Returns true if the user exists, false otherwise.
     */
    public function userExists(string $username): bool
    {
        $sql = "SELECT COUNT(*) AS 'count'
        FROM $this->tableName
        WHERE username = $username;";
        try {
            $SendDB = $this->DBConnect->prepare($sql);
            $SendDB->execute();
        } catch (PDOException $error) {
            echo $error;
        }
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
    protected function getUsersPassword($username): string
    {

        $sql = "SELECT password
            FROM  $this->tableName
            WHERE username = $username;";
        try {
            $SendDB = $this->DBConnect->prepare($sql);
            $SendDB->execute();
        } catch (PDOException $error) {
            echo $error;
        }
        if ($SendDB->rowCount() > 0) {
            $row = $SendDB->fetch(PDO::FETCH_ASSOC);
            $password = $row['password'];
            return $password;
        } else {
            return false;
        }
    }
    /**
     * Creates a new user with the provided username and password.
     *
     * @param string $username The username for the new user.
     * @param string $password The password for the new user.
     * @return bool Returns true if the user is created successfully, false otherwise.
     */
    public function createUser($username, $password)
    {
        $checkUsername = '/^(?=.*[a-zA-Z])(?=.[0-9])[a-zA-Z0-9!@#$%^&*]+$/';
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';
        if (preg_match($checkUsername, $username)) {
            if (preg_match($checkPassword, $password)) {
                $options = array('cost' => 10);
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $sql = "INSERT INTO $this->tableName  (username,password)
            VALUES ($username, $derivedPassword)
            ON DUPLICATE KEY UPDATE username = $username;";
                $sendDB = $this->DBConnect->prepare($sql);
                try {
                    $sendDB->bindValue(':username', $username);
                    $sendDB->bindValue(':password', $derivedPassword);
                    $sendDB->execute();
                    return true;
                } catch (PDOException $error) {
                    echo $error;
                }
                $this->responseText['createUser'] = "User created successfully";
                return false;
            } else {
                $this->responseText['createUser'] = "user password is not secure enough";
            }
        }else {
            $this->responseText['createUser'] = "username is must contain alphanumercial";
        }
    }
    function removeUser($username): bool
    {
        $sql = "DELETE FROM $this->tableName WHERE id = $username;";
        $SendDB = $this->DBConnect->prepare($sql);
        try {
            $SendDB->execute();
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
    /**
     * Updates a user's password in the admin authentication system.
     *
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
                    $stmt = "UPDATE users SET password = $newPassword  WHERE id = $username;";
                    // Execute the update statement
                    try {
                        //code...
                        $SendDB = $this->DBConnect->prepare($stmt);
                        $SendDB->execute();
                        if ($SendDB->rowCount() > 0) {
                            $this->responseText['UpdatePassword'] = "Password was updated successfully";
                        } else {
                            $this->responseText['UpdatePassword'] = "No user found with the specified ID.";
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    return true;
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

    /**
     * Creates a table with the provided name, columns, primary key, and unique keys.
     *
     * @param string $table_name The name of the table to create.
     * @param array $columns An array of column definitions.
     * @param string|null $primary_key The primary key column name.
     * @param array|null $unique_keys An array of unique key column names.
     * @return bool Returns true if the table is created successfully, false otherwise.
     */
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
            $sql->execute();
            echo "Table created successfully";
            return true;
        } catch (PDOException $error) {
            echo "Error creating table: " . $error;
            return false;
        }
    }

    /**
     * Closes the database connection.
     *
     * @return bool Returns true if the connection is closed successfully, false otherwise.
     */
    public function closeConnection()
    {
        if ($this->DBConnect->close()) {
            $this->responseText['closeConnection'] = 'connection closed';
            return true;
        } else {
            $this->responseText['closeConnection'] = 'connection not closed';
            return false;
        }
    }

    /**
     * Prints the database connection object for debugging purposes.
     */
    public function printDB()
    {
        print_r($this->DBConnect);
    }
    /**
     * turns response text into a json string and then returns
     *
     * @return string
     */
    function returnResponse(): string
    {
        return json_encode($this->responseText);
    }
}
