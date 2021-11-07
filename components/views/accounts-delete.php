<?php

class DeleteAccountsView extends DashboardView
{
    const SLUG = ["admin/accounts/delete"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBACC"]);

        if (
            !empty($_POST) &&
            isset($_POST["holder0"]) &&
            isset($_POST["action"])
        )
        {
            $action = $_POST["action"];
            switch ($action)
            {
                default:
                case "YES":
                    break;
                case "NO":
                    Utils::redirect("admin/accounts");
                    return;
            }

            $record_id = $_POST["holder0"];
            $result1 = DBM::$com["ACC"]->delete($record_id);
            if ($result1)
            {
                Auth::signOut(true, $record_id);
                if ($record_id == Auth::getAccountID())
                {
                    Utils::redirect("sign-in");
                }

                Utils::redirect("admin/accounts");
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/accounts");
        }

        $document = parent::getDocument();
        $record = DBM::$com["ACC"]->readId($_GET["id"]);

        if (is_bool($record))
        {
            VWM::outputNotFound();
        }

        $document->getDataByRef()["RGA_7"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Delete Account";
        $document->getDataByRef()["PAGE_MARKER"] = "accounts-delete";

        $child = new Template("generic-delete");

        $child->setData([
            "HOLDER_0"     => $record["id"],
            "ITEM_DETAILS" => sprintf(
                                  "user account of \"%s\"",
                                  Utils::getFullName(
                                      $record["first_name"],
                                      $record["middle_name"],
                                      $record["last_name"],
                                      $record["suffix"]
                                  )
                              ),
        ]);

        $document->attach($child);
        return $document;
    }
}

VWM::register(new DeleteAccountsView());
