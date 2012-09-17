<?php
//start a session.
session_start ();
include "database.php";

if (!isset ( $_SESSION ['username'] )) {
	include "header.php";
	?>
<div id="body_index">
<h3> Please Login to Continue: </h3>
<form method="POST" action="login.php">
Username: <input type="text" name="username" size="15" /> <br />
<br />
Password: <input type="password" name="password" size="15" /><br />
<br />
<input type="submit" name="login" value="Login" />
</form>
</div>
<?
} else {
	include "header.php";
	?>
<div id = "logout">
<?php 
	echo "welcome " . $_SESSION ['username'];
	?>
	
<a href="Logout.php" >
Logout
</a>
</div>
<div id = "side">
<table id="table_1">
	<?php 
	if ($_SESSION ['Role'] == 'librarian') {
		echo "<tr><td><a href=\"accountInfo.php\">Information</a></td></tr>
		<tr><td><a href=\"addUser.php\">Add User</a></td></tr>
		<tr><td><a href=\"addBook.php\">Add book</a></td></tr>
		<tr><td><a href=\"searchPatron.php\">Search patron</a></td></tr>
		<tr><td><a href=\"searchBook.php\">Search Book</a></td></tr>
		<tr><td><a href=\"checkinBook.php\">Check In Book</a></td></tr>";
	} else {
		echo "<tr><td><a href=\"accountInfo.php\">Information</a></td></tr>
		<tr><td><a href=\"searchBook.php\">Search Book</a></td></tr>
		<tr><td><a href=\"checkinBook.php\">Check In Book</a></td></tr>";
		}
		?>
</table>
</div>
<div id = "welcome">
	<h3> Welcome to Newport Oldtown Library System</h3>
</div>
<?
}
include "footer.php";
?>