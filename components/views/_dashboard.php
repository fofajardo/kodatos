<?php

class DashboardView implements View
{
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
            "PAGE_MARKER" => "dashboard",
        ]);

        return $document;
    }
}
