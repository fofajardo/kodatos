<?php

class EditMapVaxView extends DashboardView
{
    const SLUG = ["admin/vaccination-sites/edit"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBSIT", "DBLOC"]);

        if (!empty($_POST))
        {
            $site_records = DBM::$com["SITES"];
            $result = $site_records->update(
                $_POST["site-name"],
                $_POST["site-location"],
                $_POST["site-is-lab"],
                $_POST["site-id"]
            );

            if ($result)
            {
                $action = $_POST["action"];
                switch ($action)
                {
                    case "G_RET":
                        Utils::redirect("admin/vaccination-sites");
                        return;
                    case "G_NEW":
                        Utils::redirect("admin/vaccination-sites/add");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/vaccination-sites");
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_2"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Edit Vaccination Site";
        $document->getDataByRef()["PAGE_MARKER"] = "map-vax-edit";
        $document->getDataByRef()["PAGE_LANDING"] = "vaccination-sites";
        $document->getDataByRef()["NEW_VERB"] = "Vaccination Site";

        $record = DBM::$com["SITES"]->readId($_GET["id"]);

        $child = new Template("map-vax-add_edit");
        $child->setData([
            "SITE_NAME"     => $record["name"],
            "SITE_ID"       => $record["id"],
            "SITE_IS_LAB"   => $record["is_laboratory"],
        ]);

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

        $document->attach($child);
        return $document;
    }
}

VWM::register(new EditMapVaxView());
