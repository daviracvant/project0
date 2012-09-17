<?php 
	session_start(); 
	if (!isset ($_SESSION['username'])) {
		header ("Location: index.php");
	} else {
	include "header.php";
	?>
<div id="logout">
<?php
	echo "welcome " . $_SESSION ['username'];
	?>
<a href="Logout.php"> Logout </a>
</div>

<div id="body_index">
<h3>Add a Book</h3>
<form method="POST">
<table align= center width="80%" border="0" cellspacing="4" cellpadding="5">
	<tr><td align=right>isbn</td>
		<td><input name="isbn" type="text" id="isbn" size="20">*</td>
	</tr>
	<tr><td align=right>author</td>
		<td><input name="author" type="text" id="author" size="20">*</td>
	</tr>
	<tr><td align=right>title</td>
		<td><input name="title" type="text" id="title" size="20">*</td>
	</tr>
	<tr><td align=right>year</td>
		<td><input name="year" type="text" id="year" size="20">*</td>
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
	//check password. 
	if ($_POST ['isbn'] == "" || $_POST ['author'] == "" ||
	$_POST ['title'] == "" || $_POST ['year'] == "") {
		echo "Please complete the require fields";
	} else { // pass the test. call the method add patron in User class.
		$isbn = $_POST ['isbn'];
		$author = $_POST ['author'];
		$title = $_POST['title'];
		$year = $_POST ['year'];
		//remove the number of book.
		$user->add_Book($connection, $isbn, $author, $title, $year);
		$db->close_connection ( $connection );
	}
}
?>
</div>
<?php include "footer.php";?>