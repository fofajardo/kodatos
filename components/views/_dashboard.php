<?php

class DashboardView extends BaseView
{
    protected $mainTpl;

    protected function getAuthRequired()
    {
        return true;
    }

    public function getDocument()
    {
        $document = parent::getDocument();

        $this->clearHeader();
/*
        $this->addHeaderMenu(
            "dashboard",
            strtoupper(Auth::getUserName()),
            "mdi-account-circle",
            "dashboard"
        );
*/
        $this->addHeaderMenuTarget(
            "menu",
            "mdi-menu",
            "navigation-col",
            "active"
        );
        $this->addHeaderMenu(
            "sign-out",
            "Sign out",
            "mdi-logout",
            "sign-out"
        );
        $this->addParameters([
            "PAGE_NAME" => "Dashboard",
            "PAGE_MARKER" => "dashboard",
        ]);

        $this->mainTpl = new Template("_dashboard");
        $document->attach($this->mainTpl);

        return $document;
    }
}
