<?php
include "../api/functions.php";

$logged_in = checkIfLoggedIn();
$log_bool = $logged_in[1];

if ($log_bool) {
	header("location: http://www.thunderbots.tk/");
}

if (isset($_POST['user']) && isset($_POST['pwd']) && isset($_POST['confirm_pwd']) && isset($_POST['email'])) {
	if (!empty($_POST['user']) && !empty($_POST['pwd']) && !empty($_POST['confirm_pwd']) && !empty($_POST['email'])) {
		$u = $_POST['user'];
		$pwd = $_POST['pwd'];
		$confirm_pwd = $_POST['confirm_pwd'];
		$email = $_POST['email'];
		
		$mysql_host = "mysql5.000webhost.com";
		$mysql_database = "a9873696_mvhs";
		$mysql_user = "a9873696_admin";
		$mysql_password = "mvhs2014";
		
		$connect = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
		
		$u = $connect->real_escape_string($u);
		$pwd = $connect->real_escape_string($pwd);
		$email = $connect->real_escape_string($email);
		
		
		$sql = "SELECT * FROM `users`";
		
		$result = $connect->query($sql);
		
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$current = decodeEncryption($row['UID'], $row['UKEY']);
				if ($u == $current) {
					die("Please choose an original username. Click <a href='../'>here</a> to continue.");
				}
			}
		}
			
		if ($pwd != $confirm_pwd) { header("location: http://www.thunderbots.tk/login/signup.php"); }
			
		signup($u, $pwd, $email);
		
		echo "Please verify your account, there should be an email in your inbox, if not then please check the spam folder.";
		header("location: http://www.thunderbots.tk/"); 
	} else {
		header("location: http://www.thunderbots.tk/login/signup.php");
	}
}
?>
<!DOCTYPE html>
<html lang="en-us">
<?php
createHeaders("Create a new account","Create a new account for the M.V.H.S. Robotics Club","account,mvhs,robotics,thunderbots",1);
?>
<body>
	<header>
		<hgroup>
			<h1>Thunderbots 5604</h1>
			<h3>Year: 2014, Competition: Cascade Effect</h3>
		</hgroup>
	</header>
	<section id="column_empty1">
		<!-- Place filler text //-->
	</section>
	<section id="column_main">
		<div>
			<header>
				<h3>Please create an account: </h3>
			</header>
			<div class="content">
				<form method="post" action="signup.php">
					<table id="loginTable">
						<tr>
							<td>Username:</td>
							<td><input type="text" name="user" /></td>
						</tr>
						<tr>
							<td>Password: </td>
							<td><input type="password" name="pwd" /></td>
						</tr>
						<tr>
							<td>Confirm Password: </td>
							<td><input type="password" name="confirm_pwd" /></td>
						</tr>
						<tr>
							<td>E-Mail: </td>
							<td><input type="text" name="email" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" value="Login" /></td>
						</tr>
					</table>
				</form>
				<footer>
					&larr;<a href="../">Go</a> back to home page.
				</footer>
			</div>
		</div>
	</section>
	<section id="column_empty2">
		<!-- Place filler text //-->
	</section>
	<footer>
		<small>2015 Thunderbots 5604</small>
	</footer>
</body>
</html>