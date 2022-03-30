<?php

    session_start();

    // menghapus session yang ada
    session_destroy();

    // mengembalikan ke page login
    header('location: login.php');

?>