<?php

class Sessions extends Database
{
    public function create(
        $session_id,
        $account_id
    ) {
        $parameters = [
            "INSERT INTO `account_sessions`",
            "(`session_id`, `account_id`, `login_time`)",
            "VALUES (?, ?, NOW())",
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

    public function delete(int $id)
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
