<?php

class SignInView extends BaseView
{
    const SLUG = "sign-in";
    
    public function getDocument()
    {
        if (Auth::isSignedIn())
        {
            Utils::redirect("dashboard");
            exit;
        }

        $document = parent::getDocument();
        $this->hideHeader = true;
        $this->parameters["PAGE_NAME"] = "Sign In";

        $tpl = new Template("_sign-in");
        $document->attach($tpl);

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
                $tpl->appendElement(
                    "div",
                    [
                        "class" => "status-box $status_color mb1",
                    ],
                    $status_text
                );
            }
        }

        // var_dump(Auth::isSessionExpired());

        return $document;
    }
}

VWM::register(new SignInView());
