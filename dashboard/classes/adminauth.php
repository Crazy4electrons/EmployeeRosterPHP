<?php

/**
 * admin class is used to save and match
 *  admin user password with saved
 * hash file aswell as to create new ones 
 * and save and retrieve values using json strings
 *
 */
class AdminAuthForm
{
    private static $adminAuth = './adminAuth.json';
    protected $userNames;
    public $response = array();
    function __construct($pathToFile = null)
    {
        if ($pathToFile != null) {
            self::$adminAuth = $pathToFile;
        }
        $this->refreshUserNames();
    }

    function authenticateUser(string $username, string $password): bool
    {
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';
        $checkUsername = '/^(?=.+[0-9])[a-zA-Z0-9]+$/';

        if (preg_match($checkUsername, $username)) {
            if ($this->userExists($username)) {
                if (preg_match($checkPassword, $password)) {
                    $storedPassword = $this->getUsersPassword($username);
                    if (password_verify($password, $storedPassword)) {
                        $this->response['UserAuth'] = "true";
                        return true;
                    } else {
                        $this->response['UserAuth'] = "false";
                        return false;
                    }
                }
            }
        }
        $this->response['UserAuth'] = "false";
        return false;
    }

    protected function adminAddAdmin($username, $password, $adminUsername, $adminPassword)
    {
        if ($this->authenticateUser($adminUsername, $adminPassword)) {
            if (!$this->userExists($username)) {
                $this->createUser($username, $password);
                $this->response['UserCreateSuccess'] = "" . $username . " was created successfully";
                return true;
            }
        }
        return false;
    }

    function userExists(string $username)
    {
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            $this->response['UserExists'] = "User does: " . $username; // Log error message
            return true;
        }
        $this->response['UserExists'] = "User does not exist: " . $username; // Log error message
        return false;
    }

    protected function getUsersPassword($username)
    {
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            $password = $this->userNames[$username];
            return $password;
        }
        $this->response['GetUserPass'] = "Failed to get user's password for username: " . $username; // Log error message
        return null;
    }

    protected function createUser($username, $password)
    {

        $checkPassword = '/^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';
        $checkUsername = '/^(?=.+[0-9])[a-zA-Z0-9]+$/';
        if (preg_match($checkUsername, $username)) {
            if (preg_match($checkPassword, $password)) {
                $options = array('cost' => 10);
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $this->userNames[$username] = $derivedPassword;
                $jsonSavefile = json_encode($this->userNames);
                file_put_contents(self::$adminAuth, $jsonSavefile);
                $this->response['CreateUser'] = 'true';
                return true;
            } else {
                $this->response['CreateUser'] = 'false';
                return false;
            }
        } else {
            $this->response['CreateUser'] = 'false';
            return false;
        }
    }
    protected function removeUser($username)
    {
        if ($this->userExists($username)) {
            unset($this->userNames[$username]);
            $this->response['deleteUser'] = 'true';
            return true;
        }else{
            $this->response['deleteUser'] = 'false';
            return false;

        }
    }


    protected function refreshUserNames()
    {
        if (file_exists(self::$adminAuth)) {
            $this->response['SaveFile'] = "The file " . self::$adminAuth . " exists.";
            $jsonfile = file_get_contents(self::$adminAuth, true);
            $this->userNames = json_decode($jsonfile, true);
        } else {
            if (touch(self::$adminAuth)) {
                $this->response['SaveFile'] = "newfile created";
                if (!isset($this->userNames['eds1st']) && empty($this->userNames['eds1st'])) {
                    if ($this->createUser('eds1st', 'No1Pass@dmin')) {
                        $this->response['SaveFile'] .= " + default admin added";
                    }
                }
            } else {
                $this->response['SaveFile'] = "file couldn't be created \n username not saved";
            }
        }
    }

    function getResponseData(): string
    {
        // return $this->response;
        return json_encode($this->response);
    }
}



