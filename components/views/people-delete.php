<?php

class DeletePersonView extends DashboardView
{
    const SLUG = ["admin/people/delete"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBVAX", "DBTSR"]);
        $document = parent::getDocument();

        if (
            !empty($_POST) &&
            isset($_POST["holder0"]) &&
            isset($_POST["action"])
        )
        {
            $action = $_POST["action"];
            switch ($action)
            {
                default:
                case "YES":
                    break;
                case "NO":
                    Utils::redirect("admin/people");
                    return;
            }

            $patient_id = $_POST["holder0"];
            $result1 = DBM::$com["PAT"]->delete(
                $patient_id
            );
            $result2 = DBM::$com["VAXR"]->deleteFromPatientId(
                $patient_id
            );
            $result3 = DBM::$com["TSTR"]->deleteFromPatientId(
                $patient_id
            );

            if ($result1 && $result2 && $result3)
            {
                Utils::redirect("admin/people");
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
        $document->getDataByRef()["PAGE_NAME"] = "Delete Person";
        $document->getDataByRef()["PAGE_MARKER"] = "people-delete";

        $child = new Template("generic-delete");

        $child->setData([
            "HOLDER_0"     => $patient["id"],
            "HOLDER_1"     => $patient["reference_code"],
            "ITEM_DETAILS" => trim(sprintf(
                                  "records belonging to %s %s %s %s",
                                  $patient["first_name"],
                                  $patient["middle_name"],
                                  $patient["last_name"],
                                  $patient["suffix"]
                              )),
        ]);

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new DeletePersonView());
