<?php

class Permission
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

    // return member type name by permission id
    public function getPermissionTypeById($id)
    {
        $query = "SELECT * FROM `permissions` WHERE `id`='$id'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result['type'];
    }

}