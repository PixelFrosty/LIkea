<?php
session_start();
$cssfile = 'profile.css';
include 'header.php';

// Check if user is logged in, otherwise redirect to home
if (!isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}

// Fetch user data based on the session
$userID = $_SESSION['id'];
$mysql_servername = 'localhost';
$mysql_username = "root";
$mysql_password = "";
$mysql_dbname = "likeadb";

$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or die("Connection failed: %s\n". $conn -> error);

$get_user = "SELECT name, email, password, phone, userID, regionID FROM user WHERE userID='$userID'";
$result = $conn->query($get_user);
$row = $result->fetch_assoc();

if ($row) {
    $name = $row['name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $region = $row['regionID'];
} else {
    echo "User data not found.";
    exit;
}
if($phone == null){
    $phone = "No phone number added";
}
?>

<div id="container">
    <div id="profile">
        <h4>Welcome, <?php echo htmlspecialchars($name); ?>!</h4>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Region ID:</strong> <?php echo htmlspecialchars($region); ?></p>

        <a href="editProfile.php">Edit Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>


