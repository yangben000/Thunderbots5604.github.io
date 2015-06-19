<?php
// Web site login script "login/index.php"
include "../api/functions.php";
if (isset($_POST['user']) && isset($_POST['pwd']) && !empty($_POST['user']) && !empty($_POST['pwd'])) {
	$u = $_POST['user'];
	$pwd = $_POST['pwd'];
	
	if (login($u, $pwd)) {
		header("location: http://www.thunderbots.tk/");			// redirects user to set new cookie values.
	} else {
		die("Credentials failed. You may be signing in from a new location if so please check your email.");
	}
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<?php
		createHeaders("Login to Thunderbots Robotics",
			"Login to Thunderbots Robotics",
			"login, thunderbots, mvhs, robotics, ftc, database", 1);
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
				<h3>Please login: </h3>
			</header>
			<div class="content">
				<form method="post" action="index.php">
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
		<small>2014 Thunderbots 5604</small>
	</footer>
</body>
</html>