<?php

class AddVaxView extends DashboardView
{
    const SLUG = ["admin/vaccinations/add"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBVAX", "DBWOR", "DBPRO", "DBSIT"]);

        if (empty($_GET) || !isset($_GET["rfc"]))
        {
            Utils::redirect("admin/people");
        }

        if (!empty($_POST))
        {
            $vax_records = DBM::$com["VAXR"];
            $result = $vax_records->create(
                $_POST["person-id"],
                $_POST["vax-product"],
                $_POST["vax-lotnum"],
                $_POST["vax-expiry"],
                $_POST["vax-date"],
                $_POST["vax-site"],
                $_POST["vax-hcw"]
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
        $document->getDataByRef()["RGA_0B"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add Vaccination Dose Record";
        $document->getDataByRef()["PAGE_MARKER"] = "vax-add";

        $patient = DBM::$com["PAT"]->readCode($_GET["rfc"], "");

        $child = new Template("vaccinations-add_edit");
        $child->setData([
            "ACTION_VERB" => "creating a new",
            "VAX_DATE"    => "",
            "VAX_LOTNUM"  => "",
            "VAX_EXPIRY"  => "",
            "PERSON_ID"   => $patient["id"],
            "PERSON_RFC"  => $_GET["rfc"],
            "PERSON_NAME" => Utils::getFullName(
                $patient["first_name"],
                $patient["middle_name"],
                $patient["last_name"],
                $patient["suffix"]
            ),
        ]);

        $db_com = DBM::$com["SITES"]->readFilter(false);
        $insert0 = "";
        if (is_bool($db_com))
        {
            $insert0 .= Template::createElement(
                "option",
                [
                    "disabled" => "disabled"
                ],
                "You must add a vaccination site first using the Dashboard, under Mapping > Vaccination Sites."
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
        $child->getDataByRef()["VAX_SITE_INSERT"] = $insert0;

        $db_com = DBM::$com["HCW"]->read();
        $insert1 = "";
        if (is_bool($db_com))
        {
            $insert1 .= Template::createElement(
                "option",
                [
                    "disabled" => "disabled"
                ],
                "You must add a healthcare worker first using the Dashboard, under Records > Healthcare Workers."
            );
        }
        else
        {
            foreach ($db_com as $option)
            {
                $attributes = [
                    "value" => $option["id"],
                ];
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
        }
        $child->getDataByRef()["VAX_HCW_INSERT"] = $insert1;

        $db_com = DBM::$com["PROD"]->read();
        $insert2 = "";
        if (is_bool($db_com))
        {
            $insert2 .= Template::createElement(
                "option",
                [
                    "disabled" => "disabled"
                ],
                "You must add a vaccine brand first using the Dashboard, under Inventory > Vaccines."
            );
        }
        else
        {
            foreach ($db_com as $option)
            {
                $attributes = [
                    "value" => $option["id"],
                ];
                $insert2 .= Template::createElement("option", $attributes, $option["vax_name"]);
            }
        }
        $child->getDataByRef()["VAX_PROD_INSERT"] = $insert2;
        
        $document->attach($child);
        return $document;
    }
}

VWM::register(new AddVaxView());
