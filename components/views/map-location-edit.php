<?php

class EditLocationView extends DashboardView
{
    const SLUG = ["admin/locations/edit"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBLOC"]);

        if (!empty($_POST))
        {
            $location_records = DBM::$com["LOC"];
            $result = $location_records->update(
                $_POST["location-name"],
                // $_POST["location-group"],
                $_POST["location-id"]
            );

            if ($result)
            {
                $action = $_POST["action"];
                switch ($action)
                {
                    case "G_RET":
                        Utils::redirect("admin/locations");
                        return;
                    case "G_NEW":
                        Utils::redirect("admin/locations/add");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/locations");
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_4"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Edit Location";
        $document->getDataByRef()["PAGE_MARKER"] = "map-location-edit";
        $document->getDataByRef()["PAGE_LANDING"] = "locations";
        $document->getDataByRef()["NEW_VERB"] = "Location";

        $record = DBM::$com["LOC"]->readId($_GET["id"]);

        $child = new Template("map-location-add_edit");
        $child->setData([
            "LOCATION_ID"       => $record["id"],
            "LOCATION_NAME"     => $record["name"],
        ]);

/*
        $db_com = DBM::$com["LOC"]->read();
        if (!is_bool($db_com))
        {
            $insert0 = "";
            foreach ($db_com as $option)
            {
                $attributes = [
                    "value" => $option["id"],
                ];
                if ($option["id"] == $record["location_id"])
                {
                    $attributes["selected"] = "selected";
                }
                $insert0 .= Template::createElement("option", $attributes, $option["name"]);
            }
            $child->getDataByRef()["LOCATION_INSERT"] = $insert0;
        }
*/

        $document->attach($child);
        return $document;
    }
}

VWM::register(new EditLocationView());
