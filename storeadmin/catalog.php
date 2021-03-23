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
?>

<form method="POST" action="includes/itemcatalog.php" target="_blank">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="2">Product Reports</td>
</tr>
<tr>
<td valign="middle" align="center" colspan="2">Print out a catalog you can distribute to customers.</td>
</tr>
<tr>
<td valign="middle" align="right" width="35%">
<input type="checkbox" name="showimages" value="yes"> 
</td>
<td valign="middle" align="left" width="65%">
 Show Images in Catalog
</td>
</tr>
<tr>
<td valign="middle" align="right" width="35%">
<input type="checkbox" name="splitoptions" value="yes"> 
</td>
<td valign="middle" align="left" width="65%">
 Split First Product Options
</td>
</tr>
<tr>
<td valign="middle" align="center" colspan="2">
<input type="submit" class="button" value="View Report" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<form method="POST" action="orders.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">Catalog Details</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="hidden" name="ret" value="catalog">
<input type="submit" class="button" value="Update Details" name="mode"> 
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
