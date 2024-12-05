<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
    
    <?php
    if(isset($cssfile)) {
        echo "<link href='css/$cssfile?v=<?php echo time(); ?>' rel='stylesheet'>";
    }
    ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300..700&display=swap" rel="stylesheet">
</head>

<div id="navbar">
    <div id="title">
        <?php

        $title = "LIkea";

        echo "<a href='/home.php' id='title'> $title </a>";

        ?>
    </div>
    <div id="main">
        <a href="/furniture">Furniture</a>
    </div>
    <div id="user">
        <div id="name">
            <?php
                    if (isset($_SESSION['id'])) {
                        $name = $_SESSION['username'];
                        echo "Welcome back <b>$name</b>!";
                    }
            ?>
        </div>
        <div id="manage">
            <?php
                    if (isset($_SESSION['id'])) {
                        echo "<a href='/profile.php'> Manage Account</a>";
                        echo "<a href='/logout.php'> Logout </a>";
                    } else {
                        echo "<a href='/login.php'>Sign up/Login</a>";
                    }
            ?>
        </div>
    </div>
</div>
