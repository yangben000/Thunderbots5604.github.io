<!DOCTYPE html>
<html lang="en-us">
<?php
require_once 'core/init.php';

createHeader(0, "Access the robotics club!");

$errors = array();
$login_success;
$user = new User();
if (Input::exists())
{
	if (Token::check(Input::get('token')))
	{
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true),
			'password' => array('required' => true)
		));

		if ($validation->passed())
		{
			// Log In
			$user = new User();

			$remember = (Input::get('remember') == 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);

			if ($login)
			{
				Session::flash('home', 'You have been logged in!');
				Redirect::to('index.php');
			}
			else
			{
				$login_success = FALSE;
			}
		}
		else
		{
			foreach ($validation->errors() as $error)
			{
				array_push($errors, $error);
			}
		}
	}
}
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
	<header class="w3container green w3-padding-16">
		<span onclick="w3_open()" class="w3-opennav text-white w3-xxlarge">&#9776;</span>
		<div class="w3-image">
			<img src="images/logo.png" alt="Thunderbots 5604" />
		</div>
	</header>
<div class="w3-card-8">
	<header class="w3-container w3-padding-8 red">
		<h3>Log In</h3>
		<p>Click <a href="#login_form" class="w3-btn">here</a> to log in.</p>
	</header>
</div>
<div id="login_form" class="w3-modal">
	<div class="w3-modal-dialog">
		<div class="w3-modal-content w3-card-8">
			<header class="w3-container teal">
				<a href="#" class="w3-closebtn">&times;</a>
				<h2>Please Log In</h2>
			</header>
			<div class="w3-container white">
				<form action="login.php" method="post" class="w3-container">
					<div class="w3-group">
						<input class="w3-input" type="text" name="username" id="username" autocomplete="off" />
						<label class="w3-label" for="username">Username</label>
					</div>

					<div class="w3-group">
						<input class="w3-input" type="password" name="password" id="password" autocomplete="off" />
						<label class="w3-label" for="password">Password</label>		
					</div>

					<div class="field w3-group">
						<label class="w3-input" for="remember">
							<input type="checkbox" name="remember" id="remember" /> Remember Me
						</label>
					</div>
					<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />

					<input class="w3-input" type="submit" value="Log in" />
				</form>
			</div>
			<footer class="w3-container teal">
				<p>Thunderbots 5604 Robotics</p>
			</footer>
		</div>
	</div>
</div>
</div>
</body>
</html>