<?php

require_once "./components/framework.php";

$all_sessions = isset($_GET["all"]);

if (Auth::isSignedIn())
{
    Auth::signOut($all_sessions);
}

Utils::redirect("");
