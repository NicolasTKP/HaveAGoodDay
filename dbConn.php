<?php
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'haveagoodday';

    $connection = mysqli_connect($server, $user, $password, $database);

    if ($connection === false) {
        die("Database connection failed" . mysqli_connect_error($connection));
        print('fail');
    }