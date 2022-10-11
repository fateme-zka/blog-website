<?php

session_start();

$connection = mysqli_connect('localhost', 'root', '', 'dornica');

// if get_provinces is set to true
if(isset($_POST['get_provinces'])){

    // get all provinces query
    $query = "SELECT `id`, `name` FROM `provinces`";

    $result = $connection->query($query);

    $result_array = [];

    // fetch each item of table one by one
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $result_array[] = $row;
    }

    // return all provinces
    echo json_encode($result_array);
}

