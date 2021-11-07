<?php

class AddLocationView extends DashboardView
{
    const SLUG = ["admin/locations/add"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBLOC"]);

        if (!empty($_POST))
        {
            $location_records = DBM::$com["LOC"];
            $result = $location_records->create(
                $_POST["location-name"]
                // $_POST["location-group"]
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

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_4"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add Location";
        $document->getDataByRef()["PAGE_MARKER"] = "map-location-add";
        $document->getDataByRef()["PAGE_LANDING"] = "locations";
        $document->getDataByRef()["NEW_VERB"] = "Location";

        $child = new Template("map-location-add_edit");
        $child->setData([
            "LOCATION_ID"       => "",
            "LOCATION_NAME"     => "",
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
                $insert0 .= Template::createElement("option", $attributes, $option["name"]);
            }
            $child->getDataByRef()["LGU_GROUP_INSERT"] = $insert0;
        }
*/

        $document->attach($child);
        return $document;
    }
}

VWM::register(new AddLocationView());
