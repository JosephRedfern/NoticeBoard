<?php
function pageTitle(){
	if(isset($_SESSION['iid'])){
		$institutionName = institutionNameFromIid($_SESSION['iid']);
		$prefix = "NoteSlide - ".$institutionName;
	}else{
	$prefix = "NoteSlide";
	}
	if(isset($_GET['action'])){
		switch($_GET['action']){
			case "postnotice":
				return $prefix." - Post Notice";
				break;
			default:
				return $prefix;
		}
	}else{
		return $prefix;
	}
}


function sanityChecks(){
	if(isset($_SESSION['iid'])&&isset($_SESSION['pwerror'])){
		unset($_SESSION['pwerror']);
	}
}

function showVariableMenuItems(){
	if(isset($_SESSION['uid'])){
		echo "<li><a href=\"?addPost\">Add Post</a></li>";
		echo "<li><a href=\"#myaccount\">My Account</a></li>";
		echo "<li><a href=\"?logout\">Logout</a></li>";
	}else{
		echo "<li><a href=\"?what\">What is this?</a></li>";
		echo "<li><a href=\"?register\">Register</a></li>";
	}
}

function checkLogin(){
	if(isset($_GET['login'])){
		$result = doLogin();
		return $result;
	}
}

function checkLogout(){
	if(isset($_GET['logout'])){
		session_unset();
	}
}

function checkError(){
	if(isset($_GET['pwerror'])){
		if(!isset($_SESSION['iid'])){
			return "Username/Password error - please try again";
		}
	}
}

function loginArea(){
	if(isset($_SESSION['uid'])){
		echo "<div class=\"pull-right loginarea\"><h5>Welcome, ".$_SESSION["fname"]." ".$_SESSION["lname"]."</h5></div>";
	}else{
		echo "<div class=\"pull-right\"><form action=\"?login\" method=\"POST\">
            <input class=\"input-small\" type=\"text\" placeholder=\"Username\" name=\"username\">
            <input class=\"input-small\" type=\"password\" placeholder=\"Password\" name=\"password\">
            <button class=\"btn\" type=\"submit\">Sign in</button>
          </form></div>";
	}
}

function doLogin(){
	if(isset($_POST['username'])&&isset($_POST['password'])){
		$username = mysql_real_escape_string($_POST['username']);
		$password = md5(mysql_real_escape_string($_POST['password']).SALT);
		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$result = mysql_query($query);
		if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
			$_SESSION["uid"] = $row["uid"];
			$_SESSION["iid"] = $row["iid"];
			//$_SESSION["cid"] = $row["cid"]; //not implemented
			$_SESSION["username"] = $row["username"];
			$_SESSION["fname"] = $row["fname"];
			$_SESSION["lname"] = $row["lname"];
			header("Location: index.php");
		}else{
			$_SESSION["pwerror"] = TRUE;
			header("Location: index.php");
		}
	}else{
		$_SESSION["pwerror"] = TRUE;
		header("Location: index.php");

	}
}

function viewPost($pid){
	$pid = mysql_real_escape_string($pid);
	if(!isset($_SESSION['admin'])||isset($_SESSION['uid'])){
		$query = "SELECT * FROM posts WHERE visible=1 AND pid=".$pid." AND iid=".$_SESSION['iid'];
		$result = mysql_query($query);
		if(mysql_num_rows($result)==0){
			echo "<p><span class=\"label important\">Error</span> The requested post cannont be found! Sorry!</p>";
		}else{
			$row = mysql_fetch_array($result);
			echo "<div class=\"post\">";
			echo "<h1>".$row["title"]."</h1>";
			echo "<h5>Posted By <a href=\"#\">".fullNameFromUid($row["uid"])."</a> (".institutionNameFromUid($row["uid"]).")</h2>";
			echo "<p class=\"postFulltext\"><h4>Description: </h4>".$row["fulltext"]."</p>";
			echo "</div>";
		}
	}else{
		echo "<p><span class=\"label important\">Error</span> You need to log in before you can view posts</p>";
	}
}

function institutionNameFromIid($iid){
	$iid = mysql_real_escape_string($iid);
	$query = "SELECT name FROM institutions WHERE iid=$iid LIMIT 1";
	$result = mysql_query($query);
	if(mysql_num_rows($result)>0){
	$row = mysql_fetch_row($result);
	return $row[0];
	}else{
		return FALSE;
	}
}

function institutionNameFromUid($uid){
	$uid = mysql_real_escape_string($uid);
	$query = "SELECT iid FROM users WHERE uid=$uid LIMIT 1";
	$result = mysql_query($query);
	if(mysql_num_rows($result)>0){
	$row = mysql_fetch_row($result);
	return institutionNameFromIid($row[0]);
	}else{
		return FALSE;
	}
}

function courseNameFromCid($cid){
	$cid = mysql_real_escape_string($cid);
	$query = "SELECT name FROM courses WHERE cid=$cid LIMIT 1";
	$result = mysql_query($query);
	if(mysql_num_rows($result)>0){
	$row = mysql_fetch_row($result);
	return $row[0];
	}else{
		return FALSE;
	}
}

function returnInstitutions(){
	$query = "SELECT iid, name FROM institutions ORDER BY name";
	$result = mysql_query($query);
	$institutions = array();
	while($row = mysql_fetch_array($result)){
		$institutions[$row[0]]=$row[1];
	}
	return $institutions;
}

function usernameInUse($username){
	$username = mysql_real_escape_string($username);
	$query = "SELECT * FROM users WHERE username='$username'";
	$result = mysql_query($query);
	if(mysql_num_rows($result)==0){
		return false;
	}else{
		return true;
	}
}

function fullNameFromUid($uid){
	$uid = mysql_real_escape_string($uid);
	$query = "SELECT fname, lname FROM users WHERE uid=$uid";
	$result = mysql_query($query);
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_array($result);
		return $row["fname"]." ".$row["lname"];
	}
}

function emailInUse($email){
	$email = mysql_real_escape_string($email);
	$query = "SELECT * FROM users WHERE email='$email'";
	$result = mysql_query($query);
	if(mysql_num_rows($result)==0){
		return false;
	}else{
		return true;
	}
}

/*
function processInstitutionSelection(){
	if(isset($_GET['iid'])){
		$iid = mysql_real_escape_string($_GET['iid']);
		$query = "SELECT iid FROM institutions WHERE iid=$iid";
		$result = mysql_query($query);
		if(mysql_num_rows($result)>0){
			$_SESSION['iid'] = $iid;
			header("Location: index.php"); //redirect to index.php. in future, make redirection back to previous page (perhaps?)
		}
	}
}
*/

function processCourseSelection(){
	if(isset($_GET['cid'])){
		$cid = mysql_real_escape_string($_GET['cid']);
		$iid = mysql_real_escape_string($_SESSION['iid']);
		$query = "SELECT cid FROM courses WHERE cid=$cid AND iid=$iid";
		$result = mysql_query($query);
		if(mysql_num_rows($result)>0){
			$_SESSION['cid'] = $cid;
			header("Location: index.php"); //redirect to index.php. in future, make redirection back to previous page (perhaps?)
		}
	}
}

function pageHeader(){
	$defaultheader = "<h1>NoteSlide <small>The online noticeboard for Universities</small></h1>";
	if(isset($_GET['viewPost'])){
		echo "<h3><a href=\"index.php\">< Back to Listings</a></h3>";
	}else{
	if(isset($_SESSION['iid'])){
		$iid = mysql_real_escape_string($_SESSION['iid']);
		if(institutionNameFromIid($iid)){
			echo "<h2>".institutionNameFromIid($iid);
			if(isset($_SESSION['cid'])){
				if(courseNameFromCid($_SESSION['cid'])){
					echo " - ".courseNameFromCid($_SESSION['cid'])."</h2>";
				}else{
					echo "</h2>";
				}
			}else{
				echo "</h2>";
			}
		}else{
			echo $defaultheader;
		}
		
	}else{
		echo $defaultheader;
	}
	}
}

function listInstitutions(){
	$query = "SELECT * FROM institutions";
	$result = mysql_query($query);
	if(mysql_num_rows($result)!==0){
	echo "<ul>";
	while($row = mysql_fetch_array($result)){
		echo "<li><a href=\"?iid=".$row["iid"]."\">".$row["name"]."</a></li>";
	}
	echo "</ul>";
	}else{
		echo "<ul><li>Oops! No Uni's</li></ul>";
	}
}

function listCourses($iid){
	$iid = mysql_real_escape_string($iid);
	$query = "SELECT * FROM courses WHERE iid=$iid";
	$result = mysql_query($query) OR DIE(mysql_error());
	if(mysql_num_rows($result)!==0){
	echo "<ul>";
	while($row = mysql_fetch_array($result)){
		echo "<li><a href=\"?cid=".$row["iid"]."\">".$row["name"]."</a></li>";
	}
	echo "</ul>";
	}else{
		echo "<ul><li>Oops! No courses have been added for your Uni...</li></ul>";
	}
}

function listPosts($n=10, $offset=0){
	if(isset($_SESSION['iid'])){
		outputPostsByInstitution($_SESSION['iid'], $n, $offset);
	}else{
		echo "You need to select a University before you can view posts. Please choose one from the menu on the right";
	}
}

function iidIsValid($iid){
	$iid = mysql_real_escape_string($iid);
	$query = "SELECT iid FROM institutions WHERE iid=$iid";
	$result = mysql_query($query);
	if(mysql_num_rows($result)>0){
		return true;
	}else{
		return false;
	}
}

function cidIsValid($cid){
	$cid = mysql_real_escape_string($iid);
	$query = "SELECT cid FROM courses WHERE iid=$cid";
	$result = mysql_query($query);
	if(mysql_num_rows($result)>0){
		return true;
	}else{
		return false;
	}
}

function mainPage(){
	if(isset($_SESSION['iid'])){
		if(iidIsValid($_SESSION['iid'])&&!isset($_GET['viewPost'])&&!isset($_GET['addPost'])){
			echo "<h2>Latest Posts</h2>";
			outputPostsByInstitution($_SESSION['iid']);
		}else{
			if(isset($_GET['viewPost'])){
				viewPost($_GET['viewPost']);
			}else{
				if(isset($_GET['addPost'])){
					include "includes/post.php";
				}
			}
		}
	}else{
	if(isset($_SESSION['pwerror'])){
		echo "<h2>Password Error</h2>";
		echo "<p>The username/password combination you entered is invalid. Please try again!</p>";
		session_destroy();
	}else{
		if(isset($_GET['register'])){
			include "includes/registration.php";
		}else{
		echo "<h2>Welcome to NoteSlide</h2>";
		echo "<p>Welcome to NoteSlide, the Online message board for Universities across the UK. To get started, either Sign In, or <a href=\"?register\">register for an account</a></p>";
			}
			
}
}
}

function outputPostsByInstitution($iid, $n=10, $offset=0){
	$n = mysql_real_escape_string($n);
	$iid = mysql_real_escape_string($iid);
	$query = "SELECT * FROM posts WHERE visible=1 AND iid=$iid ORDER BY `timestamp` DESC LIMIT $offset,$n";
	$result = mysql_query($query) OR DIE(mysql_error());
	while($post = mysql_fetch_array($result)){
		echo "<p class=\"post\">";
		echo "<h3><a href=\"?viewPost=".$post['pid']."\">".$post['title']."</a><small> - ".date("j/n/y", strtotime($post['timestamp']))."</small></h3>";
		echo "<small>".$post['description']."</small>";
		echo "</p>";
	}
}

function outputSidebar(){
	/*if(isset($_SESSION['iid'])){
		if(!isset($_SESSION['cid'])){ //CHANGE THIS LINE ONCE CID IS IMPLEMENTED!
		
		}else{
		echo "<div class=\"span4\">";
	    echo "<h3>Choose your Course</h3>";
	    listCourses($_SESSION['iid']);
	    echo "</div>";
		}
	}else{
		echo "<div class=\"span4\">";
	    echo "<h3>Choose your Uni</h3>";
	    listInstitutions();
	    echo "</div>";
	}*/
}
?>