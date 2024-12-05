<?php
session_start();
$cssfile = 'login.css';
include 'header.php';

// incase user uses link to login, but is already logged in
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}

    if (isset($_POST['login_request'])) {
        
        $mysql_servername = 'localhost';
        $mysql_username = "root";
        $mysql_password = "";
        $mysql_dbname = "likeadb";
        $conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or 
        die("Connection failed: %s\n". $conn -> error);
        
        $email = $_POST['email'];
        $password = $_POST['password'];

        $get_user = "SELECT name, email, password, ID, regionID FROM user WHERE email='$email'";
        $result = $conn->query($get_user);
        $row = $result->fetch_assoc();

    if (isset($row['email']) && $email === $row['email'] && password_verify($password, $row['password'])){
        // TODO: placeholder until actual db setup
        $_SESSION['email'] = $row['email'];
        $_SESSION['username'] = $row['name'];
        $_SESSION['id'] = $row['ID'];
        $_SESSION['date'] = $row['date'];
        $_SESSION['region'] = $row['regionID'];

        header("Location: home.php");
        exit;
    } else {
        // set local variable if invalid login attempt
        $error = 'Invalid email or password.';
    }
}
?>

<div id="container">
    <div id="login">

        <h1 id="notice">Likea</h1>

        <form method="POST" action="">
            <label for="email">Email:</label> <br>
            <input type="text" id="email" name="email" required> <br> <br>
            <label for="password">Password:</label> <br>
            <input type="password" id="password" name="password" required> <br> <br>
            <input type="submit" value="Login" name="login_request">
        </form>
        <br>
        <a href="signup.php">No account? Sign up now!</a>

        <?php
        if (isset($error)) {
            echo "<br>";
            echo "<br>";
            echo "<p style='color:red;'>$error</p>";
        }
        ?>
    </div>
</div>
