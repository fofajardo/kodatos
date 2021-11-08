<?php

class NotFoundView extends BaseView
{
    const SLUG = "notfound";
    
    public function getDocument()
    {
        $document = parent::getDocument();
        $this->parameters["PAGE_NAME"] = "Page Not Found";

        $tpl = new Template("_notfound");
        $document->attach($tpl);

        return $document;
    }
}

VWM::register(new NotFoundView());
