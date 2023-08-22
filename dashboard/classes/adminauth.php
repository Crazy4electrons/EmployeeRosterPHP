<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * admin class is used to save and match
 *  admin user password with saved
 * hash file aswell as to create new ones 
 * and save and retrieve values using json strings
 *
 */
class AdminAuthfrom
{
    private static $adminAuth = './adminAuth.json';
    protected $userNames;
    private $response;

    public function __construct()
    {
        $this->refreshUserNames();
    }

    public function authenticateUser(string $username, string $password)
    {
        $authenticated = false;
        $checkPassword = '/^[A-Za-z0-9]+$/';
        $checkUsername = '/^i[a-z0-9_]+$/';

        if (preg_match($checkUsername, $username)) {
            if (preg_match($checkPassword, $password)) {
                if ($this->userExists($username)) {
                    $storedPassword = $this->getUsersPassword($username);
                    error_log($storedPassword); // Debug: Display stored password
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

    public function adminAddAdmin($username, $password, $adminUsername, $adminPassword)
    {
        if (!$this->userExists($username)) {
            if ($this->authenticateUser($adminUsername, $adminPassword)) {
                $this->createUser($username, $password);
            }
        }
    }

    function userExists(string $username)
    {
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            return true;
        }
        error_log("User does not exist: " . $username); // Log error message
        return false;
    }

    protected function getUsersPassword($username)
    {
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            $password = $this->userNames[$username];
            return $password;
        }
        error_log("Failed to get user's password for username: " . $username); // Log error message
        return null;
    }

    private function createUser($username, $password)
    {

        $checkPassword = '/^[A-Za-z0-9]+$/';
        $checkUsername = '/^i[a-z0-9_]+$/';
        if (preg_match($checkUsername, $username)) {
            if (preg_match($checkPassword, $password)) {
                $options = array('cost' => 10);
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $this->userNames[$username] = $derivedPassword;
                $jsonSavefile = json_encode($this->userNames);
                file_put_contents(self::$adminAuth, $jsonSavefile);
                $this->response['message'] = ['createuser' => 'true'];
                return true;
            } else {
                error_log("Invalid password format"); // Log error message
                $this->response['message'] = ['createuser' => 'false'];
                return false;
            }
        } else {
            error_log("Invalid username format"); // Log error message
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
            if (touch(self::$adminAuth)) {
                $jsonfile = file_get_contents(self::$adminAuth, false);
                $this->userNames = json_decode($jsonfile);
                if (!isset($this->userNames['root']) && empty($this->userNames['root'])) {
                    $this->userNames['eds'] = 'No1PassAdmin';
                    $jsonSav1stAdmin = json_encode($this->userNames);
                    file_put_contents(self::$adminAuth, $jsonSav1stAdmin);
                }
                $this->response['message'] = ['filefind' => 'newfile created',];
            } else {
                $this->response['message'] = ['filefind' => "file couldn't be created \n username not saved",];
            }
        }
    }
    function getresponsedata()
    {
        if (!(file_exists("./response.json"))) {

            $contents = json_encode($this->response);
            if (touch("./response")) {
                file_put_contents("./response.json", $contents);
            }
        }


        $jsonresponse = json_encode($this->response);
        return $jsonresponse;
    }
}


$adminget = new AdminAuthfrom();
if (isset($_POST['message']) && !empty($_POST['message'])) {
    error_log($_POST['message']);
    $clientdata = json_decode($_POST['message']);
    if (isset($clientdata['admindo']) && !empty($clientdata['admindo'])) {
        switch ($clientdata['admindo']) {
            case 'auth':
                if ($adminget->authenticateUser($clientdata['username'], $clientdata['password'])) {
                    $responseData = $adminget->getresponsedata();
                    ob_clean();
                    ob_start('OB_FLUSH');
                    echo $responseData;
                    ob_flush();
                    error_log($adminget->getresponsedata());
                }
                break;
            case 'addadmin':
        }
    }
}
