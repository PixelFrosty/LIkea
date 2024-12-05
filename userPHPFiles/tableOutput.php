<!--
This file is used to display the records in table format
-->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname  = "db1";
$conn = new mysqli($servername, $username, $password, $dbname)or die("Connect failed: %s\n". $conn -> error);


$sql = "SELECT * FROM persons";

$result = $conn->query($sql);

if($result->num_rows > 0){
 echo "<table style='border: solid 1px black;'>
	<tr>
	    <th>ID</th>
	    <th>Last Name</th>
	<th>First Name</th>
	<th>City</th>
	
	</tr>";
}


while ($row = $result -> fetch_assoc()){
	echo '<tr>
		<td> '.$row['PersonID'].' </td>
		 <td> '.$row['LastName'].' </td>
		<td> '.$row['FirstName'].' </td>
		<td> '.$row['City'].' </td>
	      </tr>';	
}

echo "</table>";

?>