<?php
$mysql_host = "mysql5.000webhost.com";
$mysql_database = "a9873696_mvhs";
$mysql_user = "a9873696_admin";
$mysql_password = "mvhs2014";

include '../api/functions.php';

if (isset($_GET['u']) && isset($_GET['pwd']) && isset($_GET['email'])) {
	if (!empty($_GET['u']) && !empty($_GET['pwd']) && isset($_GET['email'])) {
		$connection = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
		$username = $connection->real_escape_string(urldecode($_GET['u']));
		$password = $connection->real_escape_string(urldecode($_GET['pwd']));
		$email = $connection->real_escape_string(urldecode($_GET['email']));
		
		$sql = "SELECT * FROM `temp` WHERE `UID`='$username'";
		$result = $connection->query($sql);
		
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$ukey = $row['UKEY'];
				$pkey = $row['PKEY'];
				$ekey = $row['EKEY'];
				
				$sql = "INSERT INTO users (UID, PWD, EMAIL, UKEY, PKEY, EKEY) VALUES ('$username', '$password', '$email', '$ukey', '$pkey', '$ekey')";
				$add_user = $connection->query($sql);
				if ($add_user === TRUE) { echo "Data inserted..."; }
			}
		} else {
			die("Your information has been tampered with, will not add user...");
		}
		
		$sql = "DELETE FROM `temp` WHERE `UID`='$username'";
		$delete_record = $connection->query($sql);
		$connection->close();
		header("location: http://www.thunderbots.tk/");
	} else {
		header("location: http://www.thunderbots.tk/");
	}
} else {
	header("location: http://www.thunderbots.tk/");
}
?>