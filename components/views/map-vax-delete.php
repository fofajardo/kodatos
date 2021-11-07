<?php

class DeleteMapVaxView extends DashboardView
{
    const SLUG = ["admin/vaccination-sites/delete"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBSIT"]);

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
                    Utils::redirect("admin/vaccination-sites");
                    return;
            }

            $record_id = $_POST["holder0"];
            $result1 = DBM::$com["SITES"]->delete($record_id);

            if ($result1)
            {
                Utils::redirect("admin/vaccination-sites");
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/vaccination-sites");
        }

        $document = parent::getDocument();
        $record = DBM::$com["SITES"]->readId($_GET["id"]);

        if (is_bool($record))
        {
            VWM::outputNotFound();
        }

        $document->getDataByRef()["RGA_2"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Delete Vaccination Site";
        $document->getDataByRef()["PAGE_MARKER"] = "map-vax-delete";

        $child = new Template("generic-delete");

        $child->setData([
            "HOLDER_0"     => $record["id"],
            "ITEM_DETAILS" => sprintf(
                                  "vaccination site with the name \"%s\"",
                                  $record["name"]
                              ),
        ]);

        $document->attach($child);
        return $document;
    }
}

VWM::register(new DeleteMapVaxView());
