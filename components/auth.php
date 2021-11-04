<?php

/* @FILE: auth.php
   © Francis Dominic Fajardo - All Rights Reserved
   Unauthorized copying of this file, via any medium is strictly prohibited
   Proprietary and confidential
*/

class Auth
{
    public static function initialize()
    {
        session_name("auth_kodatos_sessid");
        session_start();
    }
}

Auth::initialize();
