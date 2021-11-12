<?php

class Sessions extends Database
{
    public function create(
        $session_id,
        $account_id
    ) {
        if (empty($session_id) || empty($account_id))
        {
            return false;
        }
        $parameters = [
            "INSERT INTO `account_sessions`",
            "(`session_id`, `account_id`, `creation`, `expiry`)",
            "VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY))",
        ];
        $values = [
            $session_id,
            $account_id,
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `account_sessions`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readFromAccount(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `account_sessions` WHERE `account_id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readFromSID(string $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `account_sessions` WHERE `session_id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries[0];
    }


    public function update(
        $expiry = null,
        $id
    ) {
        $query = ["UPDATE `account_sessions` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($expiry))
        {
            $parameters[] = "`expiry`=?";
            $values[] = $expiry;
        }
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `session_id`=?";
        $values[] = $id;

        return $this->execute(implode(" ", $query), $values);
    }

    public function delete(string $id)
    {
        return $this->execute(
            "DELETE FROM `account_sessions` WHERE `session_id`=?",
            [$id]
        );
    }

    public function deleteFromAccount(int $id)
    {
        return $this->execute(
            "DELETE FROM `account_sessions` WHERE `account_id`=?",
            [$id]
        );
    }
}

DBM::add("SESS", new Sessions());
