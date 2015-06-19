<?php
require_once 'core/init.php';

if (Input::exists())
{
	if (Token::check(Input::get('token')))
	{
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 2,
				'max' => 20,
				'unique' => 'users',
				'not_numeric' => true),
			'password' => array(
				'required' => true,
				'min' => 8),
			'password_again' => array(
				'required' => true,
				'matches' => 'password'),
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50,
				'not_numeric' => true)
		));

		if ($validation->passed())
		{
			$user = new User();

			$salt = Hash::salt(32);
			
			date_default_timezone_set('America/Los_Angeles');

			try
			{
				$user->create(array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'name' => Input::get('name'),
					'joined' => date("Y-d-m H:i:s"),
					'groups' => 1
				));

				Session::flash('home', 'You have been registered and can now log in!');
				Redirect::to('index.php');

			} catch (Exception $err)
			{
				die($err->getMessage());
			}
		} else {
			$errors = array();

			foreach ($validation->errors() as $error)
			{
				array_push($errors, $error);
			}

			var_dump($errors);
		}
	}
}
?>
<form action="" method="post">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" value="<?php echo escape(Input::get('username')); ?>" name="username" id="username" autocomplete="off" />
		<span><?php if (isset($errors["not_numeric"])) { echo "Username cannot be numeric."; } ?></span>
	</div>
	<div class="field">
		<label for="password">Choose a password</label>
		<input type="password" name="password" id="password" />
		<span><?php if (isset($errors["matches"])) { echo $errors["matches"]; } ?></span>
	</div>
	<div class="field">
		<label for="password_again">Enter your password again</label>
		<input type="password" name="password_again" id="password_again" />
		<span><?php if (isset($errors["matches"])) { echo $errors["matches"]; } ?></span>
	</div>
	<div class="field">
		<label for="name">Enter your name</label>
		<input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>" />
		<span><?php if (isset($errors["not_numeric"])) { echo "Username cannot be numeric."; } ?></span>
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	<input type="submit" value="Register" />
</form>