<script language="php">include("includes/admncookie.php");</script>
<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

if (isset($_COOKIE['adminmstr']) AND $_COOKIE['adminmstr'] == $Master_Key)
{
?>
<form method="POST" action="permissions.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="center" class="fieldname">
Set Permissions
</td>
</tr>
<tr>
<td valign="top" align="left">
The master administrator can set the general administrator's permission levels for different features of this site. Please note: the master administrator will still be
able to access all areas of the site, regardless of the permissions set.
</td>
</tr>
<tr>
<td valign="top" align="center">
<input type="submit" value="Set Permissions" class="button" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}

// Show initial startup page
include("includes/initial.htm");

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
