<?php

class HomeView implements View
{
    const SLUG = ["", "home"];

    public function output()
    {
        $header_tpl = new Template("_header");
        $document = new Template("home");
        $document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

        echo $document->output();
    }
}

VWM::register(new HomeView());