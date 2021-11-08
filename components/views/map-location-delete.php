<?php

class DeleteLocationView extends DashboardView
{
    const SLUG = ["admin/locations/delete"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBLOC"]);

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
                    Utils::redirect("admin/locations");
                    return;
            }

            $record_id = $_POST["holder0"];
            $result1 = DBM::$com["LOC"]->delete($record_id);

            if ($result1)
            {
                Utils::redirect("admin/locations");
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/locations");
        }

        $document = parent::getDocument();
        $record = DBM::$com["LOC"]->readId($_GET["id"]);

        if (is_bool($record))
        {
            VWM::outputNotFound();
        }

        $document->getDataByRef()["RGA_4"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Delete Location";
        $document->getDataByRef()["PAGE_MARKER"] = "map-location-delete";

        $child = new Template("generic-delete");

        $child->setData([
            "HOLDER_0"     => $record["id"],
            "ITEM_DETAILS" => sprintf(
                                  "location with the name \"%s\"",
                                  $record["name"]
                              ),
        ]);

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new DeleteLocationView());
