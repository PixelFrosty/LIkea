<?php
session_start();
$cssfile = "browser.css";
include 'header.php';

$loggedIn = False;
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $loggedIn = True;
}

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

// to find all existing filters
$get_type = $conn->query("SELECT DISTINCT type FROM item");
$get_material = $conn->query("SELECT DISTINCT material FROM item");
$get_brand = $conn->query("SELECT DISTINCT brand FROM item");
$get_year = $conn->query("SELECT DISTINCT year FROM item");
$itemQuery = <<<'EOT'
SELECT *
FROM (
    SELECT 
        i.itemID,
        i.name AS item_name, 
        i.type AS item_type, 
        i.material, 
        i.brand, 
        i.year, 
        i.price AS originalPrice,
        ROUND(i.price * i.sale, 2) AS salePrice,
        r.location AS region
    FROM 
        item i
    JOIN 
        branch b ON i.branchID = b.branchID
    JOIN 
        region r ON b.regionID = r.regionID
    JOIN 
        user u ON u.regionID = r.regionID
EOT;

if ($loggedIn === True && isset($_POST['search'])) {
    $itemQuery .= "WHERE";
    if ($loggedIn === True) {
        $itemQuery .= "u.userID = $id";
    }
    if (isset($search)) {
        $itemQuery .= "AND i.name = $search";
    }
}

$itemQuery .= ") AS furnitureAvailableInRegion;";

$items = $conn->query($itemQuery);
$count = $conn->query("SELECT COUNT(*) as items FROM item");

if (isset($_POST['search'])) {
    $search = $_POST['search'];
}
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
<input type="submit" value="Apply filters" name="apply_filter" id="button">
</form>

<?php
$countRes = $count -> fetch_assoc();
$itemCount = $countRes['items'];
echo "<div id='count'> Amount of items: $itemCount </div>";

echo "<div id='item_list'>";
while ($row = $items -> fetch_assoc()) {
    echo "<div id='item'>"
."<h3>".$row['item_name']."</h3>"
."<h4>".$row['brand']."</h4>"
.$row['item_type']."<br>"
.$row['material']."<br>"
.$row['year']."<br>"
.$row['originalPrice']."<br>"
.$row['salePrice']."<br>"
."</div>";
}
echo "</div>";
?>

