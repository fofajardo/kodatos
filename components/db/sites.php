<?php

class Sites extends Database
{
    public function create(
        $site_name,
        $location_id,
        $is_laboratory
    ) {
        $parameters = [
            "INSERT INTO `sites`",
            "(`name`, `location_id`, `is_laboratory`)",
            "VALUES (?, ?, ?)",
        ];
        $values = [
            $site_name, $location_id, $is_laboratory
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $site_name = null,
        $location_id = null,
        $is_laboratory = null,
        $id
    ) {
        $query = ["UPDATE `sites` SET"];
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
        if (!empty($is_laboratory))
        {
            $parameters[] = "`is_laboratory`=?";
            $values[] = $is_laboratory;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `sites`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readFilter(bool $laboratories_only)
    {
        $filter_value = $laboratories_only ? 1 : 0;
        $this->statement = $this->connection->prepare("SELECT * FROM `sites` WHERE `is_laboratory` = '$filter_value'");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `sites` WHERE `id`=?",
            [$id]
        );
    }
}

DBM::add("SITES", new Sites());
