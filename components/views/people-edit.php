<?php

class EditPersonView extends DashboardView
{
    const SLUG = ["admin/people/edit"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBLOC"]);
        $document = parent::getDocument();

        if (!empty($_POST))
        {
            $patients = DBM::$com["PAT"];
            $result = $patients->update(
                $_POST["name-first"],
                $_POST["name-last"],
                $_POST["name-middle"],
                $_POST["name-suffix"],
                $_POST["bio_gender"],
                $_POST["bio-birthdate"],
                $_POST["bio-civilstatus"],
                $_POST["contact-number"],
                $_POST["contact-email"],
                $_POST["location-general"],
                $_POST["location-street"],
                $_POST["person-id"]
            );

            if ($result)
            {
                $refcode = $_POST["person-rfc"];
                $action = $_POST["action"];
                switch ($action)
                {
                    case "G_RET":
                        Utils::redirect("admin/people");
                        return;
                    case "G_VAX":
                        Utils::redirect("admin/vaccinations/view?rfc=$refcode");
                        return;
                    case "G_TST":
                        Utils::redirect("admin/test-results/view?rfc=$refcode");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["rfc"]))
        {
            Utils::redirect("admin/people");
        }

        $patient = DBM::$com["PAT"]->readCode($_GET["rfc"], "");

        if (is_bool($patient))
        {
            VWM::outputNotFound();
        }

        $document->getDataByRef()["RGA_0"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Edit Person";
        $document->getDataByRef()["PAGE_MARKER"] = "people-edit";

        $child = new Template("people-add_edit");
        $locations = DBM::$com["LOC"]->read();

        if (is_bool($locations))
        {
            $child->appendElement(
                "option",
                [
                    "disabled" => "disabled"
                ],
                "You must add a location first using the Dashboard, under Mapping > Locations."
            );
        }
        else
        {
            foreach ($locations as $location)
            {
                $attributes = [
                    "value" => $location["id"],
                ];
                if ($location["id"] == $patient["location_id"])
                {
                    $attributes["selected"] = "selected";
                }
                $child->appendElement("option", $attributes, $location["name"]);
            }
        }

        $selected_gender = "BIO_GENDER_" . $patient["gender"];
        $selected_cstatus = "BIO_CIVILSTATUS_" . $patient["civil_status"];

        $child->setData([
            "PERSON_ID" => $patient["id"],
            "PERSON_RFC" => $patient["reference_code"],
            "NAME_LAST" => $patient["last_name"],
            "NAME_FIRST" => $patient["first_name"],
            "NAME_MID" => $patient["middle_name"],
            "NAME_SUFF" => $patient["suffix"],
            "$selected_gender" => "selected",
            "BIO_BIRTHDATE" => $patient["birthdate"],
            "$selected_cstatus" => "selected",
            "CON_NUMBER" => $patient["contact_number"],
            "CON_EMAIL" => $patient["email"],
            "LOC_STREET" => $patient["street_address"],
        ]);

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new EditPersonView());
