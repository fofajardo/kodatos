<?php

class Accounts extends Database
{
    public function create(
        $username,
        $email,
        $password,
        $role_id,
        $location_id,
        $enabled
    ) {
        $parameters = [
            "INSERT INTO `accounts`",
            "(`username`, `email`, `password`, `role_id`, `location_id`, `enabled`)",
            "VALUES (?, ?, ?, ?)",
        ];
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $values = [
            $username, $email, $hash, $role_id, $location_id, $enabled
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $username = null,
        $email = null,
        $password = null,
        $role_id = null,
        $location_id = null,
        $enabled = null,
        $id
    ) {
        $query = ["UPDATE `accounts` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($username))
        {
            $parameters[] = "`username`=?";
            $values[] = $username;
        }
        if (!empty($email))
        {
            $parameters[] = "`email`=?";
            $values[] = $email;
        }
        if (!empty($password))
        {
            $parameters[] = "`password`=?";
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $values[] = $hash;
        }
        if (!empty($role_id))
        {
            $parameters[] = "`role_id`=?";
            $values[] = $role_id;
        }
        if (!empty($location_id))
        {
            $parameters[] = "`location_id`=?";
            $values[] = $location_id;
        }
        if (!empty($enabled))
        {
            $parameters[] = "`enabled`=?";
            $values[] = $enabled;
        }

		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;

        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `accounts`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `accounts` WHERE `id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries[0];
    }

    public function readCredentials(string $email, string $password)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `accounts` WHERE `email`=? OR `username`=?");
        $this->statement->execute([$email, $email]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        $exists = ($this->statement->rowCount() > 0);

        if ($exists) {
            $record = $entries[0];
            $password_hash = $record["password"];
            $matches = password_verify($password, $password_hash);
            return $matches ? $record : false;
        }

        return false;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `accounts` WHERE `id`=?",
            [$id]
        );
    }
}

DBM::add("ACC", new Accounts());
