<?php

    session_start();
    include 'conn.php';

    // cek apakah user sudah login
    if(!isset($_SESSION['login'])) {
        header('location: login.php');
    } else {
        // mengambil userid dan username
        $userid = $_SESSION['userid'];
        $username = $_SESSION['username'];
    }

    // cek apakah tombol submit sudah ditekan
    if(isset($_POST['submit'])) {
        $judultweet = $_POST['judul'];
        $isitweet = $_POST['isitweet'];
        
        // pisahkan tag dengan isi
        $tags = explode("#", $isitweet);
        unset($tags[0]);
        $tags = implode(',', $tags);
                                                                                                             
        // masukkan data ke dalam users
        $sql = "INSERT INTO tweets VALUES ('', $userid, '$judultweet', '$isitweet', '$tags')";
        mysqli_query($conn, $sql);

        // masukkan data ke dalam tags
        $tags = explode(",", $tags);
        foreach ($tags as $tag) {
            $sql = "SELECT namatag FROM tags WHERE namatag='$tag'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_num_rows($result);
            if($row == 0) {
                $sql = "INSERT INTO tags VALUES ('', '$tag')";
                mysqli_query($conn, $sql);
            }
        };

        header('location: index.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tweet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page">Tambah tweet</a>
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

    <div class="container">
        <h1 style="margin-top: 50px">Tambah Tweet Baru</h1>
        <hr>
        <form class="" method="POST" id="form_siswa">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Tweet</label>
                <input type="text" class="form-control" id="judul" name="judul" required autocomplete="off">
            </div>

            <div class="mb-3">
                <label for="isitweet" class="form-label">Isi Tweet</label>
                <textarea class="form-control" name="isitweet" id="isitweet" cols="30" rows="6" maxlength="250"></textarea>
            </div>

            <div class="mb-3">
                <button class="btn btn-primary" type="submit" name="submit">Upload Tweet</button>
            </div>
        </form>
    </div>
</body>
</html>