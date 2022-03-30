<?php

    session_start();
    include 'conn.php';

    // menghapus tweet
    if($_GET['jenis'] == "tweet") {
        $idtweet = $_GET['id'];
        $sql = "DELETE FROM tweets WHERE idtweet=$idtweet";
        mysqli_query($conn, $sql);
        header('location: usertweet.php');
    }

    // menghapus komentar
    if($_GET['jenis'] == "komen") {
        $idcomment = $_GET['id'];
        $idtweet = $_GET['idtwt'];
        $sql = "DELETE FROM comments WHERE idcomment=$idcomment";
        mysqli_query($conn, $sql);
        header('location: detailtweet.php?id='.$idtweet);
    }

?>