<?php

class RegistrationsDashboardView extends DashboardView
{
    const SLUG = ["dashboard", "admin/registrations"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT"]); //, "DBLOC", "DBPAT", "DBPRO", "DBSIT", "DBWOR", "DBTSR", "DBTTY"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0"] = "selected";
        $document->getDataByRef()["SECTION_TITLE"] = "Registrations";

        $child = new Template("dashboard-registrations");
        $patients = DBM::$com["PAT"]->read();

        $record_count = count($patients);
        for ($i = 0; $i < $record_count; $i++)
        {
            $record = $patients[$i];
            $name = sprintf(
                "%s, %s %s",
                strtoupper($record["last_name"]),
                $record["first_name"],
                $record["middle_name"]
            );

            $row = <<<EOD
<div class="row">
    <span class="cell left-col">$name</span>
    <span class="cell">View Edit Delete</span>
</div>
EOD;
            $child->append($row);
        }

        $document->attach($child);
        return $document;
    }
}

VWM::register(new RegistrationsDashboardView());
