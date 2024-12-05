<?php
session_start();
include 'header.php';

// incase user uses link to login, but is already logged in
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}

    if (isset($_POST['signup_request'])) {
        
        $mysql_servername = 'localhost';
        $mysql_username = "root";
        $mysql_password = "";
        $mysql_dbname = "likeadb";
        $conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or 
        die("Connection failed: %s\n". $conn -> error);
        
        $name = $_POST['name'];
        $email = $_POST['email'];
        if ($_POST['phone'] == ''){
            $phone = 'NULL';
        } else {
            $temp = $_POST['phone'];
            $phone = "'$temp'";
        }
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $regionID = $_POST['region'];

        // testing
        // TODO: QUERY FOR EXISTENCE OF EMAIL
        $get_email = "SELECT email FROM user WHERE email='$email'";
        $result = $conn->query($get_email);
        $row = $result->fetch_assoc();

    if (isset($row['email'])){
        // set local variable if invalid login attempt
        $error = 'Email already in use.';
    } else {
        $user_info = "INSERT INTO user (name, email, phone, password, regionID) VALUES ('$name','$email',$phone,'$password', '$regionID')";

        $result = $conn->query($user_info);

        header("Location: signedup.php");
        exit;
    }
}

?>

<div id="notice">Sign up</div>

<form method="POST" action="">
    <label for="email">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required>
    <br>
    <label for="phone">Phone number:</label>
    <input type="text" id="phone" name="phone">
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <select name="region" id="region">
        <option value='1'>West</option>
        <option value='2'>Mid West</option>
        <option value='3'>South West</option>
        <option value='4'>South East</option>
        <option value='5'>North East</option>
    </select>
    
    <br>
    <input type="submit" value="Sign up!" name="signup_request">
</form>
<a href="login.php">Already have an account? Log in now!</a>

<?php
if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
?>

</html>
