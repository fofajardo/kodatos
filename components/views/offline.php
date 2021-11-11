<?php

class OfflineView extends BaseView
{
    const SLUG = "pwa/offline";
    
    public function getDocument()
    {
        $document = parent::getDocument();
        $this->parameters["PAGE_NAME"] = "Offline";

        $tpl = new Template("_offline");
        $document->attach($tpl);

        return $document;
    }
}

VWM::register(new OfflineView());
