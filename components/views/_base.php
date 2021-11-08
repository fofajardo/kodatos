<?php

class BaseView implements View
{
    protected $parameters;
    protected $headerTpl;
    protected $headerMenus;
    protected $scripts;
    protected $hideHeader;

    protected function getAuthRequired()
    {
        return false;
    }

    protected function getRoleRestriction()
    {
        return 3;
    }

    protected function addParameters(array $params)
    {
        $this->parameters = array_merge(
            $this->parameters,
            $params
        );
    }

    protected function addHeaderMenu($id, $text, $icon, $landingPage)
    {
        $hide_span = empty($text) ? "hidden" : "";
        $menu = <<<EOD
<li>
    <a class="link" href="/$landingPage">
        <div>
            <span class="iconify" data-icon="$icon"></span>
            <span $hide_span>$text</span>
        </div>
    </a>
</li>
EOD;
        $this->headerMenus[$id] = $menu;
    }

    protected function removeHeaderMenu($id)
    {
        unset($this->headerMenus[$id]);
    }

    protected function clearHeader()
    {
        $this->headerMenus = [];
    }

    protected function addScript($url)
    {
        if (is_array($url))
        {
            foreach ($url as $script_url)
            {
                $this->addScript($script_url);
            }
            return;
        }

        $this->scripts[] = Template::createElement(
            "script",
            [
                "src" => "$url",
                "type" => "text/javascript",
            ],
            ""
        );
    }

    public function getDocument()
    {
        // Redirect if authentication is required and user is not signed in
        if ($this->getAuthRequired() && !Auth::isSignedIn())
        {
            Utils::redirect("sign-in");
        }
        // Redirect if the user's role is greater than the restriction
        if (Auth::getRoleID() > $this->getRoleRestriction())
        {
            Utils::redirect("no-access");
        }

        $this->headerTpl = new Template("_header");
        $this->headerMenus = [];
        $this->headerTpl->attachContent($this->headerMenus);

        $this->addHeaderMenu(
            0,
            "",
            "mdi-account-circle",
            "dashboard"
        );
        
        $this->parameters = [
            "PAGE_NAME"          => "",
            "PAGE_MARKER"        => "default",
            "PAGE_SCRIPT_INSERT" => "",
            "SI_USER_NAME"       => strtoupper(Auth::getUserName()),
        ];
        $this->scripts = [];

        $document = new Template("_base");
        $document->attachData($this->parameters);

        return $document;
    }

    public function output()
    {
        $document = $this->getDocument();
        
        $headerTpl = $this->hideHeader ? "" : $this->headerTpl->output();
        $this->parameters["TPL_HEADER"] = $headerTpl;

        $page_name = $this->parameters["PAGE_NAME"];
        if (empty($page_name))
        {
            $this->parameters["PAGE_TITLE"] = "KoDatos";
        }
        else
        {
            $this->parameters["PAGE_TITLE"] = "$page_name - KoDatos";
        }

        if (count($this->scripts) > 0)
        {
            $this->parameters["PAGE_SCRIPT_INSERT"] = implode(PHP_EOL, $this->scripts);
        }

        return $document->output();
    }
}
