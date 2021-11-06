<?php

class Workers extends Database
{
    public function create(
        $first_name,
        $middle_name,
        $last_name,
        $suffix
    ) {
        $parameters = [
            "INSERT INTO `workers`",
            "(`first_name`, `middle_name`, `last_name`, `suffix`)",
            "VALUES (?, ?, ?)",
        ];
        $values = [
            $first_name, $middle_namem, $last_name, $suffix
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $first_name = null,
        $last_name = null,
        $middle_name = null,
        $suffix = null,
        $id
    ) {
        $query = ["UPDATE `workers` SET"];
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

		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;

        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `workers`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `workers` WHERE `id`=?",
            [$id]
        );
    }
}

DBM::add("HCW", new Workers());
