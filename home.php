<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
        <link href="css/home.css" rel="stylesheet">
    </head>

<div id="navbar">
    <?php

    $title = "LIkea";

    echo "<div id='title'> $title </div>";

    ?>
    <a href="/furniture">Furniture</a>
    <div id="user">
        <?php
            if (True) {
                $username = "Foo Bar";
                echo "<a href='/profile'> $username </a>";
                echo "<a href='/logout'> Logout </a>";
            } else {
                echo "<a href='/login'>Sign up/Login</a>";
            }
        ?>
    </div>
</div>

</html>
