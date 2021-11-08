<?php

class AddMapLabView extends DashboardView
{
    const SLUG = ["admin/laboratories/add"];

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
                        Utils::redirect("admin/laboratories");
                        return;
                    case "G_NEW":
                        Utils::redirect("admin/laboratories/add");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_3"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add Laboratory";
        $document->getDataByRef()["PAGE_MARKER"] = "map-lab-add";
        $document->getDataByRef()["PAGE_LANDING"] = "laboratories";
        $document->getDataByRef()["NEW_VERB"] = "Laboratory";

        $child = new Template("map-vax-add_edit");
        $child->setData([
            "SITE_NAME"     => "",
            "SITE_ID"       => "",
            "SITE_IS_LAB"   => 1,
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

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new AddMapLabView());
