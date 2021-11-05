<?php

class NotFoundView implements View
{
    const SLUG = "notfound";
    
    public function getDocument()
    {
        $header_tpl = new Template("_header");
        $document = new Template("notfound");
        $document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

        return $document;
    }
}

VWM::register(new NotFoundView());
