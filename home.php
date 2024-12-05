<?php
session_start();
$cssfile = "home.css";
include 'header.php';
?>

<div id="myBody">
    <div id="welcome">
        <h1>Welcome to <i style="color: #359;">LIkea</i> </h1>
        <h2>Like Ikea, but <u>cheaper</u> and <u>worse</u> </h2>
    </div>

    <div id="browse">
        <h4>Just looking?</h4>
        <br>
        <div id="cont">
            <a href="furniture.php">
                <div id="button">
                    Browse Furniture
                </div>
            </a>
        </div>
    </div>

    <div id="login">
        <h4>Want to start shopping?</h4>
        <br>
        <div id="buttons">
            <div id="cont">
                <a href="login.php">
                    <div id="button1">
                        Log into your account
                    </div>
                </a>
            </div>
            <div id="cont">
                <a href="signup.php">
                    <div id="button2">
                        Make an account
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
