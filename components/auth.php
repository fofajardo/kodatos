<?php

/* @FILE: auth.php
   © Francis Dominic Fajardo - All Rights Reserved
   Unauthorized copying of this file, via any medium is strictly prohibited
   Proprietary and confidential
*/

Framework::loadMultiple(["DBACC", "DBSES"]);

class Auth
{
    private static $accountID = null;
    private static $roleID = null;
    private static $groupID = null;
    private static $sessionID = null;
    private static $accountEnabled = null;
    private static $userName = null;
    private static $fullName = null;

    public static function getAccountID()
    {
        return self::$accountID;
    }

    public static function getRoleID()
    {
        return self::$roleID;
    }

    public static function getGroupID()
    {
        return self::$groupID;
    }

    public static function getAccountEnabled()
    {
        return self::$accountEnabled;
    }

    public static function getUserName()
    {
        return self::$userName;
    }

    public static function getFullName()
    {
        return self::$fullName;
    }

    public static $roleNames = [
        0 => "Global",
        1 => "Local Group Admin",
        2 => "Local Group User",
        3 => "Guest"
    ];

    public static function getRoleFriendlyName($role_id)
    {
        if (isset($role_id))
        {
            return self::$roleNames[$role_id];
        }
        return "";
    }

    public static function isSessionExpired()
    {
        if (isset($_SESSION["session_expired"]))
        {
            return $_SESSION["session_expired"];
        }
        return false;
    }

    public static function clearSessionExpired()
    {
        unset($_SESSION["session_expired"]);
    }

    public static function isSignedIn()
    {
        return (
            !is_null(self::$accountID) &&
            session_status() == PHP_SESSION_ACTIVE
        );
    }

    private static function updateState($record)
    {
        self::$accountID = $record["id"];
        self::$roleID = $record["role_id"];
        self::$groupID = $record["group_id"];
        self::$accountEnabled = (bool)$record["enabled"];
        self::$userName = $record["username"];
        self::$fullName = Utils::getFullName(
            $record["first_name"],
            $record["middle_name"],
            $record["last_name"],
            $record["suffix"],
            false
        );
    }

    public static function signIn(string $email, string $password)
    {
        if (self::isSignedIn())
        {
            return false;
        }
        
        $record = DBM::$com["ACC"]->readCredentials($email, $password);
        if (is_int($record))
        {
            return $record;
        }

        self::updateState($record);
        $session_created = DBM::$com["SESS"]->create(self::$sessionID, self::$accountID);

        return $session_created;
    }

    public static function signInFromSID()
    {
        $session = DBM::$com["SESS"]->readFromSID(self::$sessionID);
        $exists = !is_bool($session);

        if ($exists)
        {
            // Check if the session is expired
            $session_expiry = $session["expiry"];
            if (time() >= strtotime($session_expiry))
            {
                // Delete session from database and mark as expired
                DBM::$com["SESS"]->delete($session["session_id"]);
                session_destroy();
                session_start();
                session_regenerate_id();
                $_SESSION["session_expired"] = true;
                return false;
            }

            $account_id = $session["account_id"];
            $record = DBM::$com["ACC"]->readId($account_id);

            self::updateState($record);
            return true;
        }

        return false;
    }

    public static function signOut($allSessions = false, $user_id = null)
    {
        if (!self::isSignedIn() && empty($user_id))
        {
            return false;
        }

        $session_removed = false;

        if ($allSessions)
        {
            $target_id = empty($user_id) ? self::$accountID : $user_id;
            $session_removed = DBM::$com["SESS"]->deleteFromAccount($target_id);
        }
        else
        {
            $session_removed = DBM::$com["SESS"]->delete(self::$sessionID);
        }

        if (
            empty($user_id) ||
            (
                $allSessions &&
                $user_id == self::$accountID
            )
        ) {
            self::$accountID = null;
            self::$roleID = null;
            self::$groupID = null;
            self::$accountEnabled = null;
            self::$userName = null;
            self::$fullName = null;
        }

        return $session_removed;
    }

    public static function initialize()
    {
        session_name("auth_cogent_sessid");
        session_set_cookie_params(604800); 
        session_start();
        self::$sessionID = session_id();

        self::signInFromSID();
    }
}

Auth::initialize();
