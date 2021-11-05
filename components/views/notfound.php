<?php

class NotFoundView implements View
{
    const SLUG = "notfound";
    
    public function output()
    {
        $header_tpl = new Template("_header");
        $document = new Template("notfound");
        $document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

        echo $document->output();
    }
}

VWM::register(new NotFoundView());
