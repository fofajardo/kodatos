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
        $this->addHeaderTarget(
            "menu",
            "mdi-menu",
            "navigation-col",
            "active"
        );
        $this->addHeaderMenu(
            "sign-out",
            "Sign Out",
            "mdi-logout",
            "sign-out"
        );
        $this->addParameters([
            "PAGE_NAME" => "Dashboard",
            "PAGE_MARKER" => "dashboard",
            "PAGE_HEADER_STATIC" => "true",
            "DASHBOARD_RECORDS_VISIBLE" => "",
            "DASHBOARD_MAPPING_VISIBLE" => (Auth::getRoleID() > 2) ? "hidden" : "",
            "DASHBOARD_ADMIN_VISIBLE"   => (Auth::getRoleID() > 1) ? "hidden" : "",
        ]);

        $this->mainTpl = new Template("_dashboard");
        $document->attach($this->mainTpl);

        return $document;
    }
}
