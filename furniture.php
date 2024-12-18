<?php
session_start();
$cssfile = "browser.css";
include 'header.php';

$selectedRegionID = null;

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
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

// bind_param() variables
$searchParams = [];
$searchTypes = "";

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

$itemQuery = <<<'EOT'
SELECT *
FROM (
    SELECT 
        i.itemID AS itemID,
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

$search = '';
$type = '';
$material = '';
$brand = '';
$year = '';
$sale = 0;
$region = 'all';

if (isset($_POST['search']) && $_POST['search'] != 'none') {
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
if (isset($_SESSION['id'])) {
    $region = $_SESSION['regionID'];
}
if (isset($_POST['region']) && $_POST['region'] != 'none') {
    $region = $_POST['region'];
}
if (isset($_POST['sale'])) {
    $sale = 1;
}

if (!empty($search)) {
    $itemQuery .= " AND i.name LIKE ?";
    $countQuery .= " AND i.name LIKE ?";
    $searchParams[] .= "%$search%";
    $searchTypes .= "s";
}

if (!empty($type)) {
    $itemQuery .= " AND i.type = ?";
    $countQuery .= " AND i.type = ?";
    $searchParams[] .= "$type";
    $searchTypes .= "s";
}

if (!empty($material)) {
    $itemQuery .= " AND i.material = ?";
    $countQuery .= " AND i.material = ?";
    $searchParams[] .= "$material";
    $searchTypes .= "s";
}

if (!empty($brand)) {
    $itemQuery .= " AND i.brand = ?";
    $countQuery .= " AND i.brand = ?";
    $searchParams[] .= "$brand";
    $searchTypes .= "s";
}

if (!empty($year)) {
    $itemQuery .= " AND i.year = ?";
    $countQuery .= " AND i.year = ?";
    $searchParams[] .= "$year";
    $searchTypes .= "s";
}

if ($sale == 1) {
    $itemQuery .= " AND i.sale <= 0.99";
    $countQuery .= " AND i.sale <= 0.99";
}

if ($region !== 'all') {
    $itemQuery .= " AND r.regionID = ?";
    $countQuery .= " AND r.regionID = ?";
    $searchParams[] .= "$region";
    $searchTypes .= "s";
}

$itemQuery .= ") AS furnitureAvailableInRegion;";

$stmt = $conn->prepare($itemQuery);
if (count($searchParams) > 0) {
    $stmt->bind_param($searchTypes, ...$searchParams);
}
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();

$stmt = $conn->prepare($countQuery);
if (count($searchParams) > 0) {
    $stmt->bind_param($searchTypes, ...$searchParams);
}
$stmt->execute();
$count = $stmt->get_result();
$stmt->close();

$countRow = $count->fetch_assoc();
$itemCount = $countRow['items'];

if (!isset($_SESSION['id'])) {
    echo "<div id=reminder>Log in to start purchasing items!</div>";
}
?>

<div id="search-form">
<form id="search_filters" method="POST" action="">
    <input type="text" id="search" name="search" placeholder="Search for items" value="<?php echo htmlspecialchars($search); ?>" />
    <input type="submit" value="Search" name="search_button" id="button">
</div>
<div id="filter-form">
    <select name="type" id="type">
        <option value='none'>Category</option>
        <?php while ($row = $get_type->fetch_assoc()) {
        echo "<option"; 
        if (isset($type) && $row['type'] == $type) {echo " selected";}
        echo " value='{$row['type']}'>{$row['type']}</option>"; } ?>
    </select>
    <select name="material" id="material">
        <option value='none'>Material</option>
        <?php while ($row = $get_material->fetch_assoc()) {
        echo "<option"; 
        if (isset($material) && $row['material'] == $material) {echo " selected";}
        echo " value='{$row['material']}'>{$row['material']}</option>"; } ?>
    </select>
    <select name="brand" id="brand">
        <option value='none'>Brand</option>
        <?php while ($row = $get_brand->fetch_assoc()) {
        echo "<option"; 
        if (isset($brand) && $row['brand'] == $brand) {echo " selected";}
        echo " value='{$row['brand']}'>{$row['brand']}</option>"; } ?>
    </select>
    <select name="year" id="year">
        <option value='none'>Year of make</option>
        <?php while ($row = $get_year->fetch_assoc()) {
        echo "<option"; 
        if (isset($year) && $row['year'] == $year) {echo " selected";}
        echo " value='{$row['year']}'>{$row['year']}</option>"; } ?>
    </select>

    <select name="region" id="region">
        <option value="all" <?php echo ($region == 'all') ? 'selected' : ''; ?>>All Regions</option>
        <?php 
        while ($row = $get_regions->fetch_assoc()) {
            $selected = (isset($region) && $row['regionID'] == $region) ? 'selected' : '';
            echo "<option value='{$row['regionID']}' {$selected}>{$row['location']}</option>";
        }
        ?>
    </select>
    
    <label>
        <input type="checkbox" value="sale" id="sale" name="sale" <?php if ($sale == 1) {echo "checked";}?> >On Sale
    </label>

    <input type="submit" value="Apply filters" class="apply" name="apply_filter" id="button">
</form>
</div>

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
        if ($row['sale'] <= 0.99) {
            echo "<span id='saleP'>".((1-$row['sale'])*100)."% Off!</span><br>";
            echo "<b id='sale'>Now $".$row['salePrice']."</b><br><s>$".$row['originalPrice']."</s><br>";
        } else {
            echo "<br><br><b>$".$row['originalPrice']."</b>";
        }
        echo "</div>";

            if (isset($_SESSION['id'])) {
                $quantity = 0;
                $res = $conn->query("SELECT quantity FROM cart WHERE itemID = {$row['itemID']} and userID = {$_SESSION['id']}");
                $quantityRow = $res->fetch_assoc();
                if (isset($quantityRow)) { $quantity = $quantityRow['quantity']; }
                if ($quantity > 0) { $cartText = "$quantity in cart.";}
                else { $cartText = "Add to Cart";}
                echo <<<EOT
                <div id='purchase'>
                    <form action="" method="post">
                        <input type="hidden" name="itemID" id="itemID" value={$row['itemID']}>
                        <button type="submit" name="cart" id="cart">$cartText</button>
                    </form>
                </div>
                EOT;
            }

        echo "</div>";
    }

    if (isset($_POST['cart'])) {
        $cartQuery = <<<EOT
        INSERT INTO cart (userID, itemID, quantity)
        VALUES ({$_SESSION['id']}, {$_POST['itemID']}, 1)
        ON DUPLICATE KEY UPDATE 
            quantity = quantity + 1;
        EOT;
        $conn->query($cartQuery);
    }
    
    ?>
</div>
