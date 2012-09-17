<?php
//if the session is set, then we allow the content to display to user. 
session_start ();
if (! isset ( $_SESSION ['username'] )) {
	header ("Location: index.php");
} else {
	include "header.php";
	?>
<div id="logout">
<?php
	echo "welcome " . $_SESSION ['username'];
	?>
<a href="Logout.php"> Logout </a></div>
<div id="body_index">
<h3>Add a User</h3>
<form method="POST">
<table align= center width="80%" border="0" cellspacing="4" cellpadding="5">
	<tr><td align=right>Firstname</td>
		<td><input name="firstname" type="text" id="firstname" size="20">*</td>
	</tr>
	<tr><td align=right>Lastname</td>
		<td><input name="lastname" type="text" id="lastname" size="20">*</td>
	</tr>
	<tr><td align=right>Username</td>
		<td><input name="username" type="text" id="username" size="20">*</td>
	</tr>
	<tr><td align=right>password</td>
		<td><input name="password" type="password" id="password" size="20">*</td>
	</tr>
	<tr><td align=right>confirm password</td>
		<td><input name="confirm_password" type="password"
			id="confirm_password" size="20">*</td>
	</tr>
	<tr><td align=right>E mail</td>
		<td><input name="email" type="text" id="email" size="20"></td>
	</tr>
	<tr><td align=right>Address</td>
		<td><input name="address" type="text" id="address" size="20">*</td>
	</tr>
	<tr><td align=right>City</td>
		<td><input name="city" type="text" id="city" size="10"></td>
	</tr>
	<tr><td align=right>State</td>
		<td><input name="state" type="text" id="state" size="10"></td>
	</tr>
	<tr><td align=right>Zip</td>
		<td><input name="zip" type="text" id="zip" size="10">*</td>
	</tr>
	<tr><td align=right>Role</td>
		<td><select name="role" id="role">
			<option>patron</option>
			<option>librarian</option>
		</select></td>
	</tr>
	<tr><td align=right></td>
		<td><input type="submit" name="Add" id="submit" value="Add" /></td>
	</tr>
</table>
</form>
<?php
}
//import the user class which also include the database. 
include "User.php";
$db = new Database ();
$connection = $db->open_connection ();
if (! $connection) {
	die ( 'Could not connect: ' . mysql_error () );
}
$user = new User ();
//if the button add is pressed.
if(isset($_POST['Add'])) {
	$password = $_POST ['password'];
	$confirmation = $_POST ['confirm_password'];
	//check password. 
	if (! ($password == $confirmation)) {
		echo "Your password needs to be the same";
	} else if ($_POST ['address'] == "" ||
	$_POST ['firstname'] == "" || $_POST ['lastname'] == "" ||
	$_POST ['username'] == "" || $_POST[zip] =="") {
		echo "Please complete the require fields";
	} else { // pass the test. call the method add patron in User class.
		$email = $_POST ['email'];
		$address = $_POST ['address'];
		$zip = $_POST ['zip'];
		$firstname = $_POST ['firstname'];
		$lastname = $_POST ['lastname'];
		$username = $_POST ['username'];
		$role = $_POST ['role'];
		$user->add_patron ( $connection, $lastname, $firstname, $address, $email, 0, $zip, $username, $password, $role );
		$db->close_connection ( $connection );
	}
}
?>
</div>
<?php 
include "footer.php";
?>