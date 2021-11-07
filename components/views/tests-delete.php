<?php

class DeleteTestsView extends DashboardView
{
    const SLUG = ["admin/test-results/delete"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBTSR"]);
        $document = parent::getDocument();

        if (
            !empty($_POST) &&
            isset($_POST["holder0"]) &&
            isset($_POST["holder1"]) &&
            isset($_POST["action"])
        )
        {
            $refcode = $_POST["holder1"];
            $action = $_POST["action"];
            switch ($action)
            {
                default:
                case "YES":
                    break;
                case "NO":
                    Utils::redirect("admin/test-results/view?rfc=$refcode");
                    return;
            }

            $record_id = $_POST["holder0"];
            $result1 = DBM::$com["TSTR"]->delete($record_id);

            if ($result1)
            {
                Utils::redirect("admin/test-results/view?rfc=$refcode");
            }

            // TODO: implement error message view
        }

        if (empty($_GET) || !isset($_GET["id"]))
        {
            Utils::redirect("admin/people");
        }

        $record = DBM::$com["TSTR"]->readId($_GET["id"]);
        $patient = DBM::$com["PAT"]->readId($record["patient_id"]);

        if (is_bool($record) || is_bool($patient))
        {
            VWM::outputNotFound();
        }

        $document->getDataByRef()["RGA_0C"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Delete COVID-19 Test Result";
        $document->getDataByRef()["PAGE_MARKER"] = "tests-delete";

        $child = new Template("generic-delete");

        $child->setData([
            "HOLDER_0"     => $record["id"],
            "HOLDER_1"     => $patient["reference_code"],
            "ITEM_DETAILS" => trim(sprintf(
                                  "COVID-19 test result belonging to %s %s %s %s",
                                  $patient["first_name"],
                                  $patient["middle_name"],
                                  $patient["last_name"],
                                  $patient["suffix"]
                              )),
        ]);

        $document->attach($child);
        return $document;
    }
}

VWM::register(new DeleteTestsView());
