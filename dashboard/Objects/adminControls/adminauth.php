<?php
/**
 * admin class is used to save and match
 * admin user password with saved hash file as well as to create new ones 
 * and save and retrieve values using JSON strings.
 */
class AdminAuthForm
{
    /**
     * The path to the admin authentication JSON file.
     */
    private static $adminAuth = './adminAuth.json';

    /**
     * An array of usernames.
     */
    protected $userNames;

    /**
     * An array to store response data.
     */
    public $response = array();

    /**
     * Constructor method.
     *
     * @param string|null $pathToFile The path to the admin authentication JSON file.
     */
    function __construct($pathToFile = null)
    {
        if ($pathToFile != null) {
            self::$adminAuth = $pathToFile;
        }
        $this->refreshUserNames();
    }

    /**
     * Authenticates a user by checking their username and password.
     *
     * @param string $username The username.
     * @param string $password The password.
     * @return bool Returns true if the user is authenticated, false otherwise.
     */
    protected function authenticateUser(string $username, string $password): bool
    {
        // Regular expressions to validate username and password
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
                        $this->response['UserAuth'] = "Not verified";
                        return false;
                    }
                }
            } else {
                $this->response['UserAuth'] = "User does not exist";
                return false;
            }
        }
        $this->response['UserAuth'] = "false";
        return false;
    }

    /**
     * Adds a new user to the admin authentication system.
     *
     * @param string $username The username of the new user.
     * @param string $password The password of the new user.
     * @param string $adminUsername The username of the admin user.
     * @param string $adminPassword The password of the admin user.
     * @return bool Returns true if the user was added successfully, false otherwise.
     */
    protected function adminAddUser($username, $password, $adminUsername, $adminPassword)
    {
        if ($this->authenticateUser($adminUsername, $adminPassword)) {
            if (!$this->userExists($username)) {
                if ($this->createUser($username, $password)) {
                    $this->response['UserCreate'] .= $username . " was created successfully";
                    return true;
                } else {
                    $this->response['UserCreate'] = "There was an error, try again.";
                    return false;
                }
            } else {
                $this->response['UserCreate'] = "User already exists";
                return false;
            }
        }
        $this->response['UserCreate'] = "false";
        return false;
    }

    /**
     * Checks if a user exists in the admin authentication system.
     *
     * @param string $username The username to check.
     * @return bool Returns true if the user exists, false otherwise.
     */
    protected function userExists(string $username)
    {
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            $this->response['UserExists'] = "User does exist: " . $username;
            return true;
        }
        $this->response['UserExists'] = "User does not exist: " . $username;
        return false;
    }

    /**
     * Updates a user's password in the admin authentication system.
     *
     * @param string $username The username of the user.
     * @param string $password The current password of the user.
     * @param string $newPassword The new password for the user.
     * @return bool Returns true if the password was updated successfully, false otherwise.
     */
    protected function updateUserPassword($username, $password, $newPassword)
    {
        $checkPassword = '/^(?=.*[a-zA-Z])(?=.[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';

        // User existence check
        if ($this->userExists($username)) {
            // Update password
            $storedPassword = $this->getUsersPassword($username);
            if (password_verify($password, $storedPassword)) {
                if (preg_match($checkPassword, $newPassword)) {
                    $this->userNames[$username] = $newPassword;
                    $this->refreshUserNames();
                    $this->response['UpdatePassword'] = "Password was updated successfully";
                    return true;
                } else {
                    $this->response['UpdatePassword'] = "Password does not meet requirements";
                    return false;
                }
            } else {
                $this->response['UpdatePassword'] = "Old password does not match";
                return false;
            }
        } else {
            $this->response['UpdatePassword'] = "User does not exist";
            return false;
        }
    }

    /**
     * Retrieves all usernames from the admin authentication system.
     */
    public function getAllUsernames()
    {
        foreach ($this->userNames as $key => $value) {
            $this->response['GetallUsernames'] .= $key;
        }
    }
    
    /**
     * Gets the count of users in the admin authentication system.
     *
     * @return int The number of users.
     */
    public function getUserCount():int
    {
        if(!empty($this->userNames)) { 
            $count = count($this->userNames);
            $this->response['getUserCount'] = $count;
            return $count;
        }
        return 0;
    }

    /**
     * Retrieves a user's password from the admin authentication system.
     *
     * @param string $username The username of the user.
     * @return string|null The user's password, or null if it couldn't be retrieved.
     */
    protected function getUsersPassword($username): string
    {
        if (isset($this->userNames[$username]) && !empty($this->userNames[$username])) {
            $password = $this->userNames[$username];
            return $password;
        }
        $this->response['GetPass'] = "Failed to get user's password for username: " . $username;
        return null;
    }

    /**
     * Creates a new user in the admin authentication system.
     *
     * @param string $username The username of the new user.
     * @param string $password The password of the new user.
     * @return bool Returns true if the user was created successfully, false otherwise.
     */
    protected function createUser($username, $password): bool
    {
        $checkPassword = '/^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/';
        $checkUsername = '/^(?=.+[0-9])[a-zA-Z0-9]+$/';

        if (preg_match($checkUsername, $username)) {
            if (!$this->userExists($username)) {
                if (preg_match($checkPassword, $password)) {
                    $options = array('cost' => 10);
                    $derivedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                    $this->userNames[$username] = $derivedPassword;
                    $this->refreshUserNames();
                    $this->response['CreateUser'] = 'true';
                    return true;
                } else {
                    $this->response['CreateUser'] = 'Password not correct';
                    return false;
                }
            } else {
                $this->response['CreateUser'] = 'User already exists';
                return false;
            }
        } else {
            $this->response['CreateUser'] = 'Username not correct';
            return false;
        }
    }

    /**
     * Removes a user from the admin authentication system.
     *
     * @param string $username The username of the user to remove.
     * @return bool Returns true if the user was removed successfully, false otherwise.
     */
    protected function removeUser($username)
    {
        if ($this->userExists($username)) {
            unset($this->userNames[$username]);
            $this->response['deleteUser'] = 'true';
            $this->refreshUserNames();
            return true;
        } else {
            $this->response['deleteUser'] = 'false';
            return false;
        }
    }

    /**
     * Refreshes the userNames array and saves it to the admin authentication JSON file.
     */

    protected function refreshUserNames()
    {
        if (empty($this->userNames)) {
            if (file_exists(self::$adminAuth)) {
                $this->userNames = json_decode(file_get_contents(self::$adminAuth, true), true);
                $this->response['SaveFile'] .= "file exists and contents successfully retrieved";
            } else {
                if (touch(self::$adminAuth)) {
                    $this->response['SaveFile'] = "newfile created";
                    if ($this->createUser('admin123', 'admin@123')) {
                        $this->response['SaveFile'] .= " default admin added";
                    }
                } else {
                    $this->response['SaveFile'] = "file couldn't be created \n username not saved";
                }
            }
        } else {
            file_put_contents(self::$adminAuth, json_encode($this->userNames));
            $this->response['SaveFile'] = "UserNames saved successfully";
        }
    }

    function getResponseData(): string
    {
        // return $this->response;
        return json_encode($this->response);
    }
}
