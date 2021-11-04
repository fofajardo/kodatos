<?php

require_once "./components/framework.php";

if (!Auth::isSignedIn())
{
    Utils::redirect("sign-in");
}

echo '<a href="/sign-out">Sign out</a>';
