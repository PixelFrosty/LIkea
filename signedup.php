<?php
session_start();
$cssfile = 'signedUp.css';
include 'header.php';

if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}
?>

<div id="container">
    <div id="message-box">
        <div>Signup Successful!</div>
        <a href="login.php">Login with your new account to continue.</a>
    </div>
</div>
