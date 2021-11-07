<?php

class AddAccountsView extends DashboardView
{
    const SLUG = ["admin/accounts/add"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBACC"]);

        if (!empty($_POST))
        {
            $accounts = DBM::$com["ACC"];
            $result = $accounts->create(
                $_POST["user-name"],
                $_POST["user-email"],
                $_POST["user-password"],
                $_POST["user-role"],
                0, // TODO: Group ID not yet implemented
                $_POST["user-enabled"],
                $_POST["name-first"],
                $_POST["name-middle"],
                $_POST["name-last"],
                $_POST["name-suffix"]
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

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_7"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add Account";
        $document->getDataByRef()["PAGE_MARKER"] = "accounts-add";

        $child = new Template("accounts-add_edit");
        $child->setData([
            "NAME_LAST"     => "",
            "NAME_FIRST"    => "",
            "NAME_MID"      => "",
            "NAME_SUFF"     => "",
            "USER_ROLE_0"   => "",
            "USER_ROLE_1"   => "",
            "USER_ROLE_2"   => "",
            "USER_ROLE_3"   => "",
            "USER_NAME"     => "",
            "USER_EMAIL"    => "",
            "USER_PW_PL"    => "Enter new password",
            "USER_PW_REQ"   => "required",
            "USER_ID"       => "",
            "USER_ENABLED_0"=> "",
            "USER_ENABLED_1"=> "selected",
        ]);

        $document->attach($child);
        return $document;
    }
}

VWM::register(new AddAccountsView());
