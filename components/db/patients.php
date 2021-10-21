<?php

class Patients extends Database
{
    public function create(
        $first_name,
        $last_name,
        $middle_name,
        $birthdate
    ) {
        $parameters = [
            "INSERT INTO `patients`",
            "(`reference_code`, `security_code`, `first_name`, `last_name`, `middle_name`, `birthdate`)",
            "VALUES (?, ?, ?, ?, ?, ?)",
        ];

        $rand = random_int(0, 1000);
        $cd_ref = hash("fnv164", $first_name . $middle_name . $last_name . $birthdate . $rand);
        $cd_sec = hash("adler32", $first_name . $middle_name . $last_name . $birthdate . $rand);

        $values = [
            $cd_ref, $cd_sec, $first_name, $last_name, $middle_name, $birthdate
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $first_name = null,
        $last_name = null,
        $middle_name = null,
        $birthdate = null,
        $id
    ) {
        $query = ["UPDATE `patients` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($first_name))
        {
            $parameters[] = "`first_name`=?";
            $values[] = $first_name;
        }
        if (!empty($last_name))
        {
            $parameters[] = "`last_name`=?";
            $values[] = $last_name;
        }
        if (!empty($middle_name))
        {
            $parameters[] = "`middle_name`=?";
            $values[] = $middle_name;
        }
        if (!empty($birthdate))
        {
            $parameters[] = "`birthdate`=?";
            $values[] = $birthdate;
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
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readCode(string $reference_code, string $security_code)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `patients` WHERE `reference_code`=?");
        $this->statement->execute([$reference_code]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        $exists = ($this->statement->rowCount() > 0);
        if ($exists) {
            return ($this->statement->rowCount() == 0) ? false : $entries;
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
