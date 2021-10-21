<?php

class Workers extends Database
{
    public function create(
        $first_name,
        $last_name,
        $middle_name
    ) {
        $parameters = [
            "INSERT INTO `workers`",
            "(`first_name`, `last_name`, `middle_name`)",
            "VALUES (?, ?, ?)",
        ];
        $values = [
            $first_name, $last_name, $middle_name
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $first_name = null,
        $last_name = null,
        $middle_name = null,
        $id
    ) {
        $query = ["UPDATE `workers` SET"];
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
