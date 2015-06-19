<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn())
{
	Redirect::to('index.php');
}

if (Input::exists())
{
	if (Token::check(Input::get('token')))
	{
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50),
			'username' => array(
				'required' => true,
				'min' => 2,
				'max' => 20,
				'unique' => 'users'
			)
		));

		if ($validation->passed())
		{
			// update profile
			try
			{
				$user->update(array(
					'name' => Input::get('name')
				));
				$user->update(array(
					'username' => Input::get('username')
				));
			}
			catch (Exception $err)
			{
				die($err->getMessage());
			}
		}
		else
		{
			// Loop through errors
			foreach ($validation->errors() as $error)
			{
				echo $error, '<br />';
			}
		}
	}
}
?>
<form action="" method="post">
	<div class="field">
		<label for="name">Name</label>
		<input type="text" name="name" value="<?php echo escape($user->data()->name); ?>" />
	</div>

	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" value="<?php echo escape($user->data()->username); ?>" />
	</div>

	<input type="submit" value="Update" />
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
</form>