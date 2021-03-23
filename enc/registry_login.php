<p>Enter your user name and password to log in. 
Not yet registered? 
<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");
echo "<a href=\"registry.$pageext?show=register$addlink\">Sign up here</a>."; ?>
</p>
<form action="<?php echo "registry.$pageext"; ?>" method="POST">
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td valign="top" align="right">User Name:</td>
<td valign="top" align="left"><input type="text" name="wshusr" size="10"></td>
</tr>
<tr>
<td valign="top" align="right">Password:</td>
<td valign="top" align="left"><input type="password" name="wshpsd" size="10"></td>
</tr>
<tr>
<td colspan="2" valign="top" align="center">
<input type="hidden" name="mode" value="login">
<?php echo "$addhidden"; ?>
<input type="submit" value="Log In" name="submit" class="formbutton">
</td>
</tr>
</table>
</form>
<p align="center">
<?php echo "<a href=\"registry.$pageext?show=forgot$addlink\">Forgot your password?</a>"; ?>
</p>
