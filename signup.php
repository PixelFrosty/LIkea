<?php
session_start();
$cssfile = 'login.css';
include 'header.php';

// incase user uses link to login, but is already logged in
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}

$name = $email = "";

if (isset($_POST['signup_request'])) {
    
    $mysql_servername = 'localhost';
    $mysql_username = "root";
    $mysql_password = "";
    $mysql_dbname = "likeadb";
    $conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or 
    die("Connection failed: %s\n". $conn -> error);
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $confpassword = $_POST['confpassword'];
    $regionID = $_POST['region'];

    $get_email = "SELECT email FROM user WHERE email='$email'";
    $result = $conn->query($get_email);
    $row = $result->fetch_assoc();

    if ($confpassword !== $_POST['password']) {
        // set local variable if invalid signin attempt
        $error = 'Password does not match';
    } elseif (isset($row['email'])){
        // set local variable if invalid signin attempt
        $error = 'Email already in use.';
    } else {
        $user_info = "INSERT INTO user (name, email, password, regionID) VALUES ('$name','$email', '$password', '$regionID')";

        $result = $conn->query($user_info);

        header("Location: signedup.php");
        exit;
    }
}

?>

<div id="container">
    <div id="login">

        <h4 id="notice">Signup</h4>

        <form method="POST" action="">
            <input type="text" id="name" name="name" placeholder="Name" required value="<?php echo htmlspecialchars($name); ?>">
            <br>
            <input type="text" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>">
            <br>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <br>
            <input type="password" id="confpassword" name="confpassword" placeholder="Confirm Password" required>
            <br>
            <select name="region" id="region">
                <option value='1'>West</option>
                <option value='2'>Mid West</option>
                <option value='3'>South West</option>
                <option value='4'>South East</option>
                <option value='5'>North East</option>
            </select>
            
            <br>
            <input type="submit" value="Sign up!" name="signup_request" id="button">
        </form>

        <?php
        if (isset($error)) {
            echo "<p style='color:red;' id='error'>$error</p>";
        }
        ?>

        <div id="suggestion">
            Already have an account?
            <a href="login.php">Login</a>
        </div>
    </div>
</div>


</html>
