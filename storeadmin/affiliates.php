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

$malsquery = "SELECT MalsCart FROM " .$DB_Prefix ."_vars WHERE ID='1'";
$malsresult = mysqli_query($dblink, $malsquery) or die ("Unable to select. Try again later.");
$malsrow = mysqli_fetch_row($malsresult);
$malsid = $malsrow[0];

$setpg_lower = "affiliates";
$setpg_upper = ucfirst($setpg_lower);

if ($submit == "Activate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
if ($submit == "Deactivate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}

$getquery = "SELECT ID, Active FROM " .$DB_Prefix ."_pages WHERE PageName='$setpg_lower' AND PageType='optional'";
$getresult = mysqli_query($dblink, $getquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($getresult) == 1)
{
$getrow = mysqli_fetch_row($getresult);
$pgid = $getrow[0];
$setactive = $getrow[1];
}
if (!$setactive)
$setactive = "No";

if ($pgid AND $setactive == "Yes")
$show_aff_link = "<a href=\"pages.php?edit=yes&pgid=$pgid\">affiliate system</a>";
else
$show_aff_link = "affiliate system";

if ($setactive == "No")
{
?>

<form method="POST" action="affiliates.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">MTracker Affiliate Form Creation</td>
</tr>
<tr>
<td valign="middle">
<?php
echo "This $show_aff_link is <b>only</b> for those who use the ";
echo "<a href=\"mals.php?mode=af\">Mals-E</a> mTracker affiliate program.";
?> 
</td>
</tr>
<tr>
<td valign="middle">
Click the Activate button to auto-create forms that can be used with mTracker.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate Affiliates Page" name="submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
}
else
{
?>

<form method="POST" action="affiliates.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">MTracker Affiliate Form Creation</td>
</tr>
<tr>
<td valign="middle">
<?php
echo "Your site is set up to work with the Mals-E mTracker affiliate program. Please log in to your ";
echo "<a href=\"mals.php?mode=af\">Mals-E</a> mTracker affiliate area to activate mTracker, set links, ";
echo "track commissions and modify affiliate accounts. If you need help with mTracker, visit the ";
echo "Mals-E forums or contact the administration.";
?> 
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\" class=\"smalltext\">";
echo "<a href=\"pages.php?edit=yes&pgid=$pgid\">Contents</a> | ";
echo "<a href=\"affiliates.php?submit=Deactivate+Affiliates+Page\">Deactivate</a></p>";
}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
