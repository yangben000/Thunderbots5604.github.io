<?php
include "../api/functions.php";
$logout_success = logout();
header("location: http://www.thunderbots.tk/");
?>
<!DOCTYPE html>
<html lang="en-us">
	<?php
		createHeaders("Log Out of Thunderbots Robotics",
			"Login to Thunderbots Robotics",
			"login, thunderbots, mvhs, robotics, ftc, database", 1);
	?>
<body>
<pre>Signing Out</pre>
</body>
</html>