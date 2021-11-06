<?php

class RegistrationsDashboardView extends DashboardView
{
    const SLUG = ["dashboard", "admin/registration"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT"]); //, "DBLOC", "DBPAT", "DBPRO", "DBSIT", "DBWOR", "DBTSR", "DBTTY"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0"] = "selected";

        $child = new Template("registration-dashboard");
        $patients = DBM::$com["PAT"]->read();

        $record_count = count($patients);
        for ($i = 0; $i < $record_count; $i++)
        {
            $record = $patients[$i];
            $cd_ref = $record["reference_code"];

            $name = sprintf(
                "%s, %s %s",
                strtoupper($record["last_name"]),
                $record["first_name"],
                $record["middle_name"]
            );

            $row = <<<EOD
<div class="row">
    <span class="cell left-col">$name</span>
    <span class="cell box">
        <a class="action-button" href="/view?rfc=$cd_ref">
            <div>
                <span class="mdi-set mdi-account"></span>
                View
            </div>
        </a>
        <a class="action-button" href="/admin/registration/edit?rfc=$cd_ref">
            <div>
                <span class="mdi-set mdi-account"></span>
                Edit
            </div>
        </a>
        <a class="action-button" href="/admin/registration/delete?rfc=$cd_ref">
            <div>
                <span class="mdi-set mdi-account"></span>
                Delete
            </div>
        </a>
    </span>
</div>
EOD;
            $child->append($row);
        }

        $document->attach($child);
        return $document;
    }
}

VWM::register(new RegistrationsDashboardView());
