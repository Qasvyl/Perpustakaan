<?php
session_start();
include "koneksi.php";

//mengecek apakah form login sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //mengambil data dari form
    $Username = $_POST['Username'];
    $Password = md5($_POST['Password']);//menggunakan md5

    // query untuk memeriksa Username dan Password (dengan md5)
    $sql = "SELECT * FROM petugas WHERE Username = ? AND Password = ?";

    // menyiapkan query dengan prepared statement
    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $Username, $Password);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $Role = $row['Role'];

        $_SESSION['Username'] = $Username;
        $_SESSION['Role'] = $Role;

        if ($Role == 'Admin') {
            header('Location: index.php');
            exit;
        } elseif ($Role == 'Petugas') {
            header('Location: index.php');
            exit;
        }
    } else {
        echo "<script> alert('Username atau Password salah'); window.location.href = 'login.php';
        </script>";
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($koneksi);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Zalando+Sans+SemiExpanded:ital,wght@0,200..900;1,200..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <link rel="icon" href="image/Plogo.png" type="image/x-icon" />
</head>

<body>
    

    <div class="container">
        <form class="form" action="login.php" method="POST">
        <div class="side">
            <img src="image/op2.png">
        </div>
        <div class="right"><img src="image/PLogo.png">
            <h1>Login</h1>
            <div class="input-control">
                <label for="Username">Username</label>
                <input type="text" name="Username" id="Username" required>
            </div>
            <div class="input-control">
                <label for="Password">Password</label>
                <input type="Password" name="Password" id="Password" required>
            </div>
            <button type="submit" class="button">Masuk</button>
            </div>
        </form>
    </div>
</body>

</html>