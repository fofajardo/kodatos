<?php

require_once "./components/framework.php";

$document = new Template("enter-code");

$page_info = [];
$page_info["TPL_HEADER"] = Utils::getTemplate("_header");

$document->setData($page_info);

echo $document->output();

?>
