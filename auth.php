<?php
function cekLogin() {
    if (!isset($_SESSION['Username'])) {
        header('Location: login.php');
        exit();
    }
}

function cekAdmin() {
    if ($_SESSION['Role'] !== 'Admin') {
        header('Location: index.php');
        exit();
    }
}