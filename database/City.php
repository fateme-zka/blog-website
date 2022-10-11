<?php

class City
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

    // get all city of cities table
    public function getAllCities()
    {
        $query = "SELECT * FROM `cities` ORDER BY `id` ASC ";
        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;

    }

    // get all city of a province from cities table
    public function getAllCitiesByProvinceId($province_id)
    {
        $query = "SELECT * FROM `cities` WHERE `province_id`='$province_id' ORDER BY `id` ASC ";
        $result = $this->connection->query($query);

        $result_array = [];

        // fetch each item of table one by one
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $result_array[] = $row;
        }

        // return all provinces
        return $result_array;

    }

    // get city by id
    public function getCityById($id)
    {
        $query = "SELECT * FROM `cities` WHERE `id`='$id'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result;
    }

    // get city's name by id
    public function getCityNameById($id)
    {
        $query = "SELECT * FROM `cities` WHERE `id`='$id'";
        $result = $this->connection->query($query);
        $result = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $result['name'];
    }

    // update city values by id
    public function updateCity($id, $name, $province_id)
    {
        $query = "UPDATE `cities` SET `name`='$name', `province_id`='$province_id' WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // delete city by id
    public function deleteCityById($id)
    {
        $query = "DELETE FROM `cities` WHERE `id`='$id'";
        return $this->connection->query($query);
    }

    // insert new city
    public function insertNewCity($name, $province)
    {
        $query = "INSERT INTO `cities`(`province_id`, `name`) VALUES ('$province','$name')";
        return $this->connection->query($query);
    }

}