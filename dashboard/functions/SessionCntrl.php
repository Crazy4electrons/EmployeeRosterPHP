<?php
class SessionFunctions
{
    private static $isCalled = false;
    private static $name = 'user';
    private static $sessionPath = "./Environmentvariables/";
    // Function to set a value in the session array
    function __construct()
    {
    if (!self::$isCalled) {
        session_name(self::$name);
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
    function resetSession()
    {
        if (session_reset()) {
            return true;
        }
        return false;
    }
    function unsetSession()
    {
        if (session_unset()) {
            return true;
        }
        return false;
    }
}
