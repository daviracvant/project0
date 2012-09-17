<?php
include ("database.php");
class User {
	//a librarian can add a book to inventory. 
	function add_Book($connection, $isbn, $author, $title, $year) {
		mysql_select_db ( "library", $connection );
		$sql = "SELECT * FROM Book WHERE isbn = '$isbn'";
		$result = mysql_query ( $sql );
		$num = mysql_num_rows ( $result );
		if ($num == 0) {
			//if the book is not already in the db, then add to both, 
			//book and book staus. 
			mysql_query ( "INSERT INTO Book (isbn, author, title, year)
			VALUES ('$isbn', '$author', '$title', '$year')" );
			mysql_query ( "INSERT INTO Book_Status (isbn, copy, status)
			VALUES ('$isbn', 1, 'in')" );
			echo "A new book has been added";
		} else {
			//if the book is already in the db, then only add additional 
			//copy to the book status.
			$copy = 0;
			$result = mysql_query ( "SELECT copy FROM Book_Status WHERE isbn = '$isbn'" );
			while ( $row = mysql_fetch_array ( $result ) ) {
				if ($copy < $row ['copy']) {
					$copy = $row ['copy'];
				}
			}
			$copy += 1;
			echo "isbn already exist, a new copy has been added!";
			mysql_query ( "INSERT INTO Book_Status (isbn, copy, status)
			VALUES ('$isbn', $copy, 'in')" );
		}
		echo "<br /><a href=\"index.php\">Back to Homepage</a>";
	}
	
	//a librarian can add a patron. 
	function add_patron($connection, $LastName, $FirstName, $Address, $Email, $Acct_balance, $Zip, $UserName, $Password, $Role) {
		mysql_select_db ( "library", $connection );
		//check to see if the user already exist.
		$sql = "SELECT * FROM User WHERE LastName = '$LastName' and FirstName = '$FirstName'";
		$result = mysql_query ( $sql );
		$num = mysql_num_rows ( $result );
		if ($num == 0) {
			//insert into User.
			mysql_query ( "INSERT INTO User (LastName, FirstName, Address, Email, Acct_balance, Zip)
		VALUES ('$LastName', '$FirstName', '$Address', '$Email', $Acct_balance, $Zip)" );
			//insert into UserName,
			mysql_query ( "INSERT INTO Username(Username, Password, Role)
		VALUES ('$UserName', '$Password', '$Role')" );
			echo "User has been created";
		} else {
			echo "User already exist";
		}
		echo "<br /> <a href=\"index.php\">Back to Homepage</a>";
	}
	
	//display all the book.
	function display_book($connection) {
		mysql_select_db ( "library", $connection );
		$result = mysql_query ( "SELECT * FROM Book, Book_Status WHERE Book.isbn = Book_Status.isbn" );
		$num = mysql_num_rows ( $result );
		//display information to the browser using echo.
		if ($num == 0) {
			echo "<p> No result found </p>";
		} else {
			echo "<form method = \"POST\">";
			?>
<table id="table_2">
	<tr>
		<th align=left>ISBN</th>
		<th align=left>AUTHOR</th>
		<th align=left>TITLE</th>
		<th align=left>YEAR</th>
		<th align=left>COPY</th>
		<th align=left>STATUS</th>
	</tr>
			<?php
			while ( $row = mysql_fetch_array ( $result ) ) {
				$status = $row ['status'];
				if ($status == "in") {
					$isbn = $row ['isbn'];
					$copy = $row ['copy'];
					echo "<tr>";
					echo "<td width = 120><input type=\"radio\" name=\"check\" value = \"$isbn $copy\" />$isbn</td>";
					echo "<td width = 100>" . $row ['author'] . "</td> ";
					echo "<td width = 100>" . $row ['title'] . "</td>";
					echo "<td width = 100>" . $row ['year'] . "</td>";
					echo "<td width = 50>" . $row ['copy'] . "</td>";
					echo "<td width = 50>" . $row ['status'] . "</td>";
					echo "</tr>";
				} else {
					echo "<tr>";
					echo "<td width = 100>" . $row ['isbn'] . "</td> ";
					echo "<td width = 100>" . $row ['author'] . "</td> ";
					echo "<td width = 100>" . $row ['title'] . "</td>";
					echo "<td width = 100>" . $row ['year'] . "</td>";
					echo "<td width = 50>" . $row ['copy'] . "</td>";
					echo "<td width = 50>" . $row ['status'] . "</td>";
					echo "</tr>";
				
				}
			}
			?>
			</table>
<?php
			echo "<br /><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Check Out\" />";
			echo "</form>";
		
		}
	
	}
	//a librarian and patron can search for a book. 
	function search_book($connection, $keyword, $type) {
		mysql_select_db ( "library", $connection );
		if ($type == 'isbn') { //isbn
			$result = mysql_query ( "SELECT * FROM Book, Book_Status WHERE Book.isbn = Book_Status.isbn AND Book.isbn LIKE '%$keyword%'" );
		} else if ($type == 'author') { //author
			$result = mysql_query ( "SELECT * FROM Book, Book_Status WHERE Book.isbn = Book_Status.isbn AND Book.author LIKE '%$keyword%'" );
		} else if ($type == 'title') { //title
			$result = mysql_query ( "SELECT * FROM Book, Book_Status WHERE Book.isbn = Book_Status.isbn AND Book.title LIKE '%$keyword%'" );
		} else { //year
			$result = mysql_query ( "SELECT * FROM Book, Book_Status WHERE Book.isbn = Book_Status.isbn AND Book.year LIKE'%$keyword%'" );
		}
		$num = mysql_num_rows ( $result );
		//display information to the browser using echo.
		if ($num == 0) {
			echo "<p> No result found </p>";
		} else {
			echo "<form method = \"POST\">";
			?>
<table id="table_2">
	<tr>
		<th align=left>ISBN</th>
		<th align=left>AUTHOR</th>
		<th align=left>TITLE</th>
		<th align=left>YEAR</th>
		<th align=left>COPY</th>
		<th align=left>STATUS</th>
	</tr>
			<?php
			while ( $row = mysql_fetch_array ( $result ) ) {
				$status = $row ['status'];
				if ($status == "in") {
					$isbn = $row ['isbn'];
					$copy = $row ['copy'];
					echo "<tr>";
					echo "<td width = 120><input type=\"radio\" name=\"check\" value = \"$isbn $copy\" />$isbn</td>";
					echo "<td width = 100>" . $row ['author'] . "</td> ";
					echo "<td width = 100>" . $row ['title'] . "</td>";
					echo "<td width = 100>" . $row ['year'] . "</td>";
					echo "<td width = 50>" . $row ['copy'] . "</td>";
					echo "<td width = 50>" . $row ['status'] . "</td>";
					echo "</tr>";
				}
			}
			?>
			</table>
<?php
			echo "<br /><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Check Out\" />";
			echo "</form>";
		
		}
	}
	//display all the patron (librarian only). 
	function display_patron($connection) {
		mysql_select_db ( "library", $connection );
		$result = mysql_query ( "SELECT * FROM User, Username WHERE User.user_id = Username.user_id" );
		$num = mysql_num_rows ( $result );
		if ($num == 0) {
			echo "<p> There is no patron </p>";
		} else {
			echo "<form method = \"POST\">";
			?>
<table id="table_2">
	<tr>
		<th align=left>ID</th>
		<th align=left>LASTNAME</th>
		<th align=left>FIRSTNAME</th>
		<th align=left>USERNAME</th>
		<th align=left>ACCT_BALANCE</th>
	</tr>
			<?php
			while ( $row = mysql_fetch_array ( $result ) ) {
				$id = $row ['user_id'];
				echo "<tr>";
				echo "<td width = 120><input type=\"radio\" name=\"check\" value = \"$id\" />$id</td>";
				//echo "<td width = 100>" . $row ['user_id'] . "</td>";
				echo "<td width = 100>" . $row ['LastName'] . "</td> ";
				echo "<td width = 100>" . $row ['FirstName'] . "</td>";
				echo "<td width = 100>" . $row ['Username'] . "</td>";
				echo "<td width = 100>" . $row ['Acct_balance'] . "</td>";
				echo "</tr>";
			}
			?>
			</table>
<?php
			echo "<br /><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Remove\" />";
			echo "</form>";
		}
	}
	//a librariann and patron can search for a patron. 
	function search_patron($connection, $keyword, $type) {
		mysql_select_db ( "library", $connection );
		if ($type == 'lastname') {
			$result = mysql_query ( "SELECT * FROM User, Username WHERE User.user_id = Username.user_id 
			AND User.LastName LIKE '%$keyword%'" );
		} else if ($type == 'firstname') {
			$result = mysql_query ( "SELECT * FROM User, Username WHERE User.user_id = Username.user_id 
			AND User.FirstName LIKE '%$keyword%'" );
		} else {
			$result = mysql_query ( "SELECT * FROM User, Username WHERE User.user_id = Username.user_id 
			AND Username.Username LIKE '%$keyword%'" );
		}
		$num = mysql_num_rows ( $result );
		//display information to the browser using echo.
		if ($num == 0) {
			echo "<p> No result found </p>";
		} else {
			echo "<form method = \"POST\">";
			?>
<table id="table_2">
	<tr>
		<th align=left>ID</th>
		<th align=left>LASTNAME</th>
		<th align=left>FIRSTNAME</th>
		<th align=left>USERNAME</th>
		<th align=left>ACCT_BALANCE</th>
	</tr>
			<?php
			while ( $row = mysql_fetch_array ( $result ) ) {
				$id = $row ['user_id'];
				echo "<tr>";
				echo "<td width = 120><input type=\"radio\" name=\"check\" value = \"$id\" />$id</td>";
				echo "<td width = 100>" . $row ['LastName'] . "</td> ";
				echo "<td width = 100>" . $row ['FirstName'] . "</td>";
				echo "<td width = 100>" . $row ['Username'] . "</td>";
				echo "<td width = 100>" . $row ['Acct_balance'] . "</td>";
				echo "</tr>";
			}
			?>
			</table>
<?php
			echo "<br /><input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Remove\" />";
			echo "</form>";
		}
	}
	//deleting a patron. at the same time we need to remove the password and user name.
	function remove_patron($connection, $id) {
		mysql_select_db ( "library", $connection );
		$current_user_id = self::getCurrentUserId($_SESSION ['username']);
		//you can't remove yourself.
		if ($id != $current_user_id) {
			//put all the book from this user back. 
			$result = mysql_query( "SELECT * FROM Book_Status, Book_date WHERE Book_Status.isbn = Book_date.isbn
			AND Book_Status.copy = Book_date.copy AND Book_date.user_id = $id");
			$num = mysql_num_rows($result);
			if ($num != 0) {
				while ($row = mysql_fetch_array($result)) {
					$status = $row ['status'];
					if ($status == "out") {
						$isbn = $row ['isbn'];
						$copy = $row ['copy'];
						mysql_query ( "UPDATE Book_Status SET status='in'
						WHERE isbn='$isbn' AND copy='$copy'" );
					}
				}
			}
			//mysql_query(" UPDATE Book_Status SET status='out'");
			mysql_query ( " DELETE FROM User WHERE user_id = $id" );
			mysql_query ( " DELETE FROM Username WHERE user_id = $id" );
			mysql_query ( " DELETE FROM Book_date WHERE user_id = $id" );
			echo "Remove Successful";
		}
	}
	//a librarian and patron can check out a book. 
	//the total number of book in the library would go down by one.
	//the status of the book would be stored in the book status.
	function check_out($connection, $isbn, $copy) {
		date_default_timezone_set ( 'UTC' );
		mysql_select_db ( "library", $connection );
		//update the book status.
		mysql_query ( "UPDATE Book_Status SET status='out'
		WHERE isbn='$isbn' AND copy='$copy'" );
		$username = $_SESSION ['username'];
		$id = self::getCurrentUserId ( $username );
		//due day is a week, year/month/day for sql.
		$due_date = date('Y-m-d', strtotime("+7 day"));
		
		mysql_query ( "INSERT INTO Book_date (isbn, copy, user_id, book_date)
		VALUES ('$isbn', $copy, $id, '$due_date')" );
		echo "Check Out Successful";
	}
	
	//a librarian and patron can check in a book. 
	function check_in($connection, $isbn, $copy, $due_date) {
		date_default_timezone_set ( 'UTC' );
		mysql_select_db ( "library", $connection );
		//get the current date.. compare it with the due date
		$day = date ( "d" );
		$month = date ( "m" );
		$year = date ( "y" );
		$today = $year . $month . $day;
		$checkin_date = date ( "YW", strtotime ( "$today" ) );
		$array = array ();
		$tok = strtok ( $due_date, "-" );
		while ( $tok != false ) {
			$array [] = $tok;
			$tok = strtok ( "-" );
		}
		$due_date = $array [0] . $array [1] . $array [2];
		$due_date = date ( "YW", strtotime ( "$due_date" ) );
		//check to see if the check in date is passing the due date or not.
		if ($checkin_date > $due_date) {
			$fee = 2;
			$id = self::getCurrentUserId ( $_SESSION ['username'] );
			$result = mysql_query ( "SELECT * FROM User WHERE user_id = $id" );
			while ( $row = mysql_fetch_array ( $result ) ) {
				$balance = $row ['Acct_balance'];
			}
			$new_balance = $balance + $fee;
			mysql_query ( "UPDATE User SET Acct_balance = $new_balance
			 WHERE user_id = $id" );
		}
		//update the book status.
		mysql_query ( "UPDATE Book_Status SET status='in'
		WHERE isbn='$isbn' AND copy='$copy'" );
		//remove the date.
		mysql_query ( "DELETE FROM Book_date WHERE isbn = '$isbn'
		AND copy ='$copy'");
		echo "Check in Successful";
	}
	
	//helper method, return the current user id base on current username. 
	function getCurrentUserId($curr_username) {
		$result = mysql_query ( "SELECT * FROM Username WHERE Username = '$curr_username'" );
		$num = mysql_num_rows ( $result );
		if ($num != 0) {
			while ( $row = mysql_fetch_array ( $result ) ) {
				$id = $row ['user_id'];
			}
		}
		return $id;
	}
}
?>
