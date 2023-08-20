<?php

/**
 * admin class is used to save and match
 *  admin user password with saved
 * hash file aswell as to create new ones 
 * and save and retrieve values using json strings
 *
 */
class adminAuth
{
    private static $adminAuth = './adminAuth.json';
    protected $userNames;
    private $response;
    private $message;
    function __construct()
    {
        /**
         * check if file is already created and then read file
         * else
         * create file.
         */
        $this->RefreshUserNames();
        if (isset($_POST['message']) && !empty($_POST['message'])) {
            $this->message = json_decode($_POST['message']);
        }else{
            $response = "no text in post";
        }
    }
    function authenticateUser(string $username, string $password)
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

    function userExists(string $username)
    {
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            return true;
        }
        return false;
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

    public function CreateUser($username, $password)
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
    private function RefreshUserNames()
    {
        if (file_exists(self::$adminAuth)) {
            $response['message'] = ['filefind' => "The file " . self::$adminAuth . " exists."];
            $jsonfile = file_get_contents(self::$adminAuth, false);
            $this->userNames = json_decode($jsonfile);
        } else {
            if (touch($this->adminAuth)) {
                $jsonfile = file_get_contents(self::$adminAuth, false);
                $this->userNames = json_decode($jsonfile);
                $response['message'] = ['filefind' => 'newfile created',];
            } else {
                $response['message'] = ['filefind' => "file couldn't be created \n username not saved",];
            }
        }
    }
}
