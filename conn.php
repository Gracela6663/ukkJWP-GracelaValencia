<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "ukkjuniorweb";

    // mengkoneksikan dengan database
    $conn = new mysqli($servername, $username, $password, $database);

    //cek koneksi
    if ($conn -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
    }

?>