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
        $document->getDataByRef()["RGA_0"] = "selected";
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

        $info = [];
        $info["products"]  = DBM::$com["PROD"]->read();
        $info["vaxsites"]  = DBM::$com["SITES"]->read();
        $info["workers"]   = DBM::$com["HCW"]->read();

        $vax_records = DBM::$com["VAXR"]->readFromPatientId($patient["id"]);
        if (!is_bool($vax_records))
        {
            $vax_record_count = count($vax_records);
            for ($i = 0; $i < $vax_record_count; $i++)
            {
                $record = $vax_records[$i];
                $record_tpl = new Template("vaccinations-view-child");

                // FIXME: ID should be handled in DB component directly
                $hcw = $info["workers"][$record["vax_hcw_id"] - 1];
                $record_tpl->setData([
                    "VAX_ID"    => $record["id"],
                    "DOSE_NUM"  => Utils::getOrdinal($i + 1),
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

        $document->attach($child);
        return $document;
    }
}

VWM::register(new ViewVaxView());
