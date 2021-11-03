<?php

class Vaccinations extends Database
{
    public function create(
        $patient_id,
        $test_date,
        $test_site_id,
        $test_result
    ) {
        $parameters = [
            "INSERT INTO `testrecords`",
            "(`patient_id`, `test_date`, `test_site_id`, `test_result`)",
            "VALUES (?, ?, ?, ?)",
        ];
        $values = [
            $patient_id, $test_date, $test_site_id, $test_result
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $patient_id = null,
        $test_date = null,
        $test_site_id = null,
        $test_result = null,
        $id
    ) {
        $query = ["UPDATE `testrecords` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($patient_id))
        {
            $parameters[] = "`patient_id`=?";
            $values[] = $patient_id;
        }
        if (!empty($test_date))
        {
            $parameters[] = "`test_date`=?";
            $values[] = $test_date;
        }
        if (!empty($test_site_id))
        {
            $parameters[] = "`test_site_id`=?";
            $values[] = $test_site_id;
        }
        if (!empty($test_result))
        {
            $parameters[] = "`test_result`=?";
            $values[] = $test_result;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `testrecords`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }
    
    public function readFromPatientId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `testrecords` WHERE `patient_id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `testrecords` WHERE `id`=?",
            [$id]
        );
    }
}
