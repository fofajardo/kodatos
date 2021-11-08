<?php

class EditHCWView extends DashboardView
{
    const SLUG = ["admin/healthcare-workers/edit"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBWOR", "DBSIT", "DBLOC"]);

        if (!empty($_POST))
        {
            $hcw_records = DBM::$com["HCW"];
            $result = $hcw_records->update(
                $_POST["name-first"],
                $_POST["name-middle"],
                $_POST["name-last"],
                $_POST["name-suffix"],
                $_POST["person-id"]
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

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/healthcare-workers");
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_1"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Edit Healthcare Worker Record";
        $document->getDataByRef()["PAGE_MARKER"] = "hcw-edit";

        $record = DBM::$com["HCW"]->readId($_GET["id"]);

        $child = new Template("hcw-add_edit");
        $child->setData([
            "NAME_LAST"   => $record["last_name"],
            "NAME_FIRST"  => $record["first_name"],
            "NAME_MID"    => $record["middle_name"],
            "NAME_SUFF"   => $record["suffix"],
            "PERSON_ID"   => $record["id"],
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
                if ($option["id"] == $record["test_site_id"])
                {
                    $attributes["selected"] = "selected";
                }
                $insert0 .= Template::createElement("option", $attributes, $option["name"]);
            }
            $child->getDataByRef()["TEST_SITE_INSERT"] = $insert0;
        }
*/

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new EditHCWView());
