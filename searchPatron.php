<?php
//if the session is set, then we allow the content to display to user. 
session_start ();
if (!isset ( $_SESSION ['username'] )) {
	header ("Location: index.php");
} else {
	include "header.php";
	?>
	<div id="logout">
<?php
	echo "welcome " . $_SESSION ['username'];
	?>
<a href="Logout.php"> Logout </a></div>
<div id="body_search_index">
<form method="POST">
<table width="90%" border="0" cellspacing="4" cellpadding="5">
	<tr><td align=right>Search Patron</td>
		<td><input name="searchPatron" type="text" id="searchPatron" size="30"></td>
		<td><select name="type" id="type">
			<option>lastname</option>
			<option>firstname</option>
			<option>username</option>
		</select></td>
	
		<td><input type="submit" name="Search" id="Search" value="Search" /></td>
	</tr>
</table>
</form>
<?php //import the user class which also include the database. 
include "User.php";
$db = new Database ();
$connection = $db->open_connection ();
if (! $connection) {
	die ( 'Could not connect: ' . mysql_error () );
}
$user = new User ();
//if the button add is pressed.
if(isset($_POST['Search'])) {
	if ($_POST ['searchPatron'] == "") {
		$user->display_patron($connection);
	} else { 
		// pass the test. call the method add patron in User class.
		echo "<h3> Search Result: </h3>";
		echo "<hr>";
		$keyword = $_POST ['searchPatron'];
		$type = $_POST ['type'];
		$user->search_patron($connection, $keyword, $type);
	}
}
//when a librarian click on the radio button and click sumbit.
if (isset ( $_POST ['submit'] )) {
		if (isset ( $_POST ['check'] )) {
			//check will get the value of the radio. this case it would give me 
			//the isbn and the copy.
			$id = $_POST ['check'];
			$user->remove_patron($connection, $id);
		} else {
			echo "Please select a patron you want to remove";
		}
	}
	$db->close_connection ( $connection );

?>
</div>
<?php 
}
?>
<?php include "footer.php"; ?>