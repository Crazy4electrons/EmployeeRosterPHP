<?php
class SessionFunctions
{
    /**
     * this is a object which set or get session variables
     * and sets session name and path.
     */
    private static $isCalled = false;
    private static $name = 'user';
    private static $sessionPath = "./Environmentvariables/";
    function __construct()
    {
        if (!self::$isCalled) {
            if (session_status() != PHP_SESSION_DISABLED ) {
                session_name(self::$name);
                session_save_path(self::$sessionPath);
                session_start();
                self::$isCalled = true;
            }else{
                return session_status();
            }
        }else{
            session_regenerate_id();
            return null;
        }
    }
    function setSessionValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // Function to get a value from the session array
    function getSessionValue($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    // Function to check if a key exists in the session array
    function isKeyExistsInSession($key)
    {
        return isset($_SESSION[$key]);
    }

    // Function to remove a key from the session array
    function removeKeyFromSession($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    //function to reset session
    function resetSession()
    {
        if (session_reset()) {
            return true;
        }
        return false;
    }
    //clear session variable
    function unsetSession()
    {
        if (session_unset()) {
            return true;
        }
        return false;
    }
}
