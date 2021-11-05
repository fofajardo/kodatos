<?php

class SignOutView implements View
{
    const SLUG = "sign-out";
    
    public function getDocument()
    {
        $document = new Template("", true);
        $all_sessions = isset($_GET["all"]);

        if (Auth::isSignedIn())
        {
            Auth::signOut($all_sessions);
        }

        Utils::redirect("");
        return $document;
    }
}

VWM::register(new SignOutView());
