<? 
session_start();
if (! isset ( $_SESSION ['username']) ){
	die("We can't display the page your are requesting");
} 
unset($_SESSION['username']);
include("header.php");   
?>
<div id="body_index">
<h3> Please Login to Continue: </h3>
<a href ="index.php" > login </a>
</div>
<?php include "footer.php"?>