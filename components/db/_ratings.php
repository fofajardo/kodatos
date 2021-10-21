<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
require_once($common_directory . "/db/database.php");

class Ratings extends Database
{
    public function create(
        $session_id,
        $player_name,
        $roundset_name,
        $overall_rating,
        $fun_rating,
        $graphics_rating,
        $tags,
        $percent_complete
    ) {
        $parameters = [
            "INSERT INTO `ratings`",
            "(`session_id`, `player_name`, `roundset_name`, `overall_rating`,",
            "`fun_rating`, `graphics_rating`, `tags`, `percent_complete`)",
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        ];
        $values = [
            $session_id, $player_name, $roundset_name, $overall_rating,
            $fun_rating, $graphics_rating, $tags, $percent_complete,
        ];
        return $this->execute(implode(" ", $parameters), $values);
    }
	
    public function update(
        $session_id = null,
        $player_name = null,
        $roundset_name = null,
        $overall_rating = null,
        $fun_rating = null,
        $graphics_rating = null,
        $tags = null,
        $percent_complete = null,
        $id
    ) {
        $query = ["UPDATE `ratings` SET"];
		$parameters = [];
        $values = [];
        
        if (!empty($session_id))
        {
            $parameters[] = "`session_id`=?";
            $values[] = $session_id;
        }
        if (!empty($player_name))
        {
            $parameters[] = "`player_name`=?";
            $values[] = $player_name;
        }
        if (!empty($roundset_name))
        {
            $parameters[] = "`roundset_name`=?";
            $values[] = $roundset_name;
        }
        if (!empty($overall_rating))
        {
            $parameters[] = "`overall_rating`=?";
            $values[] = $overall_rating;
        }
        if (!empty($fun_rating))
        {
            $parameters[] = "`fun_rating`=?";
            $values[] = $fun_rating;
        }
        if (!empty($graphics_rating))
        {
            $parameters[] = "`graphics_rating`=?";
            $values[] = $graphics_rating;
        }
        if (!empty($tags))
        {
            $parameters[] = "`tags`=?";
            $values[] = $tags;
        }
        if (!empty($percent_complete))
        {
            $parameters[] = "`percent_complete`=?";
            $values[] = $percent_complete;
        }
		
        $query[] = implode(",", $parameters);
        $query[] = "WHERE `id`=?";
        $values[] = $id;
        
        return $this->execute(implode(" ", $query), $values);
    }
    
    public function get_id(
        $session_id,
        $player_name,
        $roundset_name
    ) {
        $parameters = [
            "SELECT * FROM `ratings` WHERE",
            "`session_id`=? AND `player_name`=? AND `roundset_name`=?"
        ];
        $values = [$session_id, $player_name, $roundset_name];
        $this->statement = $this->connection->prepare(implode(" ", $parameters));
        $this->statement->execute($values);
		$entry = $this->statement->fetch(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entry["id"];
    }

    public function read()
    {
        $this->statement = $this->connection->prepare("SELECT * FROM `ratings`");
        $this->statement->execute();
        $entries = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return ($this->statement->rowCount() == 0) ? false : $entries;
    }
    
    public function delete(int $id)
    {
        return $this->execute(
            "DELETE FROM `ratings` WHERE `id`=?",
            [$id]
        );
    }
}
