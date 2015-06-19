<?php
	
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
?>