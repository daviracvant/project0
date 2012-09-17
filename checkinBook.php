<?php
session_start ();
if (! isset ( $_SESSION ['username'] )) {
	header ( "Location: index.php" );
} else {
	include "header.php";
	?>
<div id="logout">
<?php
	echo "welcome " . $_SESSION ['username'];
	?>
<a href="Logout.php"> Logout </a></div>

<div id="body_index">
<?php
	include "User.php";
	$db = new Database ();
	$connection = $db->open_connection ();
	if (! $connection) {
		die ( 'Could not connect: ' . mysql_error () );
	}
	$user = new User ();
	mysql_select_db ( "library", $connection );
	$curr_username = $_SESSION ['username'];
	$id = $user->getCurrentUserId ( $curr_username );
	$rs = mysql_query ( "SELECT DISTINCT * FROM Book, Book_Status, Book_date WHERE Book.isbn = Book_Status.isbn 
	AND Book_Status.isbn = Book_date.isbn AND Book_Status.copy = Book_date.copy AND Book_date.user_id = $id" );
	$num_dis_book = 0;
	while ( $row = mysql_fetch_array ( $rs ) ) {
		$status = $row ['status'];
		$isbn = $row ['isbn'];
		$copy = $row ['copy'];
		if ($status == 'out') {
			if ($num_dis_book == 0) {
				?>
				<form method="POST">
				<h3> Check in book</h3>
<table id= "table_2">
	<tr>
		<th align=left>ISBN</th>
		<th align=left>AUTHOR</th>
		<th align=left>YEAR</th>
		<th align=left>COPY</th>
		<th align=left>DUE_DATE</th>
	</tr>
	<?php
			}
			$due_date = $row ['book_date'];
			echo "<tr>";
			echo "<td width = 120><input type=\"radio\" name=\"check\" value = \"$isbn $copy $due_date\" />$isbn</td>";
			echo "<td width = 100>" . $row ['author'] . "</td> ";
			echo "<td width = 100>" . $row ['year'] . "</td> ";
			echo "<td width = 100>" . $row ['copy'] . "</td> ";
			echo "<td width = 100>" . $due_date . "</td>";
			echo "</tr>";
			$num_dis_book += 1;
		}
	}
	if ($num_dis_book == 0) {
		echo "<p> You don't have anything to check in </p>";
	}
	?>
	</table>
<?php
	echo "<br /><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Check In\" />";
	?>
	</form>
	<?php 
	if (isset ( $_POST ['submit'] )) {
		if (isset ( $_POST ['check'] )) {
			//check will get the value of the radio. this case it would give me 
			//the isbn and the copy.
			$array = array ();
			$tok = strtok ( $_POST ['check'], " " );
			
			while ( $tok != false ) {
				$array [] = $tok;
				$tok = strtok ( " " );
			}
			//passing in the isbn copy and due date.
			$user->check_in ( $connection, $array [0], $array [1], $array [2] );
		} else {
			echo "Please select a book you want to check out";
		}
	}
}
?>
</div>
<?php
include "footer.php";
?>