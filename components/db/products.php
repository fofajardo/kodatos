<?php

class Products extends Database
{
    public function create(
        $vax_name
    ) {
        $parameters = [
            "INSERT INTO `products`",
            "(`vax_name`)",
            "VALUES (?)",
        ];
        $values = [
            $vax_name
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $vax_name = null,
        $id
    ) {
        $query = ["UPDATE `products` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($vax_name))
        {
            $parameters[] = "`vax_name`=?";
            $values[] = $vax_name;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `products`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `products` WHERE `id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `products` WHERE `id`=?",
            [$id]
        );
    }
}
