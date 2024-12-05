<?php
session_start();
include 'header.php';

if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}
?>

<div>Signup Successful!</div>
<a href="login.php">Login with your new account to continue.</a>
