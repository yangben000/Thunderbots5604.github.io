<!DOCTYPE html>
<html lang="en-us">
<?php
require_once 'core/init.php';

$user = new User();

createHeader(0, ($user->isLoggedIn() ? "Thunderbots Welcome " . escape($user->data()->username) : "Thunderbots Robotics"));
?>
<body>
	<nav class="w3-sidenav white" style="display:none;">
		<a href="javascript:void(0)" onclick="w3_close()" class="w3-closenav w3-large">Close &times;</a>
		<a href="index.php">Home</a>
		<?php
			if ($user->isLoggedIn()) {
				$escaped_name = escape($user->data()->username);
				?>
				<a href="<?php echo 'profile.php?user=' . $escaped_name; ?>">Profile</a>
				<a href="logout.php">Log Out</a>
				<?php
			} else {
				?>
				<a href="login.php">Log In</a>
				<a href="register.php">Register</a>
				<?php
			}
		?>
	</nav>
	<div id="main">
	<header class="w3-container green w3-padding-16">
		<span onclick="w3_open()" class="w3-opennav text-white w3-xxlarge">&#9776;</span>
		<div class="w3-image">
			<img src="images/logo.png" alt="Thunderbots 5604" />
		</div>
	<?php
	if (Session::exists('home'))
	{
		echo '<p>' . Session::flash('home') . '</p>';
	}

	if ($user->isLoggedIn()) {
		?>
		</header>
		<div class="w3-row">
		<section id="content" class="w3-card-8 w3-third content" style="margin: 10px;">
			<header class="w3container red w3-padding-8">
					<h3>LOGIN STATS</h3>
			</header>
			<article class="w3container white w3-padding-8">
			<ul>
				<li><a href="logout.php">Log Out</a></li>
				<li><a href="update.php">Update Profile</a></li>
				<li><a href="changepassword.php">Change Password</a></li>
			</ul>
			<?php
			if ($user->hasPermission('admin')) {
				echo '<p>You are an administrator!</p>';
			}
			if ($user->hasPermission('moderator')) {
				echo '<p>You are a moderator</p>';
			}
			?>
			</article>
			<?php
	} else {
		?>
			</header>
			<section class="w3-card-8 w3-third content" style="margin: 10px;">
				<header class="w3-container red w3-padding-8">
					<h3>LOGIN STATS</h3>
				</header>
				<article class="w3-container white w3-padding-8">
					<p>Please <a href="login.php">login</a> to view statistics.</p>
				</article>
		<?php
	}
	?>
	</section>
	<section class="w3-card-8 w3-half content" style="margin: 10px;">
		<header class="w3container red w3-padding-8">
			<h3>NEWS</h3>
		</header>
		<article class="w3container white w3-padding-8">
			<p>
				Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum OremLipsum OremLipsumLipsum OremLipsum OremLipsum Orem
				Lipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum Orem
				Lipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum Orem
			</p>
		</article>
	</section>
	</div>

	<div class="w3-row">
	<section class="w3-card-8 w3-third content" style="margin: 10px;">
		<header class="w3-container red w3-padding-8">
			<h3>Robo Pic!</h3>
		</header>
		<article class="w3-container white">
		<img src="images/robotics.jpg" alt="Robot!" width="100%" class="w3-circle" />
		
		<p class="w3-container white w3-padding-8">
			Showing off the conveyer belt!
		</p>
		</article>
	</section>
	<section class="w3-card-8 w3-half content" style="margin: 10px;">
		<header class="w3container red w3-padding-8">
			<h3>SUMMER INFO</h3>
		</header>
		<article class="w3-container white w3-padding-8">
			<p>
				Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum Orem Lipsum OremLipsum OremLipsumLipsum OremLipsum OremLipsum Orem
				Lipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum Orem
				Lipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum OremLipsum Orem
			</p>
		</article>
	</section>
	</div>
	</div>
	<?php
		include_once "includes/footer.php";
	?>
</body>
</html>