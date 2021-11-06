<?php

class ViewRecordView implements View
{
    const SLUG = "view";
    
    public function getDocument()
    {
        Framework::loadMultiple(["DBVAX", "DBLOC", "DBPAT", "DBPRO", "DBSIT", "DBWOR", "DBTSR", "DBTTY"]);

        // Init document
        $document = new Template("view");
        $header_tpl = new Template("_header");
        $document->setData([
            "TPL_HEADER" => $header_tpl->output(),
            "TPL_POV" => "",
            "CONTENT_VISIBLE" => "",
        ]);

        $initial_refcode = "";

        // Allow passing reference code via query string if signed in
        if (Auth::isSignedIn())
        {
            if (isset($_GET["rfc"]))
            {
                $initial_refcode = $_GET["rfc"];
            }
        }

        if (isset($_POST["reference"]))
        {
            $initial_refcode = $_POST["reference"];
        }

        if (empty($initial_refcode))
        {
            Utils::redirect("");            
        }

        // Info
        $info = [];
        $info["patient"] = DBM::$com["PAT"]->readCode($initial_refcode, "");

        if (is_bool($info["patient"]))
        {
            $nf_tpl = new Template("view-pov-404");
            $document->getDataByRef()["TPL_POV"] = $nf_tpl->output();
            $document->getDataByRef()["CONTENT_VISIBLE"] = "hidden";
        }
        else
        {
            $info["pid"] = $info["patient"]["id"];
            $info["location"]  = DBM::$com["LOC"]->readId($info["patient"]["location_id"]);
            $info["vaxrecord"] = DBM::$com["VAXR"]->readFromPatientId($info["pid"]);
            $info["products"]  = DBM::$com["PROD"]->read();
            $info["vaxsites"]  = DBM::$com["SITES"]->read();
            $info["workers"]   = DBM::$com["HCW"]->read();
            $info["tests"]     = DBM::$com["TSTR"]->readFromPatientId($info["pid"]);
            $info["testtype"]  = DBM::$com["TSTT"]->read();

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
                
                if ((bool)$record["test_result"])
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

        return $document;
    }
}

VWM::register(new ViewRecordView());
