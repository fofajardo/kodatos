<?php

class EnterCodeView implements View
{
    const SLUG = "enter-code";
    
    public function output()
    {
        $header_tpl = new Template("_header");
        $document = new Template("enter-code");
        $document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

        echo $document->output();
    }
}

VWM::register(new EnterCodeView());
