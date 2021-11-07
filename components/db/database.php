<?php

class Database
{
    protected $connection = null;
    protected $statement = null;
    
    public function __construct()
    {
        $this->connection = DBM::$connection;
    }
    
    public function __destruct()
    {
        if ($this->connection != null)
        {
            $this->connection = null;
        }
        if ($this->statement != null)
        {
            $this->statement = null;
        }
    }
    
    public function execute($statement, $parameters = [])
    {
        try
        {
            $this->statement = $this->connection->prepare($statement);
            $this->statement->execute($parameters);
        } catch (PDOException $e)
        {
            $this->error = $e->getMessage();
            return false;
        }
        $this->statement = null;
        return true;
    }
    
    public function getColumnNames()
    {
        if ($this->statement == null)
        {
            $this->read();
        }
        
        $columns = [];
        
        for ($i = 0; $i < $this->statement->columnCount(); $i++)
        {
            $columns[$i] = $this->statement->getColumnMeta($i)["name"];
        }
        
        return $columns;
    }
}

class DBM
{
    public static $com = [];
    public static $connection = null;

    public static function initialize()
    {
        try
        {
            self::$connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $e)
        {
            if (!APP_PRODUCTION)
            {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        register_shutdown_function(
            function () {
                if (DBM::$connection != null)
                {
                    DBM::$connection = null;
                }
            }
        );
    }

    public static function add($name, $dbComponent)
    {
        self::$com[$name] = $dbComponent;
    }

    public static function remove($name)
    {
        if (array_key_exists($name, self::$com))
        {
            unset(self::$com[$name]);
        }
    }
}

DBM::initialize();
