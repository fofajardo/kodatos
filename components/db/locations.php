<?php

class Locations extends Database
{
    public function create(
        $location_name
    ) {
        $parameters = [
            "INSERT INTO `locations`",
            "(`name`)",
            "VALUES (?)",
        ];
        $values = [
            $location_name
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $location_name = null,
        $id
    ) {
        $query = ["UPDATE `locations` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($location_name))
        {
            $parameters[] = "`name`=?";
            $values[] = $location_name;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `locations`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `locations` WHERE `id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries[0];
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `locations` WHERE `id`=?",
            [$id]
        );
    }
}
