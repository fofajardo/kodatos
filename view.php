<?php

require "./components/framework.php";
Framework::load("DBPAT");
Framework::load("DBLOC");

// Init document
$document = new Template("view", true);

$data = [];
$document->attachData($data);
$data["TPL_HEADER"] = Framework::getTemplate("_header");

// Info
$info = [];
$db = new Patients();
$info["patient"] = $db->readId(1);
$db = new Locations();
$info["location"] = $db->readId($info["patient"]["location_id"]);

// data
$data["PATIENT_NAME"] = sprintf("%s, %s %s", strtoupper($info["patient"]["last_name"]), $info["patient"]["first_name"], $info["patient"]["middle_name"]);
$data["PATIENT_BIRTH"] = $info["patient"]["birthdate"];
$data["PATIENT_REFCODE"] = $info["patient"]["reference_code"];
$data["PATIENT_LOCATION"] = $info["location"]["name"];

// Init covid test card
$card_0 = new Template("card-covidtest", true);
$card_1 = new Template("card-covidvax", true);
$card_1b = new Template("card-covidvax-dose", true);
$card_1->attach($card_1b);
$card_1->attach($card_1b);

$document->attach($card_0);
$document->attach($card_1);

// Output page
echo $document->output();

?>
