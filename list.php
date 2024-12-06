<?php
session_start();
$cssfile = "cart.css";
include 'header.php';

if (!isset($_SESSION['id'])) {
    echo "<p>You must be logged in to view your lists.</p>";
    exit;
}

$mysql_servername = 'localhost';
$mysql_username = "root";
$mysql_password = "";
$mysql_dbname = "likeadb";
$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or die("Connection failed: %s\n". $conn -> error);

$userID = $_SESSION['id'];
$listID = $_SESSION['listID'];

if (isset($_POST['update_quantity'])) {
    $itemID = $_POST['itemID'];
    $newQuantity = $_POST['quantity'];
    $updateQuery = "UPDATE cart SET quantity = $newQuantity WHERE userID = $userID AND itemID = $itemID";
    $conn->query($updateQuery);
}

if (isset($_POST['remove_item'])) {
    $itemID = $_POST['itemID'];
    $deleteQuery = "DELETE FROM cart WHERE userID = $userID AND itemID = $itemID";
    $conn->query($deleteQuery);
}

if (isset($_POST['clear_list'])) {
    $clearQuery = "DELETE FROM cart WHERE userID = $userID";
    $conn->query($clearQuery);
}

$itemQuery = <<<EOT
SELECT * FROM
    (SELECT 
    -- View all items in a particular list
        i.name AS ItemName,
        il.Quantity,
        l.time AS TimeCreated
    FROM 
        list l
    JOIN 
        inlist il ON l.listID = il.listID
    JOIN 
        item i ON il.itemID = i.itemID
    WHERE 
        l.listID = <listID> 
        AND l.userID = $userID
        AND l.listName = <listName>) AS listContents;
EOT;

$items = $conn->query($itemQuery);
$totalPrice = 0;
?>

<div class='ch_container'>
    <h2 class='center-header'>Your cart</h2>
</div>

<div id="item_list">
    <?php
    if ($items->num_rows > 0) {
        while ($row = $items->fetch_assoc()) {
            $itemTotalPrice = $row['sale_price'] * $row['item_quantity'];
            $totalPrice += $itemTotalPrice;

            echo "<div id='item'>";
            echo "<h3>".$row['item_name']."</h3>";
            echo "<h4>".$row['brand']."</h4>";
            echo $row['year']."<br>";
            echo "Made from ".$row['material']."<br>";

            echo "<form method='POST' action='list.php' style='display: inline;'>";
            echo "Quantity: <input type='number' name='quantity' value='".$row['item_quantity']."' min='1' required style='width: 50px; padding: 5px;'>";
            echo "<input type='hidden' name='itemID' value='".$row['itemID']."'>";
            echo "<button type='submit' name='update_quantity' id='button' style='padding: 5px 10px;'>Update</button>";
            echo "</form>";

            echo "<div id='price'>";
            if ($row['sale'] < 1) {
                echo "<b id='sale'>$".$row['sale_price']."</b><br><s>$".$row['originalPrice']."</s><br>";
            } else {
                echo "<br><b>$".$row['originalPrice']."</b>";
            }
            echo "</div>";
            echo "<form method='POST' action='list.php'>";
            echo "<input type='hidden' name='itemID' value='".$row['itemID']."'>";
            echo "<button type='submit' name='remove_item' id='button'>Remove from list</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>This list is empty.</p>";
    }
    ?>
</div>
<div class='totalPrice_container'>
    <div id="total_price">
        <b>Total: $<?php echo number_format($totalPrice, 2); ?></b>
    </div>
</div>
<form method="POST" action="cart.php">
    <button type="submit" name="clear_cart" id="clear_cart_button">Clear Cart</button>
</form>
