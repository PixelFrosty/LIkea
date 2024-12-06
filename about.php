<?php
session_start();
$cssfile = 'about.css';
include 'header.php';

$mysql_servername = 'localhost';
$mysql_username = "root";
$mysql_password = "";
$mysql_dbname = "likeadb";

$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$getRegions = "SELECT r.regionID, r.location, b.branchPhoneNumber, b.branchAddr 
               FROM region r
               LEFT JOIN branch b ON r.regionID = b.regionID";
$result = $conn->query($getRegions);

$regions = [];
while ($row = $result->fetch_assoc()) {
    $regions[] = $row;
}

$conn->close();
?>

<div id="container">
    <div id="about">

        <h4 id="notice">About Us</h4>
        <?php if (!empty($regions)): ?>
            <form method="POST" action="">
                <select id="region" name="region" required>
                    <option value="" disabled selected>Select Region</option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?php echo htmlspecialchars($region['regionID']); ?>"><?php echo htmlspecialchars($region['location']); ?></option>
                    <?php endforeach; ?>
                </select>
                <br>

                <input type="submit" value="Show Branch" name="select_region" id="button">
            </form>

            <?php 
            if (isset($_POST['region'])):
                $selectedRegionID = $_POST['region'];
                $selectedRegionInfo = array_filter($regions, function ($region) use ($selectedRegionID) {
                    return $region['regionID'] == $selectedRegionID;
                });
                $selectedRegionInfo = reset($selectedRegionInfo);
            ?>
                <h4>Branch Information</h4>
                <p><strong>Region:</strong> <?php echo htmlspecialchars($selectedRegionInfo['location']); ?></p>
                <p><strong>Branch Phone:</strong> <?php echo htmlspecialchars($selectedRegionInfo['branchPhoneNumber']); ?></p>
                <p><strong>Branch Address:</strong> <?php echo htmlspecialchars($selectedRegionInfo['branchAddr']); ?></p>
            <?php endif; ?>

        <?php else: ?>
            <p>No regions or branch information found.</p>
        <?php endif; ?>

    </div>
</div>
