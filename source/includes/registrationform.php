<?php
if(!defined("INCLUDED")){
	die("No direct access, you sneaky sneaky sneakster.");
}
?>
<form action="?register" name="registerform" method="POST"><fieldset>
<label for="username">Username  </label> <input type="text" name="username" id="username"/><br /><br />
<label for="fname">First Name  </label> <input type="text" name="fname" id="fname"/><br /><br />
<label for="lname">Last Name  </label> <input type="text" name="lname" id="lname"/><br /><br />
<label for="email">Email Address  </label> <input type="text" name="email" id="email"/><br /><br />
<label for="institution">University  </label> <select name="institution" id="institution">
<?php
foreach(returnInstitutions() as $iid=>$name){
	echo "<option value=\"".$iid."\">".$name."</option>";
}

?>
</select><br /><br />
<label for="pw1">Password  </label> <input type="password" name="pw1" id="pw1"><br /><br />
<label for="pw2">Password (again)  </label> <input type="password" name="pw2" id="pw2"><br /><br />
<label for="rbutton"></label><input id="rbutton" type="submit" name="submit" value="Register">
</fieldset>
</form>