<?php
if (isset($_POST["name"]) && !empty($_POST["name"])) {
    $name = $_POST["name"];
    if (!preg_match("/^[a-zA-Z ]*$/",$name)){
        echo "Name: Only letters and whitespace allowed";
    }else{
        echo "Name: ".$_POST["name"]."\n";
    }
}
if (isset($_POST["password"]) && !empty($_POST["password"])) {
    $password = $_POST["password"];
    if(strlen($password)<= 8){
        echo "password must be more than 8 characters";
    }
}
/**
* Methods for database handling.
*/
class DB extends SQLite3
{
    public const DATABASE_NAME = ’users.db’;
    public const BCRYPT_COST = 14;
    /**
    * DB class constructor. Initialize method is called, which will create users table if it does
    * not exist already.
    */
    public function __construct()
    {
        $this->open(self::DATABASE_NAME);
        $this->initialize();
    }
/**
* Creates the table if it does not exist already.
*/
protected function initialize() 
    $sql = 'CREATE TABLE IF NOT EXISTS user (
        username STRING UNIQUE NOT NULL,
        password STRING NOT NULL
        )';
    $this->exec($sql);
    /**
    * Authenticates the given user with the given password. If the user does not exist,any action
    * is performed. If it exists, its stored password is retrieved, and then password_verify
    * built-in function will check that the supplied password matches the derived one.
    *
    * @param $username The username to authenticate.
    * @param $password The password to authenticate the user.
    * @return True if the password matches for the username, false if not.
    */
    public function authenticateUser($username, $password) {
    if ($this->userExists($username)) {
    $storedPassword = $this->getUsersPassword($username);
    if (password_verify($password, $storedPassword)) {
    $authenticated = true;
    } else {
    $authenticated = false;
    }
    return $authenticated;
    }
    /**
    * Checks if the given users exists in the database.
    *
    * @param $username The username to check if exists.
    * @return True if the users exists, false if not.
    */
protected function userExists($username) {
        $sql = 'SELECT COUNT(*) AS count
    FROM user
    WHERE username = :username';
    $statement = $this->prepare($sql);
    $statement->bindValue(’:username’, $username);
    $result = $statement->execute();
    $row = $result->fetchArray();
    $exists = ($row[’count’] === 1) ? true : false;
    $statement->close();
    return $exists;
    }
    /**
    * Gets given users password.
    * @param $username The username to get the password of.
* @return The password of the given user.
*/
protected function getUsersPassword($username) {
    $sql = ’SELECT password
    FROM user
    WHERE username = :username’;
    $statement = $this->prepare($sql);
    $statement->bindValue(’:username’, $username);
    $result = $statement->execute();
    $row = $result->fetchArray();
    $password = $row[’password’];
    $statement->close();
    return $password;
    }
    /**
    * Creates a new user.
    *
    * @param $username The username to create.
    * @param $password The password of the user.
    */
    public function createUser($username, $password) {
    $sql = ’INSERT INTO user
    VALUES (:username, :password)’;
    $options = array(’cost’ => self::BCRYPT_COST);
    $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
    $statement = $this->prepare($sql);
    $statement->bindValue(’:username’, $username);
    $statement->bindValue(’:password’, $derivedPassword);
    $statement->execute();
    $statement->close();
    }
    }
    