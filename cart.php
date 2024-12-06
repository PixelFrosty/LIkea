<?php
session_start();
$cssfile = "browser.css";
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
?>

<div id="item_list">
    <?php
    echo "<h2>Your cart</h2>";
    if ($items->num_rows > 0) {
        while ($row = $items->fetch_assoc()) {
            echo "<div id='item'>";
            echo "<h3>".$row['item_name']."</h3>";
            echo "<h4>".$row['brand']."</h4>";
            echo $row['year']."<br>";
            echo "Made from ".$row['material']."<br>";
            echo "Quantity: ".$row['item_quantity']."<br>";
            echo "<div id='price'>";
            if ($row['sale'] < 1) {
                echo "<b id='sale'>$".$row['sale_price']."</b><br><s>$".$row['originalPrice']."</s><br>";
            } else {
                echo "<br><b>$".$row['originalPrice']."</b>";
            }
            echo "</div>";
            echo "<form method='POST' action='remove_from_cart.php'>";
            echo "<input type='hidden' name='itemID' value='".$row['itemID']."'>";
            echo "<button id='button'>Remove from cart</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }
    ?>
</div>
