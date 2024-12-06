<?php
session_start();
$cssfile = "browser.css";
include 'header.php';

$loggedIn = False;
$selectedRegionID = null;  // Variable to hold the selected region ID

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $loggedIn = True;
    $selectedRegionID = $_SESSION['regionID'];
}

$mysql_servername = 'localhost';
$mysql_username = "root";
$mysql_password = "";
$mysql_dbname = "likeadb";
$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or die("Connection failed: %s\n". $conn -> error);

// Get filters (for dropdown options)
$get_type = $conn->query("SELECT DISTINCT type FROM item");
$get_material = $conn->query("SELECT DISTINCT material FROM item");
$get_brand = $conn->query("SELECT DISTINCT brand FROM item");
$get_year = $conn->query("SELECT DISTINCT year FROM item");
$get_regions = $conn->query("SELECT regionID, location FROM region");

// Count item query
$countQuery = <<<'EOT'
SELECT
    COUNT(*) as items
FROM
    item i
JOIN
    branch b ON i.branchID = b.branchID
JOIN
    region r ON b.regionID = r.regionID
WHERE true
EOT;

// Base item query
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
        i.sale,
        r.location AS region
    FROM 
        item i
    JOIN 
        branch b ON i.branchID = b.branchID
    JOIN 
        region r ON b.regionID = r.regionID
    WHERE true
EOT;

// Handle search and filters
$search = '';
$type = '';
$material = '';
$brand = '';
$year = '';
if (isset($_SESSION['id'])) {
    $region = $_SESSION['regionID'];
} else {
    $region = 'all';
}

if (isset($_POST['search_button'])) {
    $search = $_POST['search'];
}
if (isset($_POST['type']) && $_POST['type'] != 'none') {
    $type = $_POST['type'];
}
if (isset($_POST['material']) && $_POST['material'] != 'none') {
    $material = $_POST['material'];
}
if (isset($_POST['brand']) && $_POST['brand'] != 'none') {
    $brand = $_POST['brand'];
}
if (isset($_POST['year']) && $_POST['year'] != 'none') {
    $year = $_POST['year'];
}
if (isset($_POST['region']) && $_POST['region'] != 'none') {
    $region = $_POST['region'];  // User selected region
}

// Update query based on filters
if (!empty($search)) {
    $itemQuery .= " AND i.name LIKE '%$search%'";
    $countQuery .= " AND i.name LIKE '%$search%'";
}

if (!empty($type)) {
    $itemQuery .= " AND i.type = '$type'";
    $countQuery .= " AND i.type = '$type'";
}

if (!empty($material)) {
    $itemQuery .= " AND i.material = '$material'";
    $countQuery .= " AND i.material = '$material'";
}

if (!empty($brand)) {
    $itemQuery .= " AND i.brand = '$brand'";
    $countQuery .= " AND i.brand = '$brand'";
}

if (!empty($year)) {
    $itemQuery .= " AND i.year = '$year'";
    $countQuery .= " AND i.year = '$year'";
}

if ($region !== 'all') {
    $itemQuery .= " AND r.regionID = '$region'";
    $countQuery .= " AND r.regionID = '$region'";
}

$itemQuery .= ") AS furnitureAvailableInRegion;";

$items = $conn->query($itemQuery);
$count = $conn->query($countQuery);
$countRes = $count->fetch_assoc();
$itemCount = $countRes['items'];

?>

<form id="search-form" method="POST" action="">
    <input type="text" id="search" name="search" placeholder="Search for items" value="<?php echo htmlspecialchars($search); ?>" />
    <input type="submit" value="Search" name="search_button" id="button">
</form>

<form id="filter-form" method="POST" action="">
    Filter by:
    <select name="type" id="type">
        <option value="none">Category</option>
        <?php while ($row = $get_type->fetch_assoc()) { echo "<option value='{$row['type']}'>{$row['type']}</option>"; } ?>
    </select>
    <select name="material" id="material">
        <option value="none">Material</option>
        <?php while ($row = $get_material->fetch_assoc()) { echo "<option value='{$row['material']}'>{$row['material']}</option>"; } ?>
    </select>
    <select name="brand" id="brand">
        <option value="none">Brand</option>
        <?php while ($row = $get_brand->fetch_assoc()) { echo "<option value='{$row['brand']}'>{$row['brand']}</option>"; } ?>
    </select>
    <select name="year" id="year">
        <option value="none">Year of make</option>
        <?php while ($row = $get_year->fetch_assoc()) { echo "<option value='{$row['year']}'>{$row['year']}</option>"; } ?>
    </select>

    <select name="region" id="region">
        <option value="all" <?php echo ($region == 'all') ? 'selected' : ''; ?>>All Regions</option>
        <?php 
        while ($row = $get_regions->fetch_assoc()) {
            $selected = ($row['regionID'] == $selectedRegionID) ? 'selected' : '';
            echo "<option value='{$row['regionID']}' {$selected}>{$row['location']}</option>";
        }
        ?>
    </select>

<!-- TODO: ADD FILTER SALES? -->
    
    <input type="submit" value="Apply filters" name="apply_filter" id="button">
</form>

<div id="count">Amount of items: <?php echo $itemCount; ?></div>

<div id="item_list">
    <?php
    while ($row = $items->fetch_assoc()) {
        echo "<div id='item'>";
        echo "<h3>".$row['item_name']."</h3>";
        echo "<h4>".$row['brand']."</h4>";
        echo $row['year']."<br>";
        echo "Made from ".$row['material'];
        echo "<div id='price'>";
        if ($row['sale'] <= 0.09) {
            echo "<b id='sale'>$".$row['salePrice']."</b><br><s>$".$row['originalPrice']."</s><br>";
        } else {
            echo "<br><b>$".$row['originalPrice']."</b>";
        }
        echo "</div></div>";
    }
    ?>
</div>
