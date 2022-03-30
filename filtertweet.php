<?php

    session_start();
    include 'conn.php';

    // cek apakah user sudah login
    if(!isset($_SESSION['login'])) {
        header('location: login.php');
    } else {
        // mengambil username
        $username = $_SESSION['username'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter tweet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/ff52be7d16.js" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Twitter</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambahtweet.php">Tambah tweet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="filtertweet.php">Filter Tweet</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $username ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="usertweet.php">Tweet saya</a></li>
                            <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 20px;">
        <form class="" method="POST" id="form_siswa">
            <div class="mb-3" style="display: flex;">
                <input type="text" class="form-control" id="username" name="tag" placeholder="Search by tag.." autocomplete="off">
                <button class="btn btn-primary" type="submit" name="submit">Search</button>
            </div>
        </form>

    <?php
       if(isset($_POST['submit'])) {
        $tag = $_POST['tag'];
        $sql = "SELECT * FROM tweets WHERE tags LIKE '%$tag%'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_num_rows($result);

        if($row != 0) {
        // looping data
        while ($roww = mysqli_fetch_assoc($result)) {
            $idtweet = $roww['idtweet'];
            $user = $roww['userid'];
            $judultweet = $roww['judultweet'];
            $isitweet = $roww['isitweet'];
            
            $result2 = mysqli_query($conn, "SELECT * FROM users WHERE userid=$user");
            $row2 = mysqli_fetch_assoc($result2);
            $username = $row2['username'];
       
    ?>

        <div class="card" style="cursor: pointer; margin-bottom: 20px;" onclick="location.href = 'detailtweet.php?id=<?php echo $idtweet?>'">
            <div class="card-header">
                <?php echo $username ." - ". $judultweet ?>
            </div>
            <div class="card-body">
                <p><?php echo $isitweet ?></p>
            </div>
        </div>
    <?php } } 
        $tag = $_POST['tag'];
        $sql = "SELECT * FROM comments WHERE tags LIKE '%$tag%'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_num_rows($result);

        while ($roww = mysqli_fetch_assoc($result)) {
            $idcomment = $roww['idcomment'];
            $isicomment = $roww['isicomment']; ?>
            <div class="card" style="cursor: pointer; margin-bottom: 20px;" onclick="location.href = 'detailtweet.php?id=<?php echo $idtweet?>'">
            <div class="card-header">
                <?php echo $idcomment ?>
            </div>
            <div class="card-body">
                <p><?php echo $isicomment ?></p>
            </div>
        </div>
        <?php } }?>
    </div>
</body>
</html>