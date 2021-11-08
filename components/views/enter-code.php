<?php

class EnterCodeView implements View
{
    const SLUG = "enter-code";
    
    public function getDocument()
    {
        $header_tpl = new Template("_header");
        $document = new Template("_enter-code");
        $document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

        return $document;
    }
}

VWM::register(new EnterCodeView());
