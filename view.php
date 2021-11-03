<?php

require "./components/framework.php";
Framework::load("DBPAT");
Framework::load("DBLOC");
Framework::load("DBVAX");
Framework::load("DBPRO");
Framework::load("DBSIT");
Framework::load("DBWOR");

// Init document
$document = new Template("view", true);

$data = [];
$document->attachData($data);
$data["TPL_HEADER"] = Framework::getTemplate("_header");

// Info
$info = [];
$info["pid"] = 1;
$db = new Patients();
$info["patient"] = $db->readId($info["pid"]);
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

// data
$data["PATIENT_NAME"] = sprintf(
                            "%s, %s %s",
                            strtoupper($info["patient"]["last_name"]),
                            $info["patient"]["first_name"],
                            $info["patient"]["middle_name"]
                        );
$data["PATIENT_BIRTH"] = $info["patient"]["birthdate"];
$data["PATIENT_REFCODE"] = $info["patient"]["reference_code"];
$data["PATIENT_LOCATION"] = $info["location"]["name"];

// Init covid test card
$card_test = new Template("card-covidtest", true);
$document->attach($card_test);

// Init covid vax card
$card_vax = new Template("card-covidvax", true);
$card_vax_data = [];
$card_vax->attachData($card_vax_data);
$document->attach($card_vax);

$record_count = count($info["vaxrecord"]);
for ($i = 0; $i < $record_count; $i++)
{
    $record = $info["vaxrecord"][$i];
    $record_tpl = new Template("card-covidvax-dose", true);
    $card_vax->attach($record_tpl);

    // FIXME: ID should be handled in DB component directly
    $hcw = $info["workers"][$record["vax_hcw_id"] - 1];
    $record_data = [
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
    ];

    $record_tpl->setData($record_data);
}

if ($record_count > 1)
{
    $card_vax_data["STATE_ID"] = "state-high";
}
else if ($record_count == 1)
{
    $card_vax_data["STATE_ID"] = "state-mid";
}
else
{
    $card_vax_data["STATE_ID"] = "state-low";
}

// Output page
echo $document->output();

?>
