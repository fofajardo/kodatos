<?php

class LocationDashboardView extends DashboardView
{
    const SLUG = ["admin/locations"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBSIT", "DBLOC"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_4"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Locations";
        $document->getDataByRef()["PAGE_LANDING"] = "locations";
        $document->getDataByRef()["NEW_VERB"] = "Location";

        $child = new Template("generic-2col-dashboard");
        $records = DBM::$com["LOC"]->read();
        
        if (is_bool($records))
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box mt1"
                ],
                "There are no locations registered in this platform. You may add a new location by clicking <em>Add Location</em>."
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
        <span class="cell hr left-col">$name</span>
        <div class="cell box">
            <a class="action-button" href="/admin/locations/edit?id=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-pencil"></span>
                    Edit
                </div>
            </a>
            <a class="action-button" href="/admin/locations/delete?id=$cd_ref">
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

VWM::register(new LocationDashboardView());
