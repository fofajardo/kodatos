<?php

class Users extends Database
{
    public function create(
        $username,
        $email,
        $password,
        $role_id
    ) {
        $parameters = [
            "INSERT INTO `users`",
            "(`username`, `email`, `password`, `role_id`)",
            "VALUES (?, ?, ?, ?)",
        ];
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $values = [
            $username, $email, $hash, $role_id
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }

    public function update(
        $username = null,
        $email = null,
        $password = null,
        $role_id = null,
        $id
    ) {
        $query = ["UPDATE `users` SET"];
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
        
		$query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `users`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function readId(int $id)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `users` WHERE `id`=?");
        $this->statement->execute([$id]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }

    public function checkPassword(string $email, string $password)
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `users` WHERE `email`=? OR `username`=?");
        $this->statement->execute([$email, $email]);
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        $exists = ($this->statement->rowCount() > 0);

        if ($exists) {
            $hash = $entries[0]["password"];
            return password_verify($password, $hash);
        }

        return false;
    }

    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `users` WHERE `id`=?",
            [$id]
        );
    }
}
