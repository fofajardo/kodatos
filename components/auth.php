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
    private static $locationID = null;
    private static $accountEnabled = null;
    private static $sessionID = null;
    private static $sessionExpired = false;

    public static function getAccountID()
    {
        return self::$accountID;
    }

    public static function getRoleID()
    {
        return self::$roleID;
    }

    public static function getLocationID()
    {
        return self::$locationID;
    }

    public static function getAccountEnabled()
    {
        return self::$accountEnabled;
    }

    public static function isSessionExpired()
    {
        return self::$sessionExpired;
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
        self::$locationID = $record["location_id"];
        self::$accountEnabled = (bool)$record["enabled"];
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
        var_dump($session_created);

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
                self::$sessionExpired = true;
                return false;
            }

            $account_id = $session["account_id"];
            $record = DBM::$com["ACC"]->readId($account_id);

            self::updateState($record);
            return true;
        }

        return false;
    }

    public static function signOut($allSessions = false)
    {
        if (!self::isSignedIn())
        {
            return false;
        }

        $session_removed = false;

        if ($allSessions)
        {
            $session_removed = DBM::$com["SESS"]->deleteFromAccount(self::$accountID);
        }
        else
        {
            $session_removed = DBM::$com["SESS"]->delete(self::$sessionID);
        }

        self::$accountID = null;
        self::$roleID = null;
        self::$locationID = null;
        self::$accountEnabled = null;

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
