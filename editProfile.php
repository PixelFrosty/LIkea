<?php
session_start();
$cssfile = 'editProfile.css';
include 'header.php';

if (!isset($_SESSION['id'])) {
    header("Location: home.php");
    exit;
}

$userID = $_SESSION['id'];
$mysql_servername = 'localhost';
$mysql_username = "root";
$mysql_password = "";
$mysql_dbname = "likeadb";

$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$get_user = "SELECT name, email, password, phone, userID, regionID FROM user WHERE userID='$userID'";
$result = $conn->query($get_user);
$row = $result->fetch_assoc();

if ($row) {
    $name = $row['name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $password = $row['password'];
    $regionID = $row['regionID'];
} else {
    echo "User data not found.";
    exit;
}

$getRegion = "SELECT regionID, location FROM region";
$result2 = $conn->query($getRegion);

$regions = [];
while ($row2 = $result2->fetch_assoc()) {
    $regions[$row2['regionID']] = $row2['location'];
}

$successMessage = "";
$errorMessage = "";
if (isset($_POST['make_changes'])) {

    $get_user = "SELECT password FROM user WHERE email='$email'";
    $result = $conn->query($get_user);
    $validOldPasswordResult = $result->fetch_assoc();
    $validOldPassword = $validOldPasswordResult['password'];

    $oldPassword = $conn->real_escape_string($_POST['ogpassword']);
    
    if (password_verify($oldPassword, $validOldPassword)) {
        $newName = $conn->real_escape_string($_POST['name']);
        $newEmail = $conn->real_escape_string($_POST['email']);
        $newPhone = $conn->real_escape_string($_POST['phone']);
        $newRegion = $conn->real_escape_string($_POST['region']);

        $_SESSION['email'] = $newEmail;
        $_SESSION['username'] = $newName;
        $_SESSION['phone'] = $newPhone;
        $_SESSION['region'] = $newRegion;

        // Check if phone is empty, if so, set it to null (or keep the existing value)
        $phoneUpdatePart = !empty($newPhone) ? "phone='$newPhone'" : "";

        if (!array_key_exists($newRegion, $regions)) {
            $errorMessage = "Invalid region selected.";
        } else {
            $passwordUpdatePart = '';
            if (!empty($_POST['password'])) {
                $newPassword = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
                $passwordUpdatePart = "password='$newPassword'";
            }

            // Build the update query with or without the phone update part
            $updateQuery = "UPDATE user SET name='$newName', email='$newEmail', regionID='$newRegion'";

            if ($phoneUpdatePart) {
                $updateQuery .= ", $phoneUpdatePart";
            }

            if ($passwordUpdatePart) {
                $updateQuery .= ", " . $passwordUpdatePart;
            }

            $updateQuery .= " WHERE userID='$userID'";

            if ($conn->query($updateQuery) === TRUE) {
                $successMessage = "Profile updated successfully!";
            } else {
                $errorMessage = "Error updating profile: " . $conn->error;
            }
        }
    } else {
        $errorMessage = "Password was incorrect" . $conn->error;
    }
}

$conn->close();
?>

<div id="container">
    <div id="login">

        <h4 id="notice">Change Account Details</h4>

        <form method="POST" action="">
            <label for="name">Name (leave empty to keep current):</label>
            <input type="text" id="name" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>">
            <br>

            <label for="email">Email (leave empty to keep current):</label>
            <input type="text" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
            <br>

            <label for="phone">Phone (leave empty to keep current):</label>
            <input type="text" id="phone" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($phone); ?>">
            <br>

            <label for="password">New Password (leave empty to keep current):</label>
            <input type="password" id="password" name="password" placeholder="New Password">
            <br>

            <label for="ogpassword">Old Password (Required):</label>
            <input type="password" id="ogpassword" required name="ogpassword" placeholder="Password">
            <br>

            <label for="region">Select Region:</label>
            <select id="region" name="region" required>
                <option value="" disabled selected>Select Region</option>
                <?php
                foreach ($regions as $regionID => $region) {
                    $selected = ($regionID == $regionID) ? 'selected' : '';
                    echo "<option value=\"$regionID\" $selected>$region</option>";
                }
                ?>
            </select>
            <br>

            <input type="submit" value="Apply Changes" name="make_changes" id="button">
        </form>

        <?php
        if ($successMessage) {
            echo "<p style='color:green;' id='success'>$successMessage</p>";
        }

        if ($errorMessage) {
            echo "<p style='color:red;' id='error'>$errorMessage</p>";
        }
        ?>
    </div>
</div>
