<?php

class EditVaxView extends DashboardView
{
    const SLUG = ["admin/vaccinations/edit"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBVAX", "DBWOR", "DBPRO", "DBSIT"]);

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/people");
        }

        if (!empty($_POST))
        {
            $vax_records = DBM::$com["VAXR"];
            $result = $vax_records->update(
                $_POST["person-id"],
                $_POST["vax-product"],
                $_POST["vax-lotnum"],
                $_POST["vax-expiry"],
                $_POST["vax-date"],
                $_POST["vax-site"],
                $_POST["vax-hcw"],
                $_POST["vax-id"]
            );

            if ($result)
            {
                $refcode = $_POST["person-rfc"];
                $action = $_POST["action"];
                switch ($action)
                {
                    case "G_RET":
                        Utils::redirect("admin/vaccinations/view?rfc=$refcode");
                        return;
                    case "G_NEW":
                        Utils::redirect("admin/vaccinations/add?rfc=$refcode");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Edit Vaccination Dose Record";
        $document->getDataByRef()["PAGE_MARKER"] = "vax-edit";

        $record = DBM::$com["VAXR"]->readId($_GET["id"]);
        $patient = DBM::$com["PAT"]->readId($record["patient_id"]);

        $child = new Template("vaccinations-add_edit");
        $child->setData([
            "ACTION_VERB" => "editing a",
            "VAX_ID"      => $record["id"],
            "VAX_DATE"    => $record["vax_date"],
            "VAX_LOTNUM"  => $record["vax_lotnum"],
            "VAX_EXPIRY"  => $record["vax_expiry"],
            "PERSON_ID"   => $patient["id"],
            "PERSON_RFC"  => $patient["reference_code"],
            "PERSON_NAME" => Utils::getFullName(
                $patient["first_name"],
                $patient["middle_name"],
                $patient["last_name"],
                $patient["suffix"]
            ),
        ]);

        $db_com = DBM::$com["SITES"]->readFilter(false);
        $insert0 = "";
        foreach ($db_com as $option)
        {
            $attributes = [
                "value" => $option["id"],
            ];
            if ($option["id"] == $record["vax_site_id"])
            {
                $attributes["selected"] = "selected";
            }
            $insert0 .= Template::createElement("option", $attributes, $option["name"]);
        }
        $child->getDataByRef()["VAX_SITE_INSERT"] = $insert0;

        $db_com = DBM::$com["HCW"]->read();
        $insert1 = "";
        foreach ($db_com as $option)
        {
            $attributes = [
                "value" => $option["id"],
            ];
            if ($option["id"] == $record["vax_hcw_id"])
            {
                $attributes["selected"] = "selected";
            }
            $insert1 .= Template::createElement(
                "option",
                $attributes,
                Utils::getFullName(
                    $option["first_name"],
                    $option["middle_name"],
                    $option["last_name"],
                    $option["suffix"]
                )
            );
        }
        $child->getDataByRef()["VAX_HCW_INSERT"] = $insert1;

        $db_com = DBM::$com["PROD"]->read();
        $insert2 = "";
        foreach ($db_com as $option)
        {
            $attributes = [
                "value" => $option["id"],
            ];
            if ($option["id"] == $record["vax_product_id"])
            {
                $attributes["selected"] = "selected";
            }
            $insert2 .= Template::createElement("option", $attributes, $option["vax_name"]);
        }
        $child->getDataByRef()["VAX_PROD_INSERT"] = $insert2;
        
        $document->attach($child);
        return $document;
    }
}

VWM::register(new EditVaxView());
