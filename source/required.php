<?php
//Writen by Joseph Redfern -  joseph[at]redfern[dot]me
//http://blog.redfern.me/
//This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
//http://creativecommons.org/licenses/by-nc-sa/3.0/
session_start();
include "settings.php";
include "functions.php";
$conn = mysql_connect($mysql_server, $mysql_user, $mysql_password) OR DIE("Uh-oh! Database Connection Error:<p><pre>".mysql_error()."</pre></p>");
mysql_select_db($mysql_db, $conn) OR DIE("Oh heck. Database Selection error...: <p><pre>".mysql_error()."</pre></p>");


sanityChecks(); //check & correct any contradictions
processInstitutionSelection(); //checks if $_GET['iid'] is set. if it is, then check if iid is valid. if it is, then set it as session & redirect
processCourseSelection(); //if $_GET['cid'] is set, check if cid matches iid. if does, set cid session and redirect

checkLogout(); //check if logout is in url. if is, unset all sessions
checkLogin(); //check if login is in url. if it is, check post values against db. if valid, set sessions & redirect to index
?>