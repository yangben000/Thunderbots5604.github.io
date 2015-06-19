<?php
	error_reporting(0);
	
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
	function createHeaders($title, $description, $keywords, $goUpFolders) {
		$additionalJavaScriptString = "";
		$additionalCSSString = "";

		echo("<head>
			<title>$title</title>
			<meta charset='UTF-8' />
			<meta name='keywords' content='$keywords' />
			<meta name='description' content='$description' />
			<meta name='author' content='Thunderbots Robotics' />");

		if ($goUpFolders > 0) {
			for ($i = 0; $i < $goUpFolders; $i++) {
				$additionalJavaScriptString .= "../";
				$additionalCSSString .= "../";
			}
		}

		$javaScriptString = $additionalJavaScriptString . "js";
		$cssString = $additionalCSSString . "css";

		echo("<script src='https://code.jquery.com/jquery-1.11.1.min.js'></script>
			<script src='$javaScriptString/api.js'></script><link rel='Stylesheet' type='text/css' href='$cssString/main.css' />");
		echo("</head>");
	}

		
	// Will have a combination of 4 KEYS to create secure protocol
	function encode($N, $msg){
		// Initialization
		$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890,.;?![]{}@#$()*/: =";
		$M = strlen($alphabet);
		$encrypt = array();
		$encode = array();
		$plain = preg_split("//",$msg, -1, PREG_SPLIT_NO_EMPTY);
		$alphabet = str_split($alphabet);
  
		$encode[' '] = ' ';
		foreach ($alphabet as $n=>$v){
			$encode[$v] =  $alphabet[($n+$N) % $M];		// Compute the encoding map for $v
		}

		foreach ($plain as $v){
			$encrypt[] = $encode[$v];
		}

		return array(join('',$encrypt), $N);
	}
	
	function decode($N,$msg){
		// Initialization
		$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890,.;?![]{}@#$()*/: =";
  	    $M = strlen($alphabet);
		$decrypt = array();
		$decode = array();

		$encrypted = preg_split("//",$msg, -1, PREG_SPLIT_NO_EMPTY);
		$alphabet = str_split($alphabet);

		// Compute the decoding map
		$decode[' '] = ' ';
		foreach ($alphabet as $n=>$v){
			$decode[$v] = $alphabet[($M+($n-$N)) % $M];	  	// Compute the decoding map for $v
		}

		foreach ($encrypted as $v){
			$decrypt[] = $decode[$v];
		}

		return join('',$decrypt);
	}

	function generateHashValues() {
		$result = '';
		for ($i = 0; $i <= 1024; $i++) {
			$result .= rand(0, 9);
		}
		
		$result = str_split($result, 2);
		$count = 0;
		foreach ($result as $p) {
			$result[$count] = intval($result[$count], 10);
			$result[$count] = log($result[$count], 2);
			$power = rand(2, 4);
			$result[$count] = pow($result[$count], $power);
			$result[$count] = log($result[$count], 2);
			$power = rand(5, 10);
			$result[$count] = pow($result[$count], $power);
			$result[$count] = round($result[$count] + 1);
			$count = $count + 1;
		}
		return $result;
	}
	
	function cipherAmount($number) {
		$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890,.;?![]{}@#$()*/: =";
		$result = abs($number + 1) % strlen($alphabet);
		return $result;
	}
	
	function generateHash($input) {
		$hash_values = generateHashValues();
		$result = '';
		$cipherL = '';
		$input = str_split($input);
		// Limited to only length of input due to mathematical periods. :/
		foreach ($input as $k=>$v) {
			$cipher_n = cipherAmount($hash_values[$k]);
			$out_char = encode(abs($cipher_n), $v);
			if ($out_char[1] < 10) {
				$out_char[1] = '0' . abs($out_char[1]);
				$cipherL .= $out_char[1];
			} else {
				$cipherL .= $out_char[1];
			}
			$result .= $out_char[0];
		}
		
		$key = str_split($cipherL, 2);
		foreach ($key as $index=>$value) {
			$key[$index] = intval($value, 10);
		}
		
		foreach ($key as $index=>$value) {
			$values = encode($value, 'a');
			$key[$index] = $values[0];
		}
		
		$cipherL = join('', $key);
		
		return array($result, $cipherL);		// In PHP you CAN return arrays Woo Hoo!
	}
	
	function decodeEncryption($input, $keys) {
		$keys = str_split($keys, 1);
		$counts = array();
		foreach ($keys as $key=>$value) {
			$count = 0;
			while ($value != 'a') {
				$value = decode(1, $value);
				$count = $count + 1;
			}
			
			if ($count < 10) {
				$count = '0' . abs($count);
			}
			array_push($counts, $count);
		}
		
		$input = str_split($input, 1);
		
		foreach ($input as $key=>$value) {
			$input[$key] = decode($counts[$key],$input[$key]);
		}
		
		$output = join('', $input);
		return $output;
	}
	
	function generateServerChecksum() {
		$alphabet = "abcdefghijklmnopqrstuvwxyz";	// Checksums are lowercase only
		$alphabet = str_split($alphabet, 1);
		$output = '';
		for ($i = 0; $i < 512; $i++) {
			$randLetter = rand(0, 26);
			$output .= $alphabet[$randLetter];
		}
		
		return $output;
	}
	
	function generateClientChecksum() {
		$alphabet = "abcdefghijklmnopqrstuvwxyz";	// Checksums are lowercase only
		$alphabet = str_split($alphabet, 1);
		$output = '';
		for ($i = 0; $i < 512; $i++) {
			$randLetter = rand(0, 26);
			$output .= $alphabet[$randLetter];
		}
		
		return $output;
	}
	
	function checkForAuthenticity() {
		if (!isset($_COOKIE['checksum']) || !isset($_COOKIE['client']) || empty($_COOKIE['checksum']) || empty($_COOKIE['client'])) {
			return FALSE;
		} else {
			$output = array('', '');
			$checksum = str_split($_COOKIE['checksum']);
			
			$client = str_split($_COOKIE['client']);
			
			foreach ($checksum as $index=>$value) {
				$output[0] .= $value;
			}
			
			foreach ($client as $index=>$value) {
				$output[1] .= $value;
			}
			$output[0] = str_split($output[0]);
			$output[1] = str_split($output[1]);
			$returnVal = TRUE;
			
			for ($i = 0; $i < 256; $i++) {
				if ($str_one[$i] == $str_two[$i]) {
					$returnVal = $returnVal;
				} else {
					$returnVal = FALSE;
				}
			}
			
			return $returnVal;
		}
	}
	
	function createChecksums() {
		if ((!isset($_COOKIE['checksum']) || empty($_COOKIE['checksum'])) || (!isset($_COOKIE['client']) || empty($_COOKIE['client']))) {
			$checksum = generateServerChecksum();
			
			$clientChecksum = generateClientChecksum();
			
			$checksum = $clientChecksum . $checksum;
			
			$checksum = str_split($checksum, 512);
			
			$clientChecksum = $checksum[0] . $clientChecksum;
			
			$clientChecksum = str_split($clientChecksum, 512);
			
			for ($i = 0; $i < 256; $i++) {
				if ($clientChecksum[1][$i] != $checksum[0][$i]) {
					die("<pre>Checksum creation failed. Or cookies are blocked.</pre>");
					return FALSE;
				}
			}
			
			echo $checksum[0] . "<br />";
			echo $clientChecksum[1] . "<br />";
			
			setcookie("checksum", $checksum[0], time() + 60 * 60 * 24 * 30, "/");
			setcookie("client", $clientChecksum[1], time() + 60 * 60 * 24 * 30, "/");
			return checkForAuthenticity();
		} else {
			return checkForAuthenticity();
		}
	}
	
	function encode($N, $msg){
		// Initialization
		$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890,.;?![]{}@#$()*/: =";
		$M = strlen($alphabet);
		$encrypt = array();
		$encode = array();
		$plain = preg_split("//",$msg, -1, PREG_SPLIT_NO_EMPTY);
		$alphabet = str_split($alphabet);
  
		$encode[' '] = ' ';
		foreach ($alphabet as $n=>$v){
			$encode[$v] =  $alphabet[($n+$N) % $M];		// Compute the encoding map for $v
		}

		foreach ($plain as $v){
			$encrypt[] = $encode[$v];
		}

		return array(join('',$encrypt), $N);
	}
	
	function decode($N,$msg){
		// Initialization
		$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890,.;?![]{}@#$()*/: =";
  	    $M = strlen($alphabet);
		$decrypt = array();
		$decode = array();

		$encrypted = preg_split("//",$msg, -1, PREG_SPLIT_NO_EMPTY);
		$alphabet = str_split($alphabet);

		// Compute the decoding map
		$decode[' '] = ' ';
		foreach ($alphabet as $n=>$v){
			$decode[$v] = $alphabet[($M+($n-$N)) % $M];	  	// Compute the decoding map for $v
		}

		foreach ($encrypted as $v){
			$decrypt[] = $decode[$v];
		}

		return join('',$decrypt);
	}

	function generateHashValues() {
		$result = '';
		for ($i = 0; $i <= 1024; $i++) {
			$result .= rand(0, 9);
		}
		
		$result = str_split($result, 2);
		$count = 0;
		foreach ($result as $p) {
			$result[$count] = intval($result[$count], 10);
			$result[$count] = log($result[$count], 2);
			$power = rand(2, 4);
			$result[$count] = pow($result[$count], $power);
			$result[$count] = log($result[$count], 2);
			$power = rand(5, 10);
			$result[$count] = pow($result[$count], $power);
			$result[$count] = round($result[$count] + 1);
			$count = $count + 1;
		}
		return $result;
	}
	
	function cipherAmount($number) {
		$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890,.;?![]{}@#$()*/: =";
		$result = abs($number + 1) % strlen($alphabet);
		return $result;
	}
	
	function generateHash($input) {
		$hash_values = generateHashValues();
		$result = '';
		$cipherL = '';
		$input = str_split($input);
		// Limited to only length of input due to mathematical periods. :/
		foreach ($input as $k=>$v) {
			$cipher_n = cipherAmount($hash_values[$k]);
			$out_char = encode(abs($cipher_n), $v);
			if ($out_char[1] < 10) {
				$out_char[1] = '0' . abs($out_char[1]);
				$cipherL .= $out_char[1];
			} else {
				$cipherL .= $out_char[1];
			}
			$result .= $out_char[0];
		}
		
		$key = str_split($cipherL, 2);
		foreach ($key as $index=>$value) {
			$key[$index] = intval($value, 10);
		}
		
		foreach ($key as $index=>$value) {
			$values = encode($value, 'a');
			$key[$index] = $values[0];
		}
		
		$cipherL = join('', $key);
		
		return array($result, $cipherL);		// In PHP you CAN return arrays Woo Hoo!
	}
	
	function decodeEncryption($input, $keys) {
		$keys = str_split($keys, 1);
		$counts = array();
		foreach ($keys as $key=>$value) {
			$count = 0;
			while ($value != 'a') {
				$value = decode(1, $value);
				$count = $count + 1;
			}
			
			if ($count < 10) {
				$count = '0' . abs($count);
			}
			array_push($counts, $count);
		}
		
		$input = str_split($input, 1);
		
		foreach ($input as $key=>$value) {
			$input[$key] = decode($counts[$key],$input[$key]);
		}
		
		$output = join('', $input);
		return $output;
	}
	
	function generateServerChecksum() {
		$alphabet = "abcdefghijklmnopqrstuvwxyz";	// Checksums are lowercase only
		$alphabet = str_split($alphabet, 1);
		$output = '';
		for ($i = 0; $i < 512; $i++) {
			$randLetter = rand(0, 26);
			$output .= $alphabet[$randLetter];
		}
		
		return $output;
	}
	
	function generateClientChecksum() {
		$alphabet = "abcdefghijklmnopqrstuvwxyz";	// Checksums are lowercase only
		$alphabet = str_split($alphabet, 1);
		$output = '';
		for ($i = 0; $i < 512; $i++) {
			$randLetter = rand(0, 26);
			$output .= $alphabet[$randLetter];
		}
		
		return $output;
	}
	
	function checkForAuthenticity() {
		if (!isset($_COOKIE['checksum']) || !isset($_COOKIE['client']) || empty($_COOKIE['checksum']) || empty($_COOKIE['client'])) {
			return FALSE;
		} else {
			$output = array('', '');
			$checksum = str_split($_COOKIE['checksum']);
			
			$client = str_split($_COOKIE['client']);
			
			foreach ($checksum as $index=>$value) {
				$output[0] .= $value;
			}
			
			foreach ($client as $index=>$value) {
				$output[1] .= $value;
			}
			$output[0] = str_split($output[0]);
			$output[1] = str_split($output[1]);
			$returnVal = TRUE;
			
			for ($i = 0; $i < 256; $i++) {
				if ($str_one[$i] == $str_two[$i]) {
					$returnVal = $returnVal;
				} else {
					$returnVal = FALSE;
				}
			}
			
			return $returnVal;
		}
	}
	
	function createChecksums() {
		if ((!isset($_COOKIE['checksum']) || empty($_COOKIE['checksum'])) || (!isset($_COOKIE['client']) || empty($_COOKIE['client']))) {
			$checksum = generateServerChecksum();
			
			$clientChecksum = generateClientChecksum();
			
			$checksum = $clientChecksum . $checksum;
			
			$checksum = str_split($checksum, 512);
			
			$clientChecksum = $checksum[0] . $clientChecksum;
			
			$clientChecksum = str_split($clientChecksum, 512);
			
			for ($i = 0; $i < 256; $i++) {
				if ($clientChecksum[1][$i] != $checksum[0][$i]) {
					die("<pre>Checksum creation failed. Or cookies are blocked.</pre>");
					return FALSE;
				}
			}
			
			echo $checksum[0] . "<br />";
			echo $clientChecksum[1] . "<br />";
			
			setcookie("checksum", $checksum[0], time() + 60 * 60 * 24 * 30, "/");
			setcookie("client", $clientChecksum[1], time() + 60 * 60 * 24 * 30, "/");
			return checkForAuthenticity();
		} else {
			return checkForAuthenticity();
		}
	}
	
	function createHeaders($title, $description, $keywords, $goUpFolders) {
		$additionalJavaScriptString = "";
		$additionalCSSString = "";

		echo("<head>
			<title>$title</title>
			<meta charset='UTF-8' />
			<meta name='keywords' content='$keywords' />
			<meta name='description' content='$description' />
			<meta name='author' content='Thunderbots Robotics' />");

		if ($goUpFolders > 0) {
			for ($i = 0; $i < $goUpFolders; $i++) {
				$additionalJavaScriptString .= "../";
				$additionalCSSString .= "../";
			}
		}

		$javaScriptString = $additionalJavaScriptString . "js";
		$cssString = $additionalCSSString . "css";

		echo("<script src='https://code.jquery.com/jquery-1.11.1.min.js'></script>
			<script src='$javaScriptString/api.js'></script><link rel='Stylesheet' type='text/css' href='$cssString/main.css' />");
		echo("</head>");
	}
	// always disconnect after use
	function checkIfLoggedIn() {
		$mysql_host = "mysql5.000webhost.com";
		$mysql_database = "a9873696_mvhs";
		$mysql_user = "a9873696_admin";
		$mysql_password = "mvhs2014";
		
		// cookie names are user, pwd, key, and ukey.
		if (isset($_COOKIE['user']) && isset($_COOKIE['pwd']) && !empty($_COOKIE['user']) && !empty($_COOKIE['pwd']) && isset($_COOKIE['key']) && !empty($_COOKIE['key']) && isset($_COOKIE['ukey']) && !empty($_COOKIE['ukey'])) {
			$uid = urldecode($_COOKIE['user']);
			$pwd = urldecode($_COOKIE['pwd']);
			$key = urldecode($_COOKIE['key']);
			$ukey = urldecode($_COOKIE['ukey']);
			
			$connect = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
			
			$username = $connect->real_escape_string($username);
			$pwd = $connect->real_escape_string($pwd);
			$key = $connect->real_escape_string($key);
			$ukey = $connect->real_escape_string($ukey);
			
			if ($connect->connect_error) {
				die("Connection Failed." . $connect->connect_error);
			}
			
			$sql = "SELECT * FROM `users`";
			
			$result = $connect->query($sql);
			
			$username = NULL;
			
			if ($result->num_rows >= 1) {
				while ($row = $result->fetch_assoc()) {
					if (decodeEncryption($row['UID'], $row['UKEY']) == decodeEncryption($uid, $ukey)) {
						$username = $row['UID'];
						$sql = "SELECT * FROM `users` WHERE `UID`='$username'";
						$result_password = $connect->query($sql);
						while ($columns = $result_password->fetch_assoc()) {
							$stored_pwd = decodeEncryption($columns['PWD'], $columns['PKEY']);
							$given_pwd = decodeEncryption($pwd, $key);
							if ($stored_pwd == $given_pwd) {
								return array(decodeEncryption($username, $row['UKEY']), TRUE);
							}
						}
						break;	// break out of the loop when user is found.
					}
				}
				
				if ($username == NULL) {
					return array($username, FALSE);
				}
			} else {
				die("Database not found.");
			}
		}
	}

	function login($username, $password) {
		$mysql_host = "mysql5.000webhost.com";
		$mysql_database = "a9873696_mvhs";
		$mysql_user = "a9873696_admin";
		$mysql_password = "mvhs2014";
		
		$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

		// check connection
		if ($conn->connect_error) {
			die("Connection Failed: " . $conn->connect_error);
		}
		
		$username = $conn->real_escape_string($username);
		$password = $conn->real_escape_string($password);
		
		$sql = "SELECT * FROM `users`";
		
		$sql = $conn->real_escape_string($sql);
		
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$stored_username = decodeEncryption($row['UID'], $row['UKEY']);
				$stored_password = decodeEncryption($row['PWD'], $row['PKEY']);
				
				$encoded_username = generateHash($stored_username);
				$encoded_password = generateHash($stored_password);
				
				if (($stored_username == $username) && ($stored_password == $password)) {
					setcookie("user", $encoded_username[0], time() + 60 * 60 * 24 * 30, "/");
					setcookie("pwd", $encoded_password[0], time() + 60 * 60 * 24 * 30, "/");
					setcookie("ukey", $encoded_username[1], time() + 60 * 60 * 24 * 30, "/");
					setcookie("key", $encoded_password[1], time() + 60 * 60 * 24 * 30, "/");
					return TRUE;
				}
			}
			
			return FALSE;
		} else {
			return FALSE;
		}
		
		return FALSE;
	}

	// This will return a boolean of whether or not the logout operation was successful.
	function logout() {
		// If not set then do not attempt do to undefined arrays, or unable to manipulate empty arrays (other than appending, or destroying)
		if (!isset($_COOKIE['user']) || !isset($_COOKIE['pwd']) || !isset($_COOKIE['ukey']) || !isset($_COOKIE['key']) || empty($_COOKIE['user']) || empty($_COOKIE['pwd']) || empty($_COOKIE['ukey']) || empty($_COOKIE['key'])) {
			return FALSE;
		}
		
		setcookie("user", NULL, time() - 1, "/");
		setcookie("pwd", NULL, time() - 1, "/");
		setcookie("ukey", NULL, time() - 1, "/");
		setcookie("key", NULL, time() - 1, "/");
		
		if (!isset($_COOKIE['user']) || !isset($_COOKIE['pwd']) || !isset($_COOKIE['ukey']) || !isset($_COOKIE['key'])) {
			return TRUE;
		} else { return FALSE; }
	}

	function signup($u, $pwd, $email) {
		$originals = array($u, $pwd, $email);

		$u_encoded = generateHash($u);
		$ukey = $u_encoded[1];
		$u = $u_encoded[0];
		$pwd_encoded = generateHash($pwd);
		$pkey = $pwd_encoded[1];
		$pwd = $pwd_encoded[0];
		$email_encoded = generateHash($email);
		$ekey = $email_encoded[1];
		$email = $email_encoded[0];
		
		$mysql_host = "mysql5.000webhost.com";
		$mysql_database = "a9873696_mvhs";
		$mysql_user = "a9873696_admin";
		$mysql_password = "mvhs2014";
		
		$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
		
		$query = "INSERT INTO temp (UID, PWD, EMAIL, UKEY, PKEY, EKEY) VALUES ('$u', '$pwd', '$email', '$ukey', '$pkey', '$ekey')";
		$result = $conn->query($query);
		
		$u = urlencode($u);
		$pwd = urlencode($pwd);
		$email = urlencode($email);
		
		$email_successful = mail($originals[2], "Please verify your account", "Thank you " . $originals[0] . " for joining the Thunderbots robotics club! \r\n
			Please go to http://www.thunderbots.tk/verify?u=$u&pwd=$pwd&email=$email");
	}
?>