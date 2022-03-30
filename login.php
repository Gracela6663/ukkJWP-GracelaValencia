<?php

    session_start();
    include 'conn.php';

    // cek apakah user sudah login
    if(isset($_SESSION['login'])) {
        header('location: index.php');
    }

    // cek apakah tombol submit sudah ditekan
    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        // menggunakan md5 untuk menyamarkan password
        $password = md5($_POST['password']);
        
        $sql = "SELECT * FROM users where username='$username' AND password='$password'";
        $result =  mysqli_query($conn, $sql);
        $row = mysqli_num_rows($result);
        $row2 = mysqli_fetch_assoc($result);
        $userid = $row2['userid'];

        if($row > 0) {
            // menambahkan session untuk menyatakan bahwa user sudah login
            $_SESSION['login'] = true;
            $_SESSION['userid'] = $userid;
            $_SESSION['username'] = $username;
            header('location: index.php');
        } else {
            echo "<script>alert('Username atau password tidak ditemukan!');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login</title>
</head>
<style>
    .container {
       width: 500px;
       margin-top: 150px;
    }
</style>
<body>
    <div class="container">
        <h1 style="text-align: center;">Login</h1>
        <hr>
        <form class="was-validated" method="POST" id="form_siswa">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control is-valid" id="username" name="username" required autocomplete="off"> 
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control is-valid" id="password" name="password" required>
            </div>

            <p style="text-align: center;">Belum punya akun? <a href="register.php">Register Sekarang</a></p>

            <div class="mb-3" style="text-align: center;">
                <button class="btn btn-primary" type="submit" name="submit">Login Sekarang</button>
            </div>
        </form>
    </div>
</body>
</html>