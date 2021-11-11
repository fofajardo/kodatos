<?php

class TestsDashboardView extends DashboardView
{
    const SLUG = ["admin/test-results"];

    public function getDocument()
    {
        Framework::loadMultiple(["DBPAT", "DBTSR"]);

        $document = parent::getDocument();
        $document->getDataByRef()["RGA_0C"] = "selected";
        $document->getDataByRef()["PAGE_NAME"] = "Test Results";
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
            <div class="status-box $tsr_color mr1">
                <span class="iconify" data-icon="mdi-$tsr_icon"></span>
                <span>$tsr_text</span>
            </div>
        </div>
        <div class="cell box">
            <a class="action-button" href="/admin/test-results/view?rfc=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-eye"></span>
                    View
                </div>
            </a>
            <a class="action-button" href="/admin/test-results/add?rfc=$cd_ref">
                <div>
                    <span class="iconify" data-icon="mdi-bookmark-plus"></span>
                    Add Test Result
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

VWM::register(new TestsDashboardView());
