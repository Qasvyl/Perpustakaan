<?php
    //deklarasi variabel
    $hostname = "localhost";
    $username = "root"; // secara default username adaah root
    $password = "";
    $db_name = "dbperpustakaan"; // sesuaikan dengan nama db yang dibuat

    //menghubungkan ke database 
    $koneksi = mysqli_connect($hostname, $username, $password, $db_name);
    //cek koneksi ke database berhasil atau tidak 
    if(!$koneksi){
        die("koneksi database database gagal: ". mysqli_connect_error());
    }
?>