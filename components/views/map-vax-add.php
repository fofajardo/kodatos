<?php

class AddMapVaxView extends DashboardView
{
    const SLUG = ["admin/vaccination-sites/add"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBSIT", "DBLOC"]);

        if (!empty($_POST))
        {
            $site_records = DBM::$com["SITES"];
            $result = $site_records->create(
                $_POST["site-name"],
                $_POST["site-location"],
                $_POST["site-is-lab"]
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

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_2"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add Vaccination Site";
        $document->getDataByRef()["PAGE_MARKER"] = "map-vax-add";
        $document->getDataByRef()["PAGE_LANDING"] = "vaccination-sites";
        $document->getDataByRef()["NEW_VERB"] = "Vaccination Site";

        $child = new Template("map-vax-add_edit");
        $child->setData([
            "SITE_NAME"     => "",
            "SITE_ID"       => "",
            "SITE_IS_LAB"   => 0,
        ]);

        $db_com = DBM::$com["LOC"]->read();
        $insert0 = "";
        if (is_bool($db_com))
        {
            $insert0 .= Template::createElement(
                "option",
                [
                    "disabled" => "disabled"
                ],
                "You must add a location first using the Dashboard, under Mapping > Locations."
            );
        }
        else
        {
            foreach ($db_com as $option)
            {
                $attributes = [
                    "value" => $option["id"],
                ];
                $insert0 .= Template::createElement("option", $attributes, $option["name"]);
            }
        }
        $child->getDataByRef()["LOCATION_INSERT"] = $insert0;

        $document->attach($child);
        return $document;
    }
}

VWM::register(new AddMapVaxView());
