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
    function __construct()
    {
        $this->RefreshUserNames();
    }

    function authenticateUser(string $username, string $password)
    {
        $authenticated = false;
        $checkpassword  = '/\b[A-Za-z0-9]+\b/';
        $checkusername = '/\bi[a-z0-9_]+\b/';
        if (preg_match($checkusername, $username)) {
            if (preg_match($checkpassword, $password)) {
                if ($this->userExists($username)) {
                    $storedPassword = $this->getUsersPassword($username);
                    if (password_verify($password, $storedPassword)) {
                        $this->response['content'] = ['verify' => 'true'];
                        $authenticated = true;
                    } else {
                        $this->response['content'] = ['verify' => 'false'];
                        $authenticated = false;
                    }
                }
            }
        }
        return $authenticated;
    }
    function adminaddadmin($username, $password, $adminusername, $adminpassword)
    {
        if (!$this->userExists($username)) {
            if ($this->authenticateUser($adminusername, $adminpassword)) {
                $this->CreateUser($username, $password);
            }
        }
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
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            $password = $this->userNames[$username];
            return $password;
        }
        return null;
    }

    private function CreateUser($username, $password)
    {
        $checkpassword  = '/\b[A-Za-z0-9]+\b/';
        $checkusername = '/\bi[A-Za-z0-9_]+\b/';
        if (preg_match($checkusername, $username)) {
            if (preg_match($checkpassword, $password)) {
                $options = array('cost' => 10);
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $this->userNames[$username] = $derivedPassword;
                $this->response['message'] = ['createuser' => 'true'];
                return true;
            } else {
                $this->response['message'] = ['createuser' => 'false'];
                return false;
            }
        } else {
            $this->response['message'] = ['createuser' => 'false'];
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
                $this->response['message'] = ['filefind' => 'newfile created',];
            } else {
                $this->response['message'] = ['filefind' => "file couldn't be created \n username not saved",];
            }
        }
    }
    function getresponsedata(): string
    {
        // $jsonreponse = json_encode($this->response);
        return $this->response;
    }
}


$adminget = new AdminAuth();
if (isset($_POST['message']) && !empty($_POST['message'])) {
    $clientdata = json_decode($_POST['message']);
    if (isset($clientdata['admindo']) && !empty($clientdata['admindo'])) {
        switch ($clientdata['admindo']) {
            case 'auth':
                if ($adminget->authenticateUser($clientdata['username'], $clientdata['password'])) {
                    echo $adminget->getresponsedata();
                }
                break;
            case 'addadmin':
        }
    }
}
