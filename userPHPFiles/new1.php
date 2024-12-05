<?php
$y = 7;
$x = 9;
$z = $y+$x;
echo $z;
echo "Hello <br>";

//Connecting the database
$servername = "localhost";
$username = "root"; //base user is "root"
$password = ""; //No password set, so you can leave this blank
$dbname = "f24"; //database name in xampp
$conn = new mysqli($servername, $username, $password, $dbname) or die("Connection failed:
%s\n". $conn->error);


//Creating a query
$sql = "select * from cities where CityName"; //Embedded SQL

$sql2 = "select * from cities where CityName='Broussard'"; //Embedded SQL

$result = $conn->query($sql);
while ($row = $result -> fetch_assoc()){
	echo "City Name: ".$row['CityName']."<br>";
	echo "CityID: ".$row['CityID']."<br>";
	echo "RandomNum: ".$row['RandomNum']."<br>";
}


//with a table:

if($result->num_rows > 0){
	echo "<table style='border: solid 1px black;'>
	<tr>
		<th>CityID</th>
		<th>CityName</th>
		</tr>";
}

while ($row = $result->fetch_assoc()){
	echo '<tr>
		<td> '.$row['CityID'].' </td>
		<td> '.$row['CityName'].' </td>
		</tr>';
}
echo "</table>";
?>