<script language="php">
setcookie ("adminusr", "", 0, "/", "", 0);
setcookie ("adminpswd", "", 0, "/", "", 0);
setcookie ("adminmstr", "", 0, "/", "", 0);
</script>
<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
require_once("../stconfig.php");
include("includes/header.htm");
?>

<form method="POST" action="admin.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="right" class="fieldname">User Name:
</td>
<td valign="middle" align="left" class="fieldname"> 
<input name="adminuser" size="12">
</td>
</tr>
<tr>
<td valign="middle" align="right" class="fieldname">Password:&nbsp;
</td>
<td valign="middle" align="left" class="fieldname"> 
<input type="password" name="adminpass" size="12">
</td>
</tr>
<?php
if ($master == "y")
{
?>
<tr>
<td valign="middle" align="right" class="fieldname">Master Key:&nbsp;
</td>
<td valign="middle" align="left" class="fieldname"> 
<input type="password" name="adminmaster" size="12">
</td>
</tr>
<?php
}
?>
<tr>
<td valign="middle" align="center" colspan="2">
<input type="submit" class="button" value="Enter" name="Submit">
</td>
</tr>
<tr>
<td valign="bottom" align="right" class="smalltext" colspan="2"><a href="forgot.php">Forgot your info?</a>
</td>
</tr>
</table>
</center>
</div>

<?php
include("includes/footer.htm");
?>
</form>

</body>

