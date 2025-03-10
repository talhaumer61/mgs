<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username       = isset($_POST['username'])     ? $_POST['username']    : '';
    $phone          = isset($_POST['phone'])        ? $_POST['phone']       : '';
    $discipline     = isset($_POST['discipline'])   ? $_POST['discipline']  : '';
    $_SESSION['user_data'] = [
        'name'          => $username,
        'phone'         => $phone,
        'discipline'    => $discipline,
    ];
    echo 'User data stored in session.';
} else {
    echo 'Invalid request method.';
}
?>