<?php

class ViewTestsView extends DashboardView
{
    const SLUG = ["admin/test-results/view"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBTSR", "DBTTY", "DBSIT"]);

        if (empty($_GET) || !isset($_GET["rfc"]))
        {
            Utils::redirect("admin/people");
        }

        $patient = DBM::$com["PAT"]->readCode($_GET["rfc"], "");

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0C"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "COVID-19 Test Results";
        $document->getDataByRef()["PAGE_MARKER"] = "tests-view";

        $child = new Template("tests-view");
        $child->setData([
            "PERSON_NAME" => Utils::getFullName(
                $patient["first_name"],
                $patient["middle_name"],
                $patient["last_name"],
                $patient["suffix"]
            ),
            "PERSON_RFC" => $patient["reference_code"],
        ]);

        $info = [];
        $info["testtype"]  = DBM::$com["TSTT"]->read();
        $info["vaxsites"]  = DBM::$com["SITES"]->read();

        $test_results = DBM::$com["TSTR"]->readFromPatientId($patient["id"]);
        if (!is_bool($test_results))
        {
            $test_results_count = count($test_results);
            for ($i = 0; $i < $test_results_count; $i++)
            {
                $record = $test_results[$i];
                $record_tpl = new Template("tests-view-child");

                $test_result = (bool)$record["test_result"] ? "Positive" : "Negative";
                $record_tpl->setData([
                    "TEST_NUM"    => Utils::getOrdinal($test_results_count - $i),
                    "TEST_ID"     => $record["id"],
                    "TEST_DATE"   => $record["test_date"],
                    "TEST_RESULT" => $test_result,
                    "TEST_TYPE"   => DBM::$com["TSTT"]->readId($record["test_type"])["name"],
                    "TEST_SITE"   => DBM::$com["SITES"]->readId($record["test_site_id"])["name"],
                ]);

                $child->attach($record_tpl);
            }
        }
        else
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box"
                ],
                "This person has no COVID-19 test results. You may add a new test result by clicking <em>Add Test Result</em>."
            );
        }

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new ViewTestsView());
