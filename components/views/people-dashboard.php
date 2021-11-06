<?php

class PeopleDashboardView extends DashboardView
{
    const SLUG = ["dashboard", "admin/people"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBTSR", "DBVAX"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "People";

        $child = new Template("people-dashboard");
        $patients = DBM::$com["PAT"]->read();

        $record_count = count($patients);
        for ($i = 0; $i < $record_count; $i++)
        {
            $record = $patients[$i];
            $cd_ref = $record["reference_code"];

            $info = [];

            $info["tests"] = DBM::$com["TSTR"]->readFromPatientId($record["id"]);
            $tested = !is_bool($info["tests"]);
            $tsr_color = "";
            $tsr_icon = "";
            $tsr_text = "";

            if ($tested)
            {
                $test_result = $info["tests"][0];
                if ((bool)$test_result["test_result"])
                {
                    $tsr_color = "red";
                    $tsr_icon = "alert-circle";
                    $tsr_text = "POSITIVE";
                }
                else
                {
                    $tsr_color = "green";
                    $tsr_icon = "checkbox-marked-circle";
                    $tsr_text = "NEGATIVE";
                }
            }
            else
            {
                $tsr_color = "yellow";
                $tsr_icon = "checkbox-marked-circle";
                $tsr_text = "UNTESTED";
            }

            $info["vaxrecord"] = DBM::$com["VAXR"]->readFromPatientId($record["id"]);
            $vaccinated = !is_bool($info["vaxrecord"]);
            $vax_color = "";
            $vax_icon = "";
            $vax_text = "";

            if ($vaccinated)
            {
                $vax_count = count($info["vaxrecord"]);
                if ($vax_count > 1)
                {
                    $vax_color = "green";
                    $vax_icon = "checkbox-marked-circle";
                    $vax_text = "NEGATIVE";
                }
                else
                {
                    $vax_color = "yellow";
                    $vax_icon = "checkbox-marked-circle";
                    $vax_text = "UNTESTED";
                }
            }
            else
            {
                $vax_color = "red";
                $vax_icon = "alert-circle";
                $vax_text = "POSITIVE";
            }

            $name = sprintf(
                "%s, %s %s",
                strtoupper($record["last_name"]),
                $record["first_name"],
                $record["middle_name"]
            );

            $row = <<<EOD
<div class="row">
    <span class="cell hr left-col">$name</span>
    <div class="cell">
        <div class="status-box $tsr_color mr1">
            <span class="mdi-set mdi-$tsr_icon"></span>
            <span>$tsr_text</span>
        </div>
        <div class="status-box $vax_color">
            <span class="mdi-set mdi-$vax_icon"></span>
            <span>$vax_text</span>
        </div>
    </div>
    <div class="cell box">
        <a class="action-button" href="/view?rfc=$cd_ref">
            <div>
                <span class="mdi-set mdi-eye"></span>
                View
            </div>
        </a>
        <a class="action-button" href="/admin/people/edit?rfc=$cd_ref">
            <div>
                <span class="mdi-set mdi-pencil"></span>
                Edit
            </div>
        </a>
        <a class="action-button" href="/admin/people/delete?rfc=$cd_ref">
            <div>
                <span class="mdi-set mdi-trash-can"></span>
                Delete
            </div>
        </a>
    </div>
</div>
EOD;
            $child->append($row);
        }

        $document->attach($child);
        return $document;
    }
}

VWM::register(new PeopleDashboardView());
