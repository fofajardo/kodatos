<?php

class HCWDashboardView extends DashboardView
{
    const SLUG = ["admin/healthcare-workers"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBWOR", "DBSIT", "DBLOC"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_1"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Healthcare Workers";

        $child = new Template("hcw-dashboard");
        $patients = DBM::$com["HCW"]->read();
        
        if (is_bool($patients))
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box mt1"
                ],
                "There are no healthcare workers registered in this platform. You may add a new person by clicking <em>Add Healthcare Worker</em>."
            );
        }
        else
        {
            $record_count = count($patients);
            for ($i = 0; $i < $record_count; $i++)
            {
                $record = $patients[$i];
                $cd_ref = $record["id"];

                $name = Utils::getFullName(
                    $record["first_name"],
                    $record["middle_name"],
                    $record["last_name"],
                    $record["suffix"]
                );

                $row = <<<EOD
    <div class="row">
        <span class="cell hr left-col">$name</span>
        <div class="cell box">
            <a class="action-button" href="/admin/healthcare-workers/edit?id=$cd_ref">
                <div>
                    <span class="mdi-set mdi-pencil"></span>
                    Edit
                </div>
            </a>
            <a class="action-button" href="/admin/healthcare-workers/delete?id=$cd_ref">
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
        }
        $document->attach($child);
        return $document;
    }
}

VWM::register(new HCWDashboardView());