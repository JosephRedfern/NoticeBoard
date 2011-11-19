<?php
if(!defined("INCLUDED")){
	die("No direct access, you sneaky sneaky sneakster.");
}
?>
<?php
if(!isset($_POST['submit'])){
include "postform.php"; 
}else{
	if(strlen($_POST['title'])>0&&strlen($_POST['description'])>0&&strlen($_POST['fulltext'])>0){
		$title = mysql_real_escape_string($_POST['title']);
		$description = mysql_real_escape_string($_POST['description']);
		$fulltext = mysql_real_escape_string($_POST['fulltext']);
		$url = mysql_real_escape_string($_POST['fulltext']);
		$uid = mysql_real_escape_string($_SESSION['uid']);
		$iid = mysql_real_escape_string($_SESSION['iid']);
		$query = "INSERT INTO posts (`iid`, `title`, `description`, `fulltext`, `url`, `uid`) VALUES ($iid, '$title', '$description', '$fulltext', '$url', $uid)";
		if(mysql_query($query)){
			echo "<p class=\"alert-message success\">Your post has been added. Hold on tight - we are re-directing you to it.</p>";
			echo "actually, redirection isn't implemented yet. trololol";
		}else{
			echo "<p class=\"alert-message error\">Oh dear... An error has occured. Please try again!</p>";
			echo "<pre>$query</pre>";
			include "postform.php";
		}
	}else{
		echo "<p class=\"alert-message warning\">You need to fill in every box apart from URL. Please try again!</p>";
		echo "In the final release the form will have your previous values pre-populated";
		include "postform.php";
	}
}
?>