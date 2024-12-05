<?php
session_start();
$cssfile = "home.css";
include 'header.php';

$search = '';
?>

<form method="POST" action="">
    <input type="text" id="search" name="search" placeholder="Search for items" required value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search" name="search_button" id="button">
</form>

<?php
$mysql_servername = 'localhost';
$mysql_username = "root";
$mysql_password = "";
$mysql_dbname = "likeadb";
$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or 
die("Connection failed: %s\n". $conn -> error);
$get_type = $conn->query("SELECT DISTINCT type FROM item");
$get_material = $conn->query("SELECT DISTINCT material FROM item");
$get_brand = $conn->query("SELECT DISTINCT brand FROM item");
$get_year = $conn->query("SELECT DISTINCT year FROM item");
?>

<form method="POST" action="">
    Filter by:
    <select name="type" id="type">
        <option value='none'>Category</option>
        <?php
            // type dropdown
            while ($row = $get_type -> fetch_assoc()) {
                $type = $row['type'];
                echo "<option value='$type'>$type</option>";
            }
        ?>
    </select>
    <select name="material" id="material">
        <option value='none'>Material</option>
        <?php
            // material dropdown
            while ($row = $get_material -> fetch_assoc()) {
                $material = $row['material'];
                echo "<option value='$material'>$material</option>";
            }
        ?>
    </select>
    <select name="brand" id="brand">
        <option value='none'>Brand</option>
        <?php
            // brand dropdown
            while ($row = $get_brand -> fetch_assoc()) {
                $brand = $row['brand'];
                echo "<option value='$brand'>$brand</option>";
            }
        ?>
    </select>
    <select name="year" id="year">
        <option value='none'>Year of make</option>
        <?php
            // year dropdown
            while ($row = $get_year -> fetch_assoc()) {
                $year = $row['year'];
                echo "<option value='$year'>$year</option>";
            }
        ?>
    </select>
</form>
