<?php

class MapVaxDashboardView extends DashboardView
{
    const SLUG = ["admin/vaccination-sites"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBSIT", "DBLOC"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_2"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Vaccination Sites";
        $document->getDataByRef()["PAGE_LANDING"] = "vaccination-sites";
        $document->getDataByRef()["NEW_VERB"] = "Vaccination Site";

        $child = new Template("generic-2col-dashboard");
        $records = DBM::$com["SITES"]->readFilter(false);
        
        if (is_bool($records))
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box mt1"
                ],
                "There are no vaccination sites registered in this platform. You may add a new person by clicking Add Vaccination Site."
            );
        }
        else
        {
            $record_count = count($records);
            for ($i = 0; $i < $record_count; $i++)
            {
                $record = $records[$i];
                $cd_ref = $record["id"];

                $name = $record["name"];

                $row = <<<EOD
    <div class="row">
        <span class="cell left-col">$name</span>
        <div class="cell box">
            <a class="action-button" href="/admin/vaccination-sites/edit?id=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-pencil"></span>
                    Edit
                </div>
            </a>
            <a class="action-button" href="/admin/vaccination-sites/delete?id=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-trash-can"></span>
                    Delete
                </div>
            </a>
        </div>
    </div>
EOD;
                $child->append($row);
            }
        }
        $this->mainTpl->attach($child);
        return $document;
    }
}

VWM::register(new MapVaxDashboardView());
