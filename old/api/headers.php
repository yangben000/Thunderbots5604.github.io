<?php
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


?>