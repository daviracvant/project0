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
	<tr><td align=right>Search Book</td>
		<td><input name="searchBook" type="text" id="searchBook" size="30"></td>
		<td><select name="type" id="type">
			<option>isbn</option>
			<option>author</option>
			<option>title</option>
			<option>year</option>
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
	if ($_POST ['searchBook'] == "") {
		$user->display_book($connection);
	} else { 
		// pass the test. call the method add patron in User class.
		echo "<h3> Search Result: </h3>";
		echo "<hr>";
		$keyword = $_POST ['searchBook'];
		$type = $_POST ['type'];
		$user->search_Book($connection, $keyword, $type);
		
	}
}
//when a librarian click on the radio button and click sumbit.
if (isset ( $_POST ['submit'] )) {
		if (isset ( $_POST ['check'] )) {
			//check will get the value of the radio. this case it would give me 
			//the isbn and the copy.
			$array = array();
			$tok = strtok ( $_POST ['check'], " " );
			
			while ( $tok != false ) {
				$array[] = $tok;
				$tok = strtok ( " " );
			}
			$user->check_out($connection, $array[0], $array[1]);
		} else {
			echo "Please select a book you want to check out";
		}
	}
	$db->close_connection ( $connection );
?>
</div>
<?php 
}
?>
<?php include "footer.php"; ?>