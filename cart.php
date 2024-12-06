<?php
session_start();
$cssfile = "cart.css";
include 'header.php';

if (!isset($_SESSION['id'])) {
    echo "<p>You must be logged in to view your cart.</p>";
    exit;
}

$mysql_servername = 'localhost';
$mysql_username = "root";
$mysql_password = "";
$mysql_dbname = "likeadb";
$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_dbname) or die("Connection failed: %s\n". $conn -> error);

$userID = $_SESSION['id'];

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

if (isset($_POST['clear_cart'])) {
    $clearQuery = "DELETE FROM cart WHERE userID = $userID";
    $conn->query($clearQuery);
}

$itemQuery = <<<EOT
SELECT 
    i.itemID,
    i.name AS item_name, 
    c.quantity AS item_quantity, 
    ROUND(i.price * i.sale, 2) AS sale_price,
    i.price AS originalPrice,
    i.sale,
    i.brand,
    i.year,
    i.material
FROM 
    cart c
JOIN 
    item i ON c.itemID = i.itemID
WHERE 
    c.userID = $userID
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

            echo "<form method='POST' action='cart.php' style='display: inline;'>";
            echo "Quantity: <input type='number' name='quantity' value='".$row['item_quantity']."' min='1' required style='width: 50px; padding: 5px;'>";
            echo "<input type='hidden' name='itemID' value='".$row['itemID']."'>";
            echo "<button type='submit' name='update_quantity' id='button' style='padding: 5px 10px;'>Update</button>";
            echo "</form>";

            echo "<div id='price'>";
            if ($row['sale'] <= 0.99) {
                echo "<span id='saleP'>".((1-$row['sale'])*100)."% Off!</span><br>";
                echo "<b id='sale'>Now $".$row['sale_price']."</b><br><s>$".$row['originalPrice']."</s><br>";
            } else {
                echo "<br><br><b>$".$row['originalPrice']."</b>";
            }
            echo "</div>";
            echo "<form method='POST' action='cart.php'>";
            echo "<input type='hidden' name='itemID' value='".$row['itemID']."'>";
            echo "<button type='submit' name='remove_item' id='button'>Remove from cart</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>Your cart is empty.</p>";
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
