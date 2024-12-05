<?php
if(isset($_POST["insertbutton"])){
$id = $_POST["IDtb"];
$ln = $_POST["lName"];
$fn = $_POST["fName"];
$city = $_POST["cityName"];

$servername = 'localhost';
$username = 'root';
$password = "";
$dbname = 'f24';

$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "INSERT INTO Persons VALUES ('$id', '$ln', '$fn', '$city')";
$result = $conn->query($sql);
if($result){
    echo "Records inserted successfully";
}
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
ID: <input type="text" name="IDtb"/>
First Name: <input type="text" name="fName"/>
Last Name: <input type="text" name="lName"/>
City: <input type="text" name="cityName"/>
<input type="submit" value="submit" name="insertbutton" >
</form>
