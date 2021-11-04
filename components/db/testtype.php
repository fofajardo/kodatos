<?php

class TestType extends Database
{
    public function create(
        $test_type
    ) {
        $parameters = [
            "INSERT INTO `testtype`",
            "(`name`)",
            "VALUES (?)",
        ];
        $values = [
            $test_type
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $test_type = null,
        $id
    ) {
        $query = ["UPDATE `testtype` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($test_type))
        {
            $parameters[] = "`name`=?";
            $values[] = $test_type;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `testtype`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `testtype` WHERE `id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries[0];
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `testtype` WHERE `id`=?",
            [$id]
        );
    }
}

DBM::add("TSTT", new TestType());
