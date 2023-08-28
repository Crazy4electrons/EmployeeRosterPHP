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

    function __construct()
    {
        $this->refreshUserNames();
    }

    function authenticateUser(string $username, string $password): bool
    {
        $checkPassword = '/^[A-Za-z0-9]+$/';
        $checkUsername = '/^[a-z0-9_]+$/';

        if (preg_match($checkUsername, $username)) {
            if ($this->userExists($username)) {
            if (preg_match($checkPassword, $password)) {
                    $storedPassword = $this->getUsersPassword($username);
                    if (password_verify($password, $storedPassword)) {
                        $this->response['UserAuth'] = 'true';
                        return true;
                    } else {
                        $this->response['UserAuth'] = 'false';
                        return false;
                    }
                }
            }
        }
        return false;
    }

    public function adminAddAdmin($username, $password, $adminUsername, $adminPassword)
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

        $checkPassword = '/^[A-Za-z0-9]+$/';
        $checkUsername = '/^[a-z0-9]+$/';
        if (preg_match($checkUsername, $username)) {
            if (preg_match($checkPassword, $password)) {
                $options = array('cost' => 10);
                $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                $this->userNames[$username] = $derivedPassword;
                $jsonSavefile = json_encode($this->userNames);
                file_put_contents(self::$adminAuth, $jsonSavefile);
                $this->response['CreateUser'] = ['true'];
                return true;
            } else {
                $this->response['CreateUser'] = ['false'];
                return false;
            }
        } else {
            $this->response['CreateUser'] = ['false'];
            return false;
        }
    }


    protected function refreshUserNames()
    {
        if (file_exists(self::$adminAuth)) {
            $this->response['SaveFile'] = ["The file " . self::$adminAuth . " exists."];
            $jsonfile = file_get_contents(self::$adminAuth, true);
            $this->userNames = json_decode($jsonfile, true);
        } else {
            if (touch(self::$adminAuth)) {
                $this->response['SaveFile'] = "newfile created";
                if (!isset($this->userNames['eds']) && empty($this->userNames['eds'])) {
                    if ($this->createUser('eds', 'No1PassAdmin')) {
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


$adminget = new AdminAuthForm();
$data = json_decode($_POST['data'], true);
if (isset($data['AdminPassword']) && !empty($data['AdminUsername'])) {

    if ($adminget->authenticateUser($data['AdminUsername'], $data['AdminPassword'])) {
        $responseData = $adminget->getResponseData();
        echo $responseData;
    } else {
        $responseData = $adminget->getResponseData();
        echo $responseData;
    }
} else {
    echo "empty post";
}

// $data = json_decode($_POST['data'],true);
// //   $adminUsername = $_POST['AdminUsername'];
//   $adminUsername = "user response";
// //   $adminPassword = $_POST['AdminPassword'];
//   $adminPassword = "password response";

//   // Perform any necessary operations with the received data

//   // Prepare the response data
//   $responseData = [
//     'message' => 'Success',
//     'data' => [
//       'adminUsername' => $adminUsername,
//       'adminPassword' => $adminPassword
//     ]
//   ];

//   // Send the response back to the JavaScript code
// //   echo json_encode($data);
// echo($data['AdminPassword']);