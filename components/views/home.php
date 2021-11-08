<?php

class HomeView implements View
{
    const SLUG = ["", "home"];

    public function getDocument()
    {
        $header_tpl = new Template("_header");
        $document = new Template("_home");
        $document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

        return $document;
    }
}

VWM::register(new HomeView());
