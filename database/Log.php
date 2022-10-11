<?php

class Log
{
    // Database connection properties
    protected $host = "localhost";
    protected $user = "root";
    protected $password = "";
    protected $db_name = "dornica";

    // connection property
    public $connection = null;

    // call constructor to connect to database (dornica)
    public function __construct()
    {
        $this->connection = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
        if ($this->connection->connect_error) {
            echo 'Failed to connect to database: ' . $this->connection->connect_error;
        }
    }

    // get all logs of logs table
    public function getAllLogs()
    {
        $query = "SELECT * FROM `logs` WHERE 1";
        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;

    }

    // insert a new log
    public function insertNewLog($operation, $date, $member_username, $description)
    {
        $query = "INSERT INTO `logs`(`operation`, `date`, `member_username`,`description`) VALUES ('$operation','$date','$member_username','$description')";
        return $this->connection->query($query);
    }


}