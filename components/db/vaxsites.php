<?php

class Sites extends Database
{
    public function create(
        $site_name,
        $location_id
    ) {
        $parameters = [
            "INSERT INTO `vaxsites`",
            "(`name`, `location_id)",
            "VALUES (?, ?)",
        ];
        $values = [
            $site_name,
            $location_id
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $site_name = null,
        $location_id = null,
        $id
    ) {
        $query = ["UPDATE `vaxsites` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($site_name))
        {
            $parameters[] = "`name`=?";
            $values[] = $site_name;
        }
        if (!empty($location_id))
        {
            $parameters[] = "`location_id`=?";
            $values[] = $location_id;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `vaxsites`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `vaxsites` WHERE `id`=?",
            [$id]
        );
    }
}

DBM::add("SITES", new Sites());
