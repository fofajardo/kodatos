<?php

class ViewVaxView extends DashboardView
{
    const SLUG = ["admin/vaccinations/view"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBVAX", "DBWOR", "DBPRO", "DBSIT"]);

        if (empty($_GET) || !isset($_GET["rfc"]))
        {
            Utils::redirect("admin/people");
        }

        $patient = DBM::$com["PAT"]->readCode($_GET["rfc"], "");

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0B"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Vaccinations";
        $document->getDataByRef()["PAGE_MARKER"] = "vax-view";

        $child = new Template("vaccinations-view");
        $child->setData([
            "PERSON_NAME" => Utils::getFullName(
                $patient["first_name"],
                $patient["middle_name"],
                $patient["last_name"],
                $patient["suffix"]
            ),
            "PERSON_RFC" => $patient["reference_code"],
        ]);

        $vax_records = DBM::$com["VAXR"]->readFromPatientId($patient["id"]);
        if (!is_bool($vax_records))
        {
            $vax_record_count = count($vax_records);
            for ($i = 0; $i < $vax_record_count; $i++)
            {
                $record = $vax_records[$i];
                $record_tpl = new Template("vaccinations-view-child");

                $hcw = DBM::$com["HCW"]->readId($record["vax_hcw_id"]);
                $record_tpl->setData([
                    "VAX_ID"    => $record["id"],
                    "DOSE_NUM"  => Utils::getOrdinal($i + 1),
                    "VAX_DATE"  => $record["vax_date"],
                    "VAX_TYPE"  => "COVID-19 vaccine",
                    "PROD_NAME" => DBM::$com["PROD"]->readId($record["vax_product_id"])["vax_name"],
                    "LOT_NUM"   => sprintf("%s / Expiry: %s", $record["vax_lotnum"], $record["vax_expiry"]),
                    "VAX_SITE"  => DBM::$com["SITES"]->readId($record["vax_site_id"])["name"],
                    "HCW_NAME"  => Utils::getFullName(
                                        $hcw["first_name"],
                                        $hcw["middle_name"],
                                        $hcw["last_name"],
                                        $hcw["suffix"],
                                   ),
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
                "This person has no vaccination records. You may add a new record by clicking <em>Add Dose</em>."
            );
        }

        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new ViewVaxView());
