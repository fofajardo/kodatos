<?php

require_once "../components/framework.php";

$document = new Template("sign-in");

$page_info = [];
$page_info["TPL_HEADER"] = Utils::getTemplate("_header");

$document->setData($page_info);

echo $document->output();

if (isset($_POST["email"]) && isset($_POST["password"]))
{
    $result = Auth::signIn($_POST["email"], $_POST["password"]);
    var_dump($result);
}

var_dump($_POST);
var_dump(Auth::isSignedIn());
var_dump(Auth::isSessionExpired());

?>
