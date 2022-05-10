<?php

class MapLabDashboardView extends DashboardView
{
    const SLUG = ["admin/laboratories"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBSIT", "DBLOC"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_3"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Laboratories";
        $document->getDataByRef()["PAGE_LANDING"] = "laboratories";
        $document->getDataByRef()["NEW_VERB"] = "Laboratory";

        $child = new Template("generic-2col-dashboard");
        $records = DBM::$com["SITES"]->readFilter(true);
        
        if (is_bool($records))
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box mt1"
                ],
                "There are no testing laboratories registered in this platform. You may add a new person by clicking Add Laboratory."
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
            <a class="action-button" href="DIR_ROOT/admin/laboratories/edit?id=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-pencil"></span>
                    Edit
                </div>
            </a>
            <a class="action-button" href="DIR_ROOT/admin/laboratories/delete?id=$cd_ref">
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

VWM::register(new MapLabDashboardView());
