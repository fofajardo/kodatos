<?php

header("Content-Type: text/html; charset=utf-8");
require_once "../components/framework.php";

Framework::load("DBACC"); // accounts
Framework::load("DBVAX"); // vax
Framework::load("DBPAT"); // patients
Framework::load("DBPRO"); // products (vaccines)
Framework::load("DBSIT"); // vaccination sites
Framework::load("DBWOR"); // health care workers

$test = new Vaccinations();
$data = $test->readFromPatientId(1);
var_dump($data);

$test = new Patients();
$data = $test->readId(1);
var_dump($data);
// echo $test->create("William", "McCoy", "Rojo", "1985-08-14");

$test = new Accounts();
$data = $test->read();
var_dump($data);
// echo $test->create("jose", "joseb@dict.gob.fil", "pandemic2020", 3);
var_dump($test->readCredentials("joseb@dict.gob.fil", "pandemic2020"));
var_dump($test->readCredentials("jose", "pandemic2020"));
echo "wrong" . PHP_EOL;
var_dump($test->readCredentials("jose", "2020"));
echo "non-existent" . PHP_EOL;
var_dump($test->readCredentials("this_should_not_exist", "2020"));

// echo Framework::$dir["S_ROOT"];