<?php
if(!defined("INCLUDED")){
	die("No direct access, you sneaky sneaky sneakster.");
}
?>
<h2>Create a Post</h2>
<br />
<form action="?addPost" name="postform" method="POST"><frameset>
<label for="title">Title  </label> <input type="text" name="title" id="title"/><br /><br />
<label for="description">Brief Description (less than 140 characters)  </label> <textarea name="description" class="xxlarge" rows="3"></textarea><br /><br />
<label for="fulltext">Full Description  </label> <textarea name="fulltext" class="xxlarge" rows="12"></textarea><br /><br />
<label for="url">URL (if applicable)  </label><input type="text" name="url" id="url"/> <br /><br />
<label for="postbutton"></label><input id="postbutton" type="submit" name="submit" value="Post"/>
</frameset>
</form>