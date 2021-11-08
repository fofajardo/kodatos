<?php

class HomeView extends BaseView
{
    const SLUG = ["", "home"];

    public function getDocument()
    {
        $document = parent::getDocument();
        $this->addScript(Framework::$dir["S_AST"] . "/qrcode-scan.min.js");

        $tpl = new Template("_home");
        $document->attach($tpl);

        return $document;
    }
}

VWM::register(new HomeView());
