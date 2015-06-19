<?php
include 'encrypt.php';

$mysql_host = "mysql5.000webhost.com";
$mysql_database = "a9873696_thunder";
$mysql_user = "a9873696_mvhs";
$mysql_password = "mvhs2014";

function closeConnection() {
	$disconnect = mysql_close();
	return $disconnect;
}

function connectToDatabase() {
	$checksum_status = checkForAuthenticity();
	
	if (!$checksum_status) {
		$checksum_status = createChecksums();
	}
	
	$checksums_status = checkForAuthenticity();
	
	if (!$checksum_status) {
		die("Your checksums have been tampered with or are invalid you may not visit the page.");
	}
	
	$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_password);
	
}
?>