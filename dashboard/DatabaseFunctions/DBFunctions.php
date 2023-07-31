<?php
class DBaccess
{
    protected $Hostname = 'localhost';
    public $Ausername = 'UAGAdmin';
    protected $Apassword = 'sFhgbUnua7IaNG7u';
    private $Database = "employee_rosters";
    public $DBconnect;
    public $username;

    public function __construct($username, $passuser, $adduser = false)
    {
        $this->username = $username;
        $this->initialize($username, $passuser, $adduser);
    }
    protected function initialize(string $username,string $password, bool $adduser=false){
        try {
            $this->DBConnect = new PDO("mysql:host=$this->Hostname;dbname=$this->Database;charset=utf8", $this->Ausername, $this->Apassword, array(PDO::ATTR_PERSISTENT => true));
            // set the PDO error mode to exception
            $this->DBConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<p>Connected successfully</p>";
        } catch(PDOException $eror) {
            echo "Connection failed: " . $eror->getMessage();
        }
        $userATable = "CREATE TABLE IF NOT EXISTS :accessDB.useradmins
            (username TEXT NOT NULL,
            password TEXT NOT NULL,
            UNIQUE (username)
            ) ENGINE= InnoDB";
        $SendDB = $this->DBConnect->prepare($userATable);
        $SendDB->bindvalue(':accessDB', $this->Database);
        try {
            $SendDB->execute();
        } catch(PDOException $eror) {
            echo $eror;
        }
        $SendDB=null;

        if($adduser === true) {
            $this->createUser($username, $password);
        } elseif($this->authenticateUser($this->username, $password)) {
            return $this->DBConnect;
        } else {
            header("Location: ".$_SERVER['HTTP_HOST']."/dashboard/index.php/?login=false");
            exit;
        }
    }
    public function authenticateUser(string $username, string $password)
    {
        if($this->userExists($username)) {
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

    protected function userExists(string $username)
    {
        $sql = "SELECT COUNT(*) AS count
        FROM user
        WHERE username = :username";
        $SendDB = $this->DBconnect->prepare($sql);
        $SendDB->bindValue(':username', $username);
        try {
            $SendDB->execute();
        } catch(PDOException $eror) {
            echo $eror;
        }
        $row = $SendDB->fetchArray(PDO::FETCH_ASSOC);
        $exists = ($row['count'] === 1) ? true : false;
        $SendDB->close();
        return $exists;
    }
    protected function getUsersPassword($username)
    {
        $sql = 'SELECT password
            FROM user
            WHERE username = :username';
        $SendDB = $this->DBconnect->prepare($sql);
        $SendDB->bindValue(':username', $username);
        try {
            $SendDB->execute();
        } catch(PDOException $eror) {
            echo $eror;
        }
        $row = $SendDB->fetchArray(PDO::FETCH_ASSOC);
        $password = $row['password'];
        $SendDB=null;
        return $password;
    }

    public function createUser($username, $password)
    {
        $sql = 'INSERT INTO user
        VALUES (:username, :password)';
        $options = array('cost' => 10);
        $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
        $sendDB = $this->DBconnect->prepare($sql);
        $sendDB->bindValue(':username', $username);
        $sendDB->bindValue(':password', $derivedPassword);
        try {
            $sendDB->execute();
        } catch (PDOException $eror) {
            echo $eror;
        }
        $sendDB->null;
        return $this->DBConnect;
    }

    public function printDB()
    {
        print_r($this->DBConnect);
    }
}
