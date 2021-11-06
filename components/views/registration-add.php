<?php

class AddRegistrationView extends DashboardView
{
    const SLUG = ["admin/registration/add"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBLOC"]);

        $document = parent::getDocument();

        if (!empty($_POST))
        {
            $patients = DBM::$com["PAT"];
            $result = $patients->create(
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
                $_POST["location-street"]
            );

            if ($result[0])
            {
                $refcode = $result[1];
                $action = $_POST["action"];
                switch ($action)
                {
                    case "G_RET":
                        Utils::redirect("/admin/dashboard");
                        return;
                    case "G_VAX":
                        Utils::redirect("/admin/vaccinations/add?rfc=$refcode");
                        return;
                    case "G_TST":
                        Utils::redirect("/admin/test-results/add?rfc=$refcode");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        $document->getDataByRef()["RGA_0"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add Registration";

        $child = new Template("registration-add");
        $locations = DBM::$com["LOC"]->read();

        foreach ($locations as $location)
        {
            $attributes = [
                "value" => $location["id"],
            ];
            $child->appendElement("option", $attributes, $location["name"]);
        }

        $child->setData(array_fill_keys([
            "NAME_LAST",
            "NAME_FIRST",
            "NAME_MID",
            "NAME_SUFF",
            "BIO_GENDER_0",
            "BIO_GENDER_1",
            "BIO_BIRTHDATE",
            "BIO_CIVILSTATUS_0",
            "BIO_CIVILSTATUS_1",
            "BIO_CIVILSTATUS_2",
            "BIO_CIVILSTATUS_3",
            "BIO_CIVILSTATUS_4",
            "CON_NUMBER",
            "CON_EMAIL",
            "LOC_STREET",
        ], ""));

        $document->attach($child);
        return $document;
    }
}

VWM::register(new AddRegistrationView());
