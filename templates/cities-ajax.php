<?php

session_start();

$connection = mysqli_connect('localhost', 'root', '', 'dornica');

// if province id is sent
if (isset($_POST['province_id'])) {

    $province_id = $_POST['province_id'];

    // get those cities which are in this province
    $query = "SELECT `id`, `province_id`, `name` FROM `cities` WHERE `province_id`='$province_id'";

    $result = $connection->query($query);

    $result_array = [];

    // fetch each item of table one by one
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $result_array[] = $row;
    }

    // return the cities of province
    echo json_encode($result_array);
}



