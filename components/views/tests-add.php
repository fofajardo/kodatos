<?php

class AddTestsView extends DashboardView
{
    const SLUG = ["admin/test-results/add"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBTSR", "DBTTY", "DBSIT"]);

        if (empty($_GET) || !isset($_GET["rfc"]))
        {
            Utils::redirect("admin/people");
        }

        if (!empty($_POST))
        {
            $test_results = DBM::$com["TSTR"];
            $result = $test_results->create(
                $_POST["person-id"],
                $_POST["test-date"],
                $_POST["test-site"],
                $_POST["test-type"],
                $_POST["test-result"]
            );

            if ($result)
            {
                $refcode = $_POST["person-rfc"];
                $action = $_POST["action"];
                switch ($action)
                {
                    case "G_RET":
                        Utils::redirect("admin/test-results/view?rfc=$refcode");
                        return;
                    case "G_NEW":
                        Utils::redirect("admin/test-results/add?rfc=$refcode");
                        return;
                    default:
                        break;
                }
            }

            // TODO: implement error message view
        }

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Add COVID-19 Test Result";
        $document->getDataByRef()["PAGE_MARKER"] = "tests-add";

        $patient = DBM::$com["PAT"]->readCode($_GET["rfc"], "");

        $child = new Template("tests-add_edit");
        $child->setData([
            "ACTION_VERB"    => "creating a new",
            "TEST_DATE"      => "",
            "TEST_RESULT_0"  => "",
            "TEST_RESULT_1"  => "",
            "PERSON_ID"      => $patient["id"],
            "PERSON_RFC"     => $_GET["rfc"],
            "PERSON_NAME"    => Utils::getFullName(
                $patient["first_name"],
                $patient["middle_name"],
                $patient["last_name"],
                $patient["suffix"]
            ),
        ]);

        $db_com = DBM::$com["SITES"]->readFilter(true);
        $insert0 = "";
        if (is_bool($db_com))
        {
            $insert0 .= Template::createElement(
                "option",
                [
                    "disabled" => "disabled"
                ],
                "You must add a laboratory/test site first using the Dashboard, under Mapping > Laboratories."
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
        $child->getDataByRef()["TEST_SITE_INSERT"] = $insert0;

        $db_com = DBM::$com["TSTT"]->read();
        $insert1 = "";
        if (is_bool($db_com))
        {
            $insert1 .= Template::createElement(
                "option",
                [
                    "disabled" => "disabled"
                ],
                "You must add a test type first using the Dashboard, under Inventory > Test Types."
            );
        }
        else
        {
            foreach ($db_com as $option)
            {
                $attributes = [
                    "value" => $option["id"],
                ];
                $insert1 .= Template::createElement("option", $attributes, $option["name"]);
            }
        }
        $child->getDataByRef()["TEST_TYPE_INSERT"] = $insert1;

        $document->attach($child);
        return $document;
    }
}

VWM::register(new AddTestsView());
