<!--
This file is to display the records from the database
-->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname  = "db1";
$conn = new mysqli($servername, $username, $password, $dbname)or die("Connect failed: %s\n". $conn -> error);


$sql = "SELECT * FROM persons";

$result = $conn->query($sql);

while ($row = $result -> fetch_assoc()){
	echo "ID : ".$row['PersonID']."<br>";
	echo "Last Name : ".$row['LastName']."<br>";
	echo "First Name : ".$row['FirstName']."<br>";
	echo "City : ".$row['City']."<br>";
}

?>