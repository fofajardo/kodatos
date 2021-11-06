<?php

class Patients extends Database
{
    public function create(
        $first_name,
        $last_name,
        $middle_name,
        $suffix,
        $gender,
        $birthdate,
        $civil_status,
        $contact_number,
        $email,
        $location_id,
        $street_address
    ) {
        $parameters = [
            "INSERT INTO `patients`",
            "(`reference_code`, `security_code`, `first_name`, `last_name`, `middle_name`, `suffix`, `gender`, `birthdate`, `civil_status`, `contact_number`, `email`, `location_id`, `street_address`)",
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        ];

        $rand = time() + random_int(0, 1000);
        $cd_raw = $first_name . $middle_name . $last_name . $birthdate . $suffix . $rand;
        $cd_ref = hash("fnv164", $cd_raw);
        $cd_sec = hash("adler32", $cd_raw);

        $values = [
            $cd_ref, $cd_sec, $first_name, $last_name, $middle_name, $suffix, $gender,
            $birthdate, $civil_status, $contact_number, $email, $location_id, $street_address
        ];
        return [$this->execute(implode(" ", $parameters), $values), $cd_ref];
    }

    public function update(
        $first_name = null,
        $last_name = null,
        $middle_name = null,
        $suffix = null,
        $gender = null,
        $birthdate = null,
        $civil_status = null,
        $contact_number = null,
        $email = null,
        $location_id = null,
        $street_address = null,
        $id
    ) {
        $query = ["UPDATE `patients` SET"];
		$parameters = [];
        $values = [];
        
        if (isset($first_name))
        {
            $parameters[] = "`first_name`=?";
            $values[] = $first_name;
        }
        if (isset($last_name))
        {
            $parameters[] = "`last_name`=?";
            $values[] = $last_name;
        }
        if (isset($middle_name))
        {
            $parameters[] = "`middle_name`=?";
            $values[] = $middle_name;
        }
        if (isset($suffix))
        {
            $parameters[] = "`suffix`=?";
            $values[] = $suffix;
        }
        if (isset($gender))
        {
            $parameters[] = "`gender`=?";
            $values[] = $gender;
        }
        if (isset($birthdate))
        {
            $parameters[] = "`birthdate`=?";
            $values[] = $birthdate;
        }
        if (isset($civil_status))
        {
            $parameters[] = "`civil_status`=?";
            $values[] = $civil_status;
        }
        if (isset($contact_number))
        {
            $parameters[] = "`contact_number`=?";
            $values[] = $contact_number;
        }
        if (isset($email))
        {
            $parameters[] = "`email`=?";
            $values[] = $email;
        }
        if (isset($location_id))
        {
            $parameters[] = "`location_id`=?";
            $values[] = $location_id;
        }
        if (isset($street_address))
        {
            $parameters[] = "`street_address`=?";
            $values[] = $street_address;
        }

		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;

        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `patients`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `patients` WHERE `id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries[0];
    }

    public function readCode(string $reference_code, string $security_code)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `patients` WHERE `reference_code`=?");
        $this->statement->execute([$reference_code]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        $exists = ($this->statement->rowCount() > 0);
        if ($exists) {
            return ($this->statement->rowCount() == 0) ? false : $entries[0];
        }
        return false;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `patients` WHERE `id`=?",
            [$id]
        );
    }
}

DBM::add("PAT", new Patients());
