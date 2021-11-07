<?php

class EditAccountsView extends DashboardView
{
    const SLUG = ["admin/accounts/edit"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBACC"]);

        if (!empty($_POST))
        {
            $accounts = DBM::$com["ACC"];
            $result = $accounts->update(
                $_POST["user-name"],
                $_POST["user-email"],
                $_POST["user-password"],
                $_POST["user-role"],
                0, // TODO: Group ID not yet implemented
                $_POST["user-enabled"],
                $_POST["name-first"],
                $_POST["name-middle"],
                $_POST["name-last"],
                $_POST["name-suffix"],
                $_POST["user-id"]
            );

            if ($result)
            {
                $action = $_POST["action"];
                switch ($action)
                {
                    case "G_RET":
                        Utils::redirect("admin/accounts");
                        return;
                    case "G_NEW":
                        Utils::redirect("admin/accounts/add");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/accounts");
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_7"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Edit Account";
        $document->getDataByRef()["PAGE_MARKER"] = "accounts-edit";

        $record = DBM::$com["ACC"]->readId($_GET["id"]);

        $selected_role  = "USER_ROLE_" . $record["role_id"];
        $selected_state = "USER_ENABLED_" . $record["enabled"];

        $child = new Template("accounts-add_edit");
        $child->setData([
            "NAME_LAST"       => $record["last_name"],
            "NAME_FIRST"      => $record["first_name"],
            "NAME_MID"        => $record["middle_name"],
            "NAME_SUFF"       => $record["suffix"],
            "USER_ID"         => $record["id"],
            "USER_NAME"       => $record["username"],
            "USER_EMAIL"      => $record["email"],
            "$selected_role"  => "selected",
            "$selected_state" => "selected",
            "USER_PW_PL"      => "Already set, type here to change",
            "USER_PW_REQ"     => "",
        ]);

        $document->attach($child);
        return $document;
    }
}

VWM::register(new EditAccountsView());
