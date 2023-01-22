 <?php

$servername = "localhost:3306";
$dbUsername = "jb1828_root";
$dbPassword = "A1B2C3a1b2c3";
$dbName = "jb1828_loginsystem";

$conn = mysqli_connect($servername ,$dbUsername, $dbPassword, $dbName);


if (!$conn) {
  die("Connection failed: ".mysqli_connect_error());
}

