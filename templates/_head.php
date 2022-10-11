<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Persian date picker -->
    <link type="text/css" rel="stylesheet" href="css/persianDatepicker.css"/>
    <script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="js/persianDatepicker.min.js"></script>

    <!--Bootstrap css cdn online-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <!--Bootstrap css cdn offline-->
    <link rel="stylesheet" href="./css/bootstrap.min.css">

    <!-- style.css -->
    <link href="./css/style.css" rel="stylesheet"/>

    <title>Dornica Project</title>

    <?php

    session_start();

    // require registration-functions file in all pages
    require("functions/registration-functions.php");

    ?>
</head>
<body class="font-vazir font-poppins" dir="rtl" style="direction: rtl;">