<?php

class Province
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

    // get all province of provinces table
    public function getAllProvinces()
    {
        $query = "SELECT * FROM `provinces` ORDER BY `id` ASC ";
        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;

    }

    // get province by id
    public function getProvinceById($id)
    {
        $query = "SELECT * FROM `provinces` WHERE `id`='$id'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result;
    }

    // update province name by id
    public function updateProvinceName($id, $name)
    {
        $query = "UPDATE `provinces` SET `name`='$name' WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // delete province by id
    public function deleteProvinceById($id)
    {
        $query = "DELETE FROM `provinces` WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // insert new province
    public function insertNewProvince($name){
        $query = "INSERT INTO `provinces`(`name`) VALUES ('$name')";
        return $this->connection->query($query);
    }

    // return province name by id
    public function getProvinceNameById($id){
        $query = "SELECT * FROM `provinces` WHERE `id`='$id'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result['name'];
    }


}
