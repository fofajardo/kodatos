<?php

require_once "./components/framework.php";

$header_tpl = new Template("_header");
$document = new Template("sign-in");
$document->getDataByRef()["TPL_HEADER"] = $header_tpl->output();

echo $document->output();

?>
