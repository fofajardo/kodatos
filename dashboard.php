<?php

require_once "./components/framework.php";

if (!Auth::isSignedIn())
{
    Utils::redirect("sign-in");
}

$header_tpl = new Template("_header_li");
$header_tpl->setData([
    "USER_NAME" => strtoupper(Auth::getUserName()),
]);

$document = new Template("dashboard");
$document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

echo $document->output();
