<?php

class Accounts extends Database
{
    public function create(
        $username,
        $email,
        $password,
        $role_id,
        $group_id,
        $enabled,
        $first_name,
        $middle_name,
        $last_name,
        $suffix
    ) {
        $parameters = [
            "INSERT INTO `accounts`",
            "(`username`, `email`, `password`, `role_id`, `group_id`, `enabled`, `first_name`, `middle_name`, `last_name`, `suffix`)",
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        ];
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $values = [
            $username, $email, $hash, $role_id, $group_id, $enabled, $first_name, $middle_name, $last_name, $suffix
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $username = null,
        $email = null,
        $password = null,
        $role_id = null,
        $group_id = null,
        $enabled = null,
        $first_name = null,
        $middle_name = null,
        $last_name = null,
        $suffix = null,
        $id
    ) {
        $query = ["UPDATE `accounts` SET"];
		$parameters = [];
        $values = [];

        if (isset($first_name))
        {
            $parameters[] = "`first_name`=?";
            $values[] = $first_name;
        }
        if (isset($last_name))
        {
            $parameters[] = "`last_name`=?";
            $values[] = $last_name;
        }
        if (isset($middle_name))
        {
            $parameters[] = "`middle_name`=?";
            $values[] = $middle_name;
        }
        if (isset($suffix))
        {
            $parameters[] = "`suffix`=?";
            $values[] = $suffix;
        }        
        if (isset($username))
        {
            $parameters[] = "`username`=?";
            $values[] = $username;
        }
        if (isset($email))
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
        if (isset($role_id))
        {
            $parameters[] = "`role_id`=?";
            $values[] = $role_id;
        }
        if (isset($group_id))
        {
            $parameters[] = "`group_id`=?";
            $values[] = $group_id;
        }
        if (isset($enabled))
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
            return $matches ? $record : 2;
        }

        return 1;
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
