<?php

class AddHCWView extends DashboardView
{
    const SLUG = ["admin/healthcare-workers/add"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBWOR", "DBSIT", "DBLOC"]);

        if (!empty($_POST))
        {
            $hcw_records = DBM::$com["HCW"];
            $result = $hcw_records->create(
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
                        Utils::redirect("admin/healthcare-workers");
                        return;
                    case "G_NEW":
                        Utils::redirect("admin/healthcare-workers/add");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_1"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add Healthcare Worker Record";
        $document->getDataByRef()["PAGE_MARKER"] = "hcw-add";

        $child = new Template("hcw-add_edit");
        $child->setData([
            "NAME_LAST"   => "",
            "NAME_FIRST"  => "",
            "NAME_MID"    => "",
            "NAME_SUFF"   => "",
            "PERSON_ID"   => "",
        ]);

/*
        $db_com = DBM::$com["SITES"]->readFilter(true);
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
            $child->getDataByRef()["TEST_SITE_INSERT"] = $insert0;
        }
*/

        $document->attach($child);
        return $document;
    }
}

VWM::register(new AddHCWView());
