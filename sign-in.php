<?php

require_once "./components/framework.php";

if (Auth::isSignedIn())
{
    Utils::redirect("dashboard");
    exit;
}

$document = new Template("sign-in");

if (isset($_POST["email"]) && isset($_POST["password"]))
{
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $result = Auth::signIn($email, $password);
    if (!empty($result) && Auth::isSignedIn())
    {
        Utils::redirect("dashboard");
    }
    else
    {
        $status_text = "";
        $status_color = "yellow";
        switch ($result)
        {
            case 1:
                $status_text = "The credentials you've entered does not match any account.";
                break;
            case 2:
                $status_text = "The password you've entered is incorrect.";
                $status_color = "red";
                break;
            default:
                $status_text = "Unknown error code " . $result;
                break;
        }
        $document->appendElement(
            "div",
            $status_text,
            [
                "class" => "status-box $status_color mb1",
            ],
        );
    }
}

// var_dump(Auth::isSessionExpired());

echo $document->output();

?>
