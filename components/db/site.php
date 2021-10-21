<?php

class Sites extends Database
{
    public function create(
        $site_name
    ) {
        $parameters = [
            "INSERT INTO `sites`",
            "(`site_name`)",
            "VALUES (?)",
        ];
        $values = [
            $site_name
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $site_name = null,
        $id
    ) {
        $query = ["UPDATE `sites` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($site_name))
        {
            $parameters[] = "`site_name`=?";
            $values[] = $site_name;
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

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `sites` WHERE `id`=?",
            [$id]
        );
    }
}
