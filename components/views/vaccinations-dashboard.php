<?php

class VaxDashboardView extends DashboardView
{
    const SLUG = ["admin/vaccinations"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBVAX"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0B"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Vaccinations";
        $document->getDataByRef()["PAGE_LANDING"] = "people";
        $document->getDataByRef()["NEW_VERB"] = "Person";

        $child = new Template("generic-3col-dashboard");
        $patients = DBM::$com["PAT"]->read();

        if (is_bool($patients))
        {
            $child->appendElement(
                "span",
                [
                    "class" => "status-box mt1"
                ],
                "There are no people registered in this platform. You may add a new person by clicking Add Person."
            );
        }
        else
        {
            $record_count = count($patients);
            for ($i = 0; $i < $record_count; $i++)
            {
                $record = $patients[$i];
                $cd_ref = $record["reference_code"];

                $info = [];

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
                        $vax_text = "FULLY VACCINATED";
                    }
                    else
                    {
                        $vax_color = "yellow";
                        $vax_icon = "checkbox-marked-circle";
                        $vax_text = "PARTIALLY VACCINATED";
                    }
                }
                else
                {
                    $vax_color = "red";
                    $vax_icon = "alert-circle";
                    $vax_text = "NOT VACCINATED";
                }

                $name = Utils::getFullName(
                    $record["first_name"],
                    $record["middle_name"],
                    $record["last_name"],
                    $record["suffix"]
                );

                $row = <<<EOD
    <div class="row">
        <span class="cell left-col">$name</span>
        <div class="cell">
            <div class="status-box $vax_color">
                <span class="iconify" data-icon="mdi-$vax_icon"></span>
                <span>$vax_text</span>
            </div>
        </div>
        <div class="cell box">
            <a class="action-button" href="/admin/vaccinations/view?rfc=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-eye"></span>
                    View
                </div>
            </a>
            <a class="action-button" href="/admin/vaccinations/add?rfc=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-bookmark-plus"></span>
                    Add Dose
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

VWM::register(new VaxDashboardView());
