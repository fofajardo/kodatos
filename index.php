<?php

require_once "./components/framework.php";

$template_file = Framework::getTemplate("home");
$document = new Template($template_file);

$page_info = [];
$page_info["TPL_HEADER"] = Framework::getTemplate("_header");

$document->setData($page_info);

echo $document->output();

?>
