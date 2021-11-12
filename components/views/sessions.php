<?php

class SessionsView extends DashboardView
{
    const SLUG = ["admin/sessions"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBACC", "DBSES"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_7"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Sessions";
        $document->getDataByRef()["PAGE_LANDING"] = "sessions";
        $document->getDataByRef()["NEW_VERB"] = "Session";

        $child = new Template("generic-3col-dashboard");

        if (!empty($_GET))
        {
            $hasAction = isset($_GET["action"]);
            $hasId = isset($_GET["id"]);
            $retVal = null;
            if ($hasAction && $hasId)
            {
                $_SESSION["com-action"] = $_GET["action"];
                switch ($_GET["action"])
                {
                    case "delete":
                        $retVal = DBM::$com["SESS"]->delete($_GET["id"]);
                        break;
                    case "expire":
                        $retVal = DBM::$com["SESS"]->update(date("Y-m-d H:i:s", time() - 86400), $_GET["id"]);
                        break;
                }
            }
            if ($retVal)
            {
                $_SESSION["com-details"] = $_GET["id"];
            }
            Utils::redirect("admin/sessions");
        }

        if (isset($_SESSION["com-details"]))
        {
            $details = $_SESSION["com-details"];
            $action = $_SESSION["com-action"];
            switch ($action)
            {
                case "delete":
                    $action = "was deleted";
                    break;
                case "expire":
                    $action = "has been marked expired";
                    break;
            }
            $this->mainTpl->append("<div class=\"status-box\">Session `$details` $action.</div>");
            unset($_SESSION["com-details"]);
            unset($_SESSION["com-action"]);
        }

        $sessions = DBM::$com["SESS"]->read();
        
        if (is_bool($sessions))
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box mt1"
                ],
                "There are no sessions in this platform. You may add a new account by clicking Add Account."
            );
        }
        else
        {
            $sess_count = count($sessions);
            for ($i = 0; $i < $sess_count; $i++)
            {
                $record = $sessions[$i];
                $sess_id = $record["session_id"];
                $account = DBM::$com["ACC"]->readId($record["account_id"]);

                $name = Utils::getFullName(
                    $account["first_name"],
                    $account["middle_name"],
                    $account["last_name"],
                    $account["suffix"]
                );
                $username = $account["username"];
                
                $date_creation = $record["creation"];
                $date_expiry   = $record["expiry"];

                $row = <<<EOD
    <div class="row">
        <div class="cell left-col">
            <span>$name</span>
            <span class="username">($username)</span>
        </div>
        <div class="cell box">
            $date_creation
            <br/>
            $date_expiry
        </div>
        <div class="cell box">
            <a class="action-button" href="/admin/sessions?action=delete&id=$sess_id">
                <div>
                    <span class="iconify" data-icon="mdi-logout"></span>
                    Sign Out
                </div>
            </a>
            <a class="action-button" href="/admin/sessions?action=expire&id=$sess_id">
                <div>
                    <span class="iconify" data-icon="mdi-timer-off"></span>
                    Expire
                </div>
            </a>
        </div>
    </div>
EOD;
                $child->append($row);
            }
        }
        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new SessionsView());
