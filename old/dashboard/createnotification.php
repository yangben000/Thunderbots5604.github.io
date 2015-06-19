<?php
	include '../api/functions.php';
	$logged_in = checkIfLoggedIn();
	$log_bool = $logged_in[1];
	
	if (!$log_bool) { header("location: http://www.thunderbots.tk/login"); die("Please log in to continue."); }
	
	$refill_request = FALSE;
	
	$mysql_host = "mysql5.000webhost.com";
	$mysql_database = "a9873696_mvhs";
	$mysql_user = "a9873696_admin";
	$mysql_password = "mvhs2014";

	$connect = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
	
	if (isset($_POST['title']) && isset($_POST['content'])) {
		if (!empty($_POST['title']) && !empty($_POST['content'])) {
			$title = $_POST['title'];
			$content = $_POST['content'];
			
			$banned_chars = str_split("\'");
			
			$title = str_split($title);
			$content = str_split($content);
			
			foreach ($title as $index=>$value) {
				$title[$index] = $connect->real_escape_string($value);
			}
			
			foreach ($content as $index=>$value) {
				$content[$index] = $connect->real_escape_string($value);
			}
			
			$count = 0;
			
			foreach ($title as $index=>$value) {
				$count = $value == "\'" ? $count++ : $count;
				$title[$index] = $value == "\'" ? "&quot;" : $title[$index];
				
			}
			
			foreach ($content as $index=>$value) {
				$count = $value == "\'" ? $count++ : $count;
				$content[$index] = $value == "\'" ? "&quot;" : $content[$index];
				
			}
			
			echo $count;
			
			$title = join('', $title);
			$content = join('', $content);
			
			$author = $logged_in[0];
			$handler = fopen("../notifications/objects.json", "a+");

			$fwrite = fwrite($handler, ",{\"author\":\"$author\", \"content\":\"$content\", \"title\":\"$title\"}");
			fclose($handler);
			$handler_read = fopen("../notifications/objects.json", "r");
			$content_read = fread($handler_read, filesize("../notifications/objects.json"));
			fclose($handler_read);
			$handler = fopen("../notifications/index.json", "w");
			$fwrite = fwrite($handler, "[$content_read]");
			fclose($handler);
		} else {
			$refill_request = TRUE;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<?php
	if ($log_bool) {
		createHeaders("Create a new notification", "Thunderbots Notifications", "notify", 1);
	} else {
		createHeaders("Please Log In to continue", "Thunderbots Notifications", "notify", 1);
	}
?>
<body>
	<header class="main">
		<hgroup>
			<h1><?php
				if ($log_bool) { echo "(WIP) Thunderbots 5604 - Welcome " . $logged_in[0]; } else {
					echo "(WIP) Please log in to continue";
				}
			?></h1>
			<h3>Year: 2014, Competition: Cascade Effect</h3>
		</hgroup>
	</header>
	<div id="navigation">
		<ul>
			<li><a href='http://www.thunderbots.tk/'>HOME</a></li>
			<li><?php
				if ($log_bool) { echo "<a href='http://www.thunderbots.tk/login/signout.php'>LOG OUT</a>"; } else {
					echo "<a href='http://www.thunderbots.tk/login'>LOG IN</a></li><li><a href='http://www.thunderbots.tk/login/signup.php'>SIGN UP</a>";
				}
			?></li>
			<?php
				if ($log_bool) { echo "<li><a href='http://www.thunderbots.tk/dashboard/'>DASHBOARD</a></li>"; }
			?>
			<li><a href='http://www.thunderbots.tk/about'>ABOUT THUNDERBOTS 5604</a></li>
		</ul>
	</div>
	<section id="column_main">
		<div>
			<header>
				<h3>Create a new global notification: </h3>
			</header>
			<div class="content">
				<form method="post" action="createnotification.php">
					<table id="loginTable">
						<tr>
							<td>Title:</td>
							<td><input type="text" name="title" /></td>
						</tr>
						<tr>
							<td>Content: </td>
							<td><textarea name="content"></textarea></td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" value="Create notification" /></td>
						</tr>
					</table>
				</form>
				<footer>
					&larr;<a href="../">Go</a> back to home page.
				</footer>
			</div>
		</div>
	</section>
</body>
</html>
