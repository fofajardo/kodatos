<?php

class SignOutView implements View
{
    const SLUG = "sign-out";
    
    public function output()
    {
        $all_sessions = isset($_GET["all"]);

        if (Auth::isSignedIn())
        {
            Auth::signOut($all_sessions);
        }

        Utils::redirect("");
    }
}

VWM::register(new SignOutView());
