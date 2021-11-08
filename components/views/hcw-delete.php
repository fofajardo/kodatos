<?php

class DeleteHCWView extends DashboardView
{
    const SLUG = ["admin/healthcare-workers/delete"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBWOR"]);

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
                    Utils::redirect("admin/healthcare-workers");
                    return;
            }

            $record_id = $_POST["holder0"];
            $result1 = DBM::$com["HCW"]->delete($record_id);

            if ($result1)
            {
                Utils::redirect("admin/healthcare-workers");
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/healthcare-workers");
        }

        $document = parent::getDocument();
        $record = DBM::$com["HCW"]->readId($_GET["id"]);

        if (is_bool($record))
        {
            VWM::outputNotFound();
        }

        $document->getDataByRef()["RGA_1"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Delete Healthcare Worker Record";
        $document->getDataByRef()["PAGE_MARKER"] = "hcw-delete";

        $child = new Template("generic-delete");

        $child->setData([
            "HOLDER_0"     => $record["id"],
            "ITEM_DETAILS" => sprintf(
                                  "healthcare worker record of \"%s\"",
                                  Utils::getFullName(
                                      $record["first_name"],
                                      $record["middle_name"],
                                      $record["last_name"],
                                      $record["suffix"]
                                  )
                              ),
        ]);

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new DeleteHCWView());
