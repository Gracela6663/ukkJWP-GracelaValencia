<?php

    session_start();
    include 'conn.php';

    // cek apakah user sudah login
    if(!isset($_SESSION['login'])) {
        header('location: login.php');
    } else {
        // mengambil username dan userid
        $username = $_SESSION['username'];
        $userid = $_SESSION['userid'];
    }

    // mengambil tweet id
    $idtweet = $_GET['id'];
    $sql = "SELECT * FROM tweets WHERE idtweet=$idtweet";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $useridtwt = $row['userid'];
    $result3 = mysqli_query($conn, "SELECT * FROM users WHERE userid=$useridtwt");
    $row3 = mysqli_fetch_assoc($result3);
    $username2 = $row3['username'];
    $judultweet = $row['judultweet'];
    $isitweet = $row['isitweet'];

    // cek apakah tombol submit sudah ditekan
    if(isset($_POST['submit'])) {
        $isikomen = $_POST['isikomen'];
        
        // mengambil tags dari isikomen
        $tags = explode("#", $isikomen);
        unset($tags[0]);
        $tags = implode(',', $tags); 

        // masukkan data ke dalam database
        $sql = "INSERT INTO comments VALUES ('', '$isikomen', $userid, $idtweet, '$tags')";
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
    }

    if(isset($_POST['submitedit'])) {
        $isikomen = $_POST['isikomen'];
        $idcomment = $_GET['idkom'];
        
        // mengambil tags dari isikomen
        $tags = explode("#", $isikomen);
        unset($tags[0]);
        $tags = implode(',', $tags); 

        // update data dalam database
        $sql = "UPDATE comments SET isicomment='$isikomen', tags='$tags' WHERE idcomment=$idcomment";
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

        header('location: detailtweet.php?id='.$idtweet);
    }

    // jika terdapat idkomentar di url baru bisa melakukan edit komen
    if(isset($_GET['action']) && $_GET['action'] == "edit") {
        $idcomment = $_GET['idkom'];
        $sql = "SELECT * FROM comments WHERE idcomment=$idcomment";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $isicomment = $row['isicomment'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambahtweet.php">Tambah tweet</a>
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

    <div class="container" style="margin-top: 50px;">
        <div class="card" style="cursor: pointer; margin-bottom: 20px;">
            <div class="card-header">
                <?php echo $username2 ." - ". $judultweet ?>
            </div>
            <div class="card-body">
                <p><?php echo $isitweet ?></p>
            </div>

            <div class="card-body">
                <p style="font-weight: bold; margin-bottom: 0;">Komentar</p>

            <?php
                // mengambil data dari database
                $sql = "SELECT * FROM comments WHERE idtweet=$idtweet";
                $result = mysqli_query($conn, $sql);
                $i = 1; 

                while ($row = mysqli_fetch_assoc($result)) {
                    $idcomment = $row['idcomment'];
                    $user = $row['userid'];
                    $isicomment = $row['isicomment'];

                    $result2 = mysqli_query($conn, "SELECT * FROM users WHERE userid=$user");
                    $row2 = mysqli_fetch_assoc($result2);
                    $username = $row2['username'];
            ?>
                <hr>
                <p style="font-weight: bold"><?php echo $username ?></p>
                <p><?php echo $isicomment ?></p>
                <?php
                // Jika komentar dipost oleh user maka user bisa melaukan edit dan hapus
                    if($user == $userid) { ?>
                        <a href="detailtweet.php?id=<?php echo $idtweet?>&action=edit&idkom=<?php echo $idcomment ?>">Edit Komentar</a> |
                        <a href="delete.php?jenis=komen&id=<?php echo $idcomment ?>&idtwt=<?php echo $idtweet?>">Hapus Komentar</a>
                    <?php } $i++; } ?>
            </div>
        </div>
        <?php
        // jika terdapat action = edit di url maka ditampikan edit komentar
            if(isset($_GET['action']) && $_GET['action'] == "edit") { ?>
                <form class="" method="POST">
                    <div class="mb-3">
                        <label for="isitweet" class="form-label">Ubah Komentar</label>
                        <textarea class="form-control" name="isikomen" id="isikomen" cols="30" rows="6" maxlength="250"><?php echo $isicomment ?></textarea>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit" name="submitedit">Ubah Komentar</button>
                    </div>
            </form>
        <!-- Jika tidak terdapat action = edit di url maka ditampikan tambah komentar -->
           <?php } else { ?>
            <form class="" method="POST">
                <div class="mb-3">
                    <label for="isitweet" class="form-label">Tambah Komentar</label>
                    <textarea class="form-control" name="isikomen" id="isikomen" cols="30" rows="6" maxlength="250"></textarea>
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary" type="submit" name="submit">Tambah Komentar</button>
                </div>
            </form>
        <?php } ?>
    </div>
</body>
</html>