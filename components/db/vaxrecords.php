<?php

class Vaccinations extends Database
{
    public function create(
        $patient_id,
        $vax_dosenum,
        $vax_product_id,
        $vax_lotnum,
        $vax_expiry,
        $vax_date,
        $vax_site_id,
        $vax_hcw_id
    ) {
        $parameters = [
            "INSERT INTO `vaxrecords`",
            "(`patient_id`, `vax_dosenum`, `vax_product_id`, `vax_lotnum`, `vax_expiry`, `vax_date`, `vax_site_id`, `vax_hcw_id`)",
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        ];
        $values = [
            $patient_id, $vax_dosenum, $vax_product_id, $vax_lotnum
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $patient_id = null,
        $vax_dosenum = null,
        $vax_product_id = null,
        $vax_lotnum = null,
        $vax_expiry = null,
        $vax_date = null,
        $vax_site_id = null,
        $vax_hcw_id = null,
        $id
    ) {
        $query = ["UPDATE `vaxrecords` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($patient_id))
        {
            $parameters[] = "`patient_id`=?";
            $values[] = $patient_id;
        }
        if (!empty($vax_dosenum))
        {
            $parameters[] = "`vax_dosenum`=?";
            $values[] = $vax_dosenum;
        }
        if (!empty($vax_product_id))
        {
            $parameters[] = "`vax_product_id`=?";
            $values[] = $vax_product_id;
        }
        if (!empty($vax_lotnum))
        {
            $parameters[] = "`vax_lotnum`=?";
            $values[] = $vax_lotnum;
        }
        if (!empty($vax_expiry))
        {
            $parameters[] = "`vax_expiry`=?";
            $values[] = $vax_expiry;
        }
        if (!empty($vax_date))
        {
            $parameters[] = "`vax_date`=?";
            $values[] = $vax_date;
        }
        if (!empty($vax_site_id))
        {
            $parameters[] = "`vax_site_id`=?";
            $values[] = $vax_site_id;
        }
        if (!empty($vax_hcw_id))
        {
            $parameters[] = "`vax_hcw_id`=?";
            $values[] = $vax_hcw_id;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `vaxrecords`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }
    
    public function readFromPatientId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `vaxrecords` WHERE `patient_id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `vaxrecords` WHERE `id`=?",
            [$id]
        );
    }
}
