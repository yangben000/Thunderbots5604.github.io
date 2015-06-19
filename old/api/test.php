<?php
$mysql_host = "mysql5.000webhost.com";
$mysql_database = "a9873696_mvhs";
$mysql_user = "a9873696_admin";
$mysql_password = "mvhs2014";

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

// check connection
if ($conn->connect_error) {
	die("Connection Failed: " . $conn->connect_error);
}

echo "Connected Successfully!";
?>