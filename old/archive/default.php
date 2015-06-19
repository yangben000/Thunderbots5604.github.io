<?php
// All includes must be before the <!DOCTYPE> tag
include '../api/functions.php';


$logged_in = checkIfLoggedIn();
$log_bool = $logged_in[1];
?>
<!DOCTYPE html>
<html lang="en-us">
<?php
	if (!$log_bool) {
	createHeaders("Archived Notifications", 
		"Mountain View High School robotics team, competing in this years competition: Cascade Effect", 
		"M.V.H.S., robotics, 2014, cascade, effect", 1);
	} else {
		createHeaders("Archived Notifications - Welcome " . $logged_in[0], 
			"Mountain View High School robotics team, competing in this years competition: Cascade Effect", 
			"M.V.H.S., robotics, 2014, cascade, effect", 1);
	}
?>
<body>
	<header class="main">
		<hgroup>
			<h1><?php
				if ($log_bool) { echo "(WIP) Thunderbots 5604 - Welcome " . $logged_in[0]; } else {
					echo "(WIP) Thunderbots 5604";
				}
			?></h1>
			<h3>Year: 2014-2015, Competition: Cascade Effect</h3>
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
	<section id="column_empty1">
		<!-- Place filler text //-->
		<header>
			<h4>Who is on the team?</h4>
		</header>
		<div class="content">
			<ul>
				<li>Sabrina Ahmed</li>
				<li>Javier Anton</li>	
				<li>Addison Brock</li>
				<li>Mark Chen</li>
				<li>Mason Douglas</li>
				<li>Daniel Grimshaw</li>
				<li>Alec Hess</li>
				<li>Edmund Hsu</li>
				<li>Ray Huang</li>
				<li>Lyric Indino</li>
				<li>Ethan Knight</li>
				<li>Samuel Kuo</li>
				<li>Pranav Mathur</li>
				<li>Seraphina Meacham</li>
				<li>Tiffany Nguyen</li>	
				<li>Zach Ohara</li>
				<li>Zach Waldron</li>	
				<li>Rose Wang</li>		
			</ul>
		</div>
		<header>
			<h4>Who coaches the team?</h4>
		</header>
		<div class="content">
			<ul>
				<li>Christain Kaneen</li>
				<li>Ken Williams</li>
				<li>Phil Cowan</li>					
			</ul>
		</div>
	</section>
	<section id="column_main">
		<div>
			<script>
			  (function() {
				var cx = '002997125637510130970:uyxo67yl-nw';
				var gcse = document.createElement('script');
				gcse.type = 'text/javascript';
				gcse.async = true;
				gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
					'//www.google.com/cse/cse.js?cx=' + cx;
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(gcse, s);
			  })();
			</script>
			<gcse:search></gcse:search>
			<header>
				<h3>Latest Announcements</h3>
			</header>
			<div class="content" id="main_content">
				<div id="notification1">
					<header><h4><span id="title1"></span> - By:<span id="author1"></span></h4></header>
					<p id="content1"></p>
				</div>
				<div id="notification2">
					<header><h4><span id="title2"></span> - By:<span id="author2"></span></h4></header>
					<p id="content2"></p>
				</div>
				<div id="notification3">
					<header><h4><span id="title3"></span> - By:<span id="author3"></span></h4></header>
					<p id="content3"></p>
				</div>
				<div id="notification4">
					<header><h4><span id="title4"></span> - By:<span id="author4"></span></h4></header>
					<p id="content4"></p>
				</div>
				<div id="notification5">
					<header><h4><span id="title5"></span> - By:<span id="author5"></span></h4></header>
					<p id="content5"></p>
				</div>
				<div id="notification6">
					<header><h4><span id="title6"></span> - By:<span id="author6"></span></h4></header>
					<p id="content6"></p>
				</div>
			</div>
			<footer>
				
			</footer>
		</div>
	</section>
	<section id="column_empty2">
		<!-- Only use for account specific notifications //-->
	</section>
	<footer>
		<small>2015 Thunderbots 5604</small>
	</footer>
	<script type="text/javascript">
		function createObjects(obj_array) {
			$('#main_content').
		}
		function displayText(obj_array) {
			for (var n = 1; n <= obj_array.length; n++)
			{
				$("#title" + n).text(obj_array[obj_array.length - n].title);
				$("#author" + n).text(obj_array[obj_array.length - n].author);
				$("#content" + n).text(obj_array[obj_array.length - n].content);
			}
		}

		var xmlhttp = new XMLHttpRequest();
		var url = "notifications/index.json";
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var obj_array = JSON.parse(xmlhttp.responseText);
				displayText(obj_array);
			}
		}

		xmlhttp.open("GET", url, true);
		xmlhttp.send();
	</script>
</body>
</html>