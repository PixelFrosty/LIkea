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
    
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
    $confpassword = $conn->real_escape_string($_POST['confpassword']);
    $regionID = $conn->real_escape_string($_POST['region']);

    $get_email = "SELECT email FROM user WHERE email = ?";
    $stmt = $conn->prepare($get_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();
    $row = $result->fetch_assoc();

    if ($confpassword !== $conn->real_escape_string($_POST['password'])) {
        // set local variable if invalid signin attempt
        $error = 'Password does not match';
    } elseif (isset($row['email'])){
        // set local variable if invalid signin attempt
        $error = 'Email already in use.';
    } else {
        $user_info = "INSERT INTO user (name, email, password, regionID) VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($user_info);
        $stmt->bind_param("ssss", $name, $email, $password, $regionID);
        $stmt->execute();

        $result = $stmt->get_result();

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
            <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>">
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
