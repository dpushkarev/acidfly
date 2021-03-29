<?php include("includes/pswdcookie.php"); ?>
<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php 
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php"); ?>

<form method="POST" action="password.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="2">Change Password:</td>
</tr>
<?php
if ($scs == "y")
{
echo "<tr>";
echo "<td valign=\"middle\" align=\"center\" colspan=\"2\">";
echo "Your password has been updated. Please make a note of it.</td>";
echo "</tr>";
}
else
{
if ($pssmode == "missing")
{
echo "<tr>";
echo "<td valign=\"middle\" align=\"center\" colspan=\"2\" class=\"error\">";
echo "The new password and the retyped password did not match. ";
echo "Please try again.</td>";
echo "</tr>";
}
?>
<tr>
<td valign="middle" align="center" colspan="2">
To change your administration password, type in the new password, retype the 
new password, then submit. Your new password will take effect immediately.</td>
</tr>
<tr>
<td valign="middle" align="right" class="fieldname">New Password:</td>
<td valign="middle" align="left" class="fieldname"><input type="password" name="newpass" size="12" maxLength="12"></td>
</tr>
<tr>
<td valign="middle" align="right" class="fieldname">Retype Password:</td>
<td valign="middle" align="left" class="fieldname"><input type="password" name="retypepass" size="12" maxLength="12"></td>
</tr>
<tr>
<td valign="middle" align="center" class="fieldname" colspan="2">
<input type="hidden" value="update" name="pmode">
<input type="submit" class="button" value="Change Password" name="Submit">
</td>
</tr>
<?php
}
?>
</table>
</center>
</div>
</form>

<?php 
include("includes/links2.php");
include("includes/footer.htm"); ?>
</body>

</html>
