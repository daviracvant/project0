<?php
//database class is doing the database connection. 
class Database {
	//open connection to the database.
	function open_connection() {
		$host = "vergil.u.washington.edu:64523";
		$username = "root";
		$password = "010388rc";
		$link = mysql_connect ( $host, $username, $password ) or die ( "connection fail" );
		return $link;
	}
	/* close connection to the database.
	 * param: the mysql connection link.
	 */
	function close_connection($connection) {
		$close = mysql_close($connection);
		return $close;
	}
	/* creating a database
	 * param: Db name.
	 */
	function create_db($dbName) {
		$db = mysql_query("CREATE DATABASE $dbName");
		return $db;
	}
	/* delete a database;
	 * param: Db name.
	 */
	function delete_db($dbName) {
		$db = mysql_query("DROP DATABASE $dbName");
	}
}
?>