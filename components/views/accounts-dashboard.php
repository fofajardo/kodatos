<?php

class AccountsDashboardView extends DashboardView
{
    const SLUG = ["admin/accounts"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBACC"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_7"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Accounts";
        $document->getDataByRef()["PAGE_LANDING"] = "accounts";
        $document->getDataByRef()["NEW_VERB"] = "Account";

        $child = new Template("generic-3col-dashboard");
        $users = DBM::$com["ACC"]->read();
        
        if (is_bool($users))
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box mt1"
                ],
                "There are no accounts in this platform. You may add a new account by clicking Add Account."
            );
        }
        else
        {
            $user_count = count($users);
            for ($i = 0; $i < $user_count; $i++)
            {
                $record = $users[$i];
                $cd_ref = $record["id"];

                $name = Utils::getFullName(
                    $record["first_name"],
                    $record["middle_name"],
                    $record["last_name"],
                    $record["suffix"]
                );
                $username = $record["username"];

                $role_id = $record["role_id"];
                $role_color = "";
                $role_text = Auth::$roleNames[$role_id];
 
                switch ($role_id)
                {
                    case 0:
                        $role_color = "red";
                    case 1:
                        $role_color = "yellow";
                    case 2:
                        $role_color = "green";
                    case 3:
                        break;
                }

                $en_color = "";
                $en_icon = "";
                $en_text = "";
                $account_enabled = (bool)$record["enabled"];
                if ($account_enabled)
                {
                    $en_color = "green";
                    $en_icon = "checkbox-marked-circle";
                    $en_text = "Enabled";
                }
                else
                {
                    $en_color = "red";
                    $en_icon = "alert-circle";
                    $en_text = "Disabled";
                }

                $row = <<<EOD
    <div class="row">
        <div class="cell hr left-col">
            <span>$name</span>
            <span class="username">($username)</span>
        </div>
        <div class="cell box">
            <div class="status-box $en_color">
                <span class="iconify" data-icon="mdi-$en_icon"></span>
                <span>$en_text</span>
            </div>
            <div class="status-box $role_color ml1">
                <span>$role_text</span>
            </div>
        </div>
        <div class="cell box">
            <a class="action-button" href="/admin/accounts/edit?id=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-pencil"></span>
                    Edit
                </div>
            </a>
            <a class="action-button" href="/admin/accounts/delete?id=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-trash-can"></span>
                    Delete
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

VWM::register(new AccountsDashboardView());
