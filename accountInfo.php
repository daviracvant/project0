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
<div id="body_account_index">
<h3>Book Checked Out by you:</h3>
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
	$result = mysql_query ( "SELECT * FROM Book, Book_Status, Book_date WHERE Book.isbn = Book_Status.isbn 
	AND Book_Status.isbn = Book_date.isbn AND Book_Status.copy = Book_date.copy AND Book_date.user_id = $id" );
	
	$num_dis_book = 0;
	while ( $row = mysql_fetch_array ( $result ) ) {
		$status = $row ['status'];
		if ($status == 'out') {
			if ($num_dis_book == 0) {
				?>
				<table id="table_2">
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
			echo "<td width = 100>" . $row ['isbn'] . "</td>";
			echo "<td width = 100>" . $row ['author'] . "</td> ";
			echo "<td width = 100>" . $row ['year'] . "</td> ";
			echo "<td width = 100>" . $row ['copy'] . "</td> ";
			echo "<td width = 100>" . $due_date . "</td>";
			echo "</tr>";
			$num_dis_book += 1;
		}
	}
	if ($num_dis_book == 0) {
		echo "<p> You have not check out anything </p>";
	}
	?>
	</table>
	<h3>Book Checked Out by other user:</h3>
	<?php 
	$rs = mysql_query ( "SELECT * FROM Book, Book_Status, Book_date WHERE Book.isbn = Book_Status.isbn 
	AND Book_Status.isbn = Book_date.isbn AND Book_Status.copy = Book_date.copy AND Book_date.user_id <> $id" );
	$num_dis_book = 0;
	while ( $row = mysql_fetch_array ( $rs ) ) {
		$status = $row ['status'];
		if ($status == 'out') {
			if ($num_dis_book == 0) {
				?>
				<table id="table_2">
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
			echo "<td width = 100>" . $row ['isbn'] . "</td>";
			echo "<td width = 100>" . $row ['author'] . "</td> ";
			echo "<td width = 100>" . $row ['year'] . "</td> ";
			echo "<td width = 100>" . $row ['copy'] . "</td> ";
			echo "<td width = 100>" . $due_date . "</td>";
			echo "</tr>";
			$num_dis_book += 1;
		}
	}
	if ($num_dis_book == 0) {
		echo "<p> Other users have not check out anything </p>";
	}
	?>
	</table>
<?php
}
?>
</div>
<?php
include "footer.php";
?>