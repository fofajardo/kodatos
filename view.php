<?php

require "./components/framework.php";
Framework::load("DBVAX");
Framework::load("DBLOC");
Framework::load("DBPAT");
Framework::load("DBPRO");
Framework::load("DBSIT");
Framework::load("DBWOR");
Framework::load("DBTSR");
Framework::load("DBTTY");

// Init document
$document = new Template("view");

$document->getDataByRef()["TPL_HEADER"] = Utils::getTemplate("_header");

if (!isset($_POST["reference"]))
{
    Utils::redirect("");
}

// Info
$info = [];
// $info["pid"] = 1;
$db = new Patients();
// $info["patient"] = $db->readId($info["pid"]);
$info["patient"] = $db->readCode($_POST["reference"], "");
$info["pid"] = $info["patient"]["id"];

if (is_bool($info["patient"]))
{
    $document->getDataByRef()["TPL_POV"] = Utils::getTemplate("view-pov-404");
}
else
{
    $db = new Locations();
    $info["location"] = $db->readId($info["patient"]["location_id"]);
    $db = new Vaccinations();
    $info["vaxrecord"] = $db->readFromPatientId($info["pid"]);
    $db = new Products();
    $info["products"] = $db->read();
    $db = new Sites();
    $info["vaxsites"] = $db->read();
    $db = new Workers();
    $info["workers"] = $db->read();
    $db = new TestRecords();
    $info["tests"] = $db->readFromPatientId($info["pid"]);
    $db = new TestType();
    $info["testtype"] = $db->read();

    $vaccinated = !is_bool($info["vaxrecord"]);
    $tested = !is_bool($info["tests"]);

    // data
    $document->getDataByRef()["TPL_POV"] = Utils::getTemplate("view-pov");
    $document->getDataByRef()["PATIENT_NAME"] = sprintf(
                                "%s, %s %s",
                                strtoupper($info["patient"]["last_name"]),
                                $info["patient"]["first_name"],
                                $info["patient"]["middle_name"]
                            );
    $document->getDataByRef()["PATIENT_BIRTH"] = $info["patient"]["birthdate"];
    $document->getDataByRef()["PATIENT_REFCODE"] = strtoupper($info["patient"]["reference_code"]);
    $document->getDataByRef()["PATIENT_LOCATION"] = $info["location"]["name"];

    // Init covid test card
    $card_test = new Template("card-covidtest");
    $document->attach($card_test);

    if ($tested)
    {
        // Get the latest record
        $record = $info["tests"][0];
        $record_tpl = new Template("card-covidtest-child");
        $card_test->attach($record_tpl);

        // FIXME: ID should be handled in DB component directly
        $record_tpl->setData([
            "TEST_DATE" => $record["test_date"],
            "TEST_TYPE" => $info["testtype"][$record["test_type"] - 1]["name"],
            "TEST_SITE" => $info["vaxsites"][$record["test_site_id"] - 1]["name"],
        ]);
        
        if ($record["test_result"] == 1)
        {
            $card_test->getDataByRef()["STATE_ID"] = "state-low";
        }
        else
        {
            $card_test->getDataByRef()["STATE_ID"] = "state-high";
        }
    }
    else
    {
        $card_test->getDataByRef()["STATE_ID"] = "state-mid";
        $card_test->append("Nothing further to show.");
    }

    // Init covid vax card
    $card_vax = new Template("card-covidvax");
    $document->attach($card_vax);

    if ($vaccinated)
    {
        $record_count = count($info["vaxrecord"]);
        for ($i = 0; $i < $record_count; $i++)
        {
            $record = $info["vaxrecord"][$i];
            $record_tpl = new Template("card-covidvax-child");
            $card_vax->attach($record_tpl);

            // FIXME: ID should be handled in DB component directly
            $hcw = $info["workers"][$record["vax_hcw_id"] - 1];
            $record_tpl->setData([
                "DOSE_NUM"  => Utils::getOrdinal($record["vax_dosenum"]),
                "VAX_DATE"  => $record["vax_date"],
                "VAX_TYPE"  => "COVID-19 vaccine",
                "PROD_NAME" => $info["products"][$record["vax_product_id"] - 1]["vax_name"],
                "LOT_NUM"   => sprintf("%s / Expiry: %s", $record["vax_lotnum"], $record["vax_expiry"]),
                "VAX_SITE"  => $info["vaxsites"][$record["vax_site_id"] - 1]["name"],
                "HCW_NAME"  => sprintf(
                                    "%s, %s %s",
                                    strtoupper($hcw["last_name"]),
                                    $hcw["first_name"],
                                    $hcw["middle_name"]
                                )
            ]);
        }
        
        if ($record_count > 1)
        {
            $card_vax->getDataByRef()["STATE_ID"] = "state-high";
        }
        else
        {
            $card_vax->getDataByRef()["STATE_ID"] = "state-mid";
        }
    }
    else
    {
        $card_vax->getDataByRef()["STATE_ID"] = "state-low";
        $card_vax->append("Nothing further to show.");
    }
}

// Output page
echo $document->output();

?>
