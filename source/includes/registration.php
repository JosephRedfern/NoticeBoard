<?php
if(!defined("INCLUDED")){
	die("No direct access, you sneaky sneaky sneakster.");
}
?>
<?php if(!isset($_POST['submit'])){
	include "registrationform.php";
}else{
	//TODO: Implement better validation. MUCH BETTER VALIDATION! with alerts and errthin... as in, not just a generic "INVALID OPTION" message
	if(strlen($_POST['username'])>1&&strlen($_POST['email'])>1&&strlen($_POST['fname'])>1&&strlen($_POST['lname'])>1&&iidIsValid($_POST['institution'])&&$_POST['pw1']==$_POST['pw2']){
		$username = mysql_real_escape_string($_POST['username']);
		$email = mysql_real_escape_string($_POST['email']);
		$fname = mysql_real_escape_string($_POST['fname']);
		$lname = mysql_real_escape_string($_POST['lname']);
		$iid  = mysql_real_escape_string($_POST['institution']);
		$pw1 = mysql_real_escape_string($_POST['pw1']);
		$pw2 = mysql_real_escape_string($_POST['pw2']);
		
		if(!usernameInUse($username)){
			if(!emailInUse($email)){
				if($pw1==$pw2){
					$password = md5($pw1.SALT);
					$query = "INSERT INTO users(username, email, fname, lname, password, iid) VALUES('$username', '$email', '$fname', '$lname', '$password', $iid)";
					if(mysql_query($query)){
					//TODO: Add email notification thingie
						echo "<p class=\"alert-message success\">Great Success! Welcome to NoteSlide</p>";
					}else{
						echo "<p class=\"alert-message error\">Oh dear... An error occured while creating your account!</p>";
						include "registrationform.php";
					}
				}else{
					echo "<p class=\"alert-message warning\">The passwords you entered didn't match. Please try again.</p>";
					include "registrationform.php";
				}
			}else{
				echo "<p class=\"alert-message warning\">The email address you entered is already associated with an account</p>";
				include "registrationform.php";
			}
		}else{
			echo "<p class=\"alert-message warning\">The username you chose is already in use... Try picking another!</p>";
			include "registrationform.php";
		}
	}else{
			echo "<p class=\"alert-message warning\">Please fill in all of the fields - Thanks!</p>";
			include "includes/registrationform.php";		
	}
}?>