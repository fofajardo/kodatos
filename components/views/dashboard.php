<?php

class DashboardView implements View
{
    const SLUG = ["dashboard", "admin/dashboard"];
    
    public function getDocument()
    {
        if (!Auth::isSignedIn())
        {
            Utils::redirect("sign-in");
        }

        $header_tpl = new Template("_header_li");
        $header_tpl->setData([
            "USER_NAME" => strtoupper(Auth::getUserName()),
        ]);

        $document = new Template("dashboard");
        $document->setData([
            "TPL_HEADER" => $header_tpl->output(),
            "PAGE_NAME" => "Dashboard",
        ]);

        return $document;
    }
}

VWM::register(new DashboardView());
