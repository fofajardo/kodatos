<?php

class EnterCodeView extends BaseView
{
    const SLUG = "enter-code";
    
    public function getDocument()
    {
        $document = parent::getDocument();
        $this->parameters["PAGE_NAME"] = "Enter Reference Code";

        $tpl = new Template("_enter-code");
        $document->attach($tpl);

        return $document;
    }
}

VWM::register(new EnterCodeView());
