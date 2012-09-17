<?php
session_start();
include "database.php"

?>
<?php
//the login function, check the username and password, compare them with the db 
//and return result true or false.
function Login($username, $password) {
	$db = new Database ();
	$connection = $db->open_connection ();
	if (! $connection) {
		die ( 'Could not connect: ' . mysql_error () );
	}
	mysql_select_db("library", $connection);
	$query = " SELECT * FROM Username where Username='$username' and Password='$password'";
	$result = mysql_query ( $query ) or die (mysql_error());
	$num = mysql_num_rows ( $result );
	
	if ($num == 0) {
		return false;
	} else {
		$data = mysql_query("SELECT * FROM Username");
		while ($row = mysql_fetch_array($data)) {
			if ($username == $row['Username']) {
				$_SESSION ['Role'] = $row['Role'];
			}
		}
		return true;
	}
}
//check if the login button is press.
if (isset ( $_POST ['login'] )) {
	$username = $_POST ['username'];
	$password = $_POST ['password'];
	
	if (Login ( $username, $password )) {
		$_SESSION ['username'] = $username;
		//redirect to index.php.
	    header('location: index.php');
	} else {
		//including header to have the same header as other pages.
		include "header.php";
		echo "<div id=\"body_index\"> <h3> Incorrect username or password: </h3><a href =\"index.php\" > login </a></div>";
		include "footer.php";
	}
}
?>
