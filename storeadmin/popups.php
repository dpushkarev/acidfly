<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
<script language="php">include("includes/htmlarea.php");</script>
</head>

<body<script language="php">$java = "popupcontent"; include("includes/htmlareabody.php");</script>>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

// Add or edit popups
if ($popup == "Save")
{
$pagetitle = addslash_mq($pagetitle);
$popupcontent = addslash_mq($popupcontent);
if ($popupid)
{
$updquery = "UPDATE " .$DB_Prefix ."_popups SET PageTitle='$pagetitle', Content='$popupcontent' WHERE ID='$popupid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
else
{
$insquery = "INSERT INTO " .$DB_Prefix ."_popups (PageTitle, Content) VALUES ('$pagetitle', '$popupcontent')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
}
}

// Delete pop ups
if ($popup == "Yes" AND $popupid)
{
$delquery = "DELETE FROM " .$DB_Prefix ."_popups WHERE ID='$popupid'";
$delresult = mysql_query($delquery, $dblink) or die("Unable to delete. Please try again later.");
$updquery = "UPDATE " .$DB_Prefix ."_items SET PopUpPg='' WHERE PopUpPg='$popuppg'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}

// Start tables
if ($popup == "Add" OR $popup == "Edit")
{
if ($popup == "Edit" AND $popupid)
{
$getpgquery = "SELECT * FROM " .$DB_Prefix ."_popups WHERE ID='$popupid'";
$getpgresult = mysql_query($getpgquery, $dblink) or die ("Unable to select popups. Try again later.");
if (mysql_num_rows($getpgresult) == 1)
{
$getpgrow = mysql_fetch_array($getpgresult);
$pagetitle = $getpgrow[PageTitle];
$popupcontent = stripslashes($getpgrow[Content]);
}
}
else
{
$pagetitle = "";
$popupcontent = "";
}
?>
<form method="POST" action="popups.php" name="Popups">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname" colspan="6">
<?php
if ($popup == "Edit" AND $popupid)
echo "Edit: $pagetitle";
else
echo "Add New Pop Up";
?>
</td>
</tr>
<tr>
<td align="right" valign="top">Page Title:</td>
<td align="left" valign="top">
<input type="text" name="pagetitle" value="<?php echo "$pagetitle"; ?>" size="53" maxLength="50">
</td>
</tr>
<tr>
<td align="right" valign="top">Content:</td>
<td align="left" valign="top">
<textarea rows="12" name="popupcontent" id="popupcontent" cols="45">
<?php echo "$popupcontent"; ?>
</textarea>
</td>
</tr>
<tr>
<td align="center" valign="top" colspan="2">
<?php
if ($popup == "Edit" AND $popupid)
echo "<input type=\"hidden\" value=\"$popupid\" name=\"popupid\">";
?>
<input type="submit" class="button" value="Save" name="popup">
</td>
</tr>
</table>
</center>
</div>
</form>
<p align="center"><a href="popups.php">Back to Pages</a></p>
<?php
}

else if ($popup == "Delete" AND $popupid)
{
$getpgquery = "SELECT PageTitle FROM " .$DB_Prefix ."_popups WHERE ID='$popupid'";
$getpgresult = mysql_query($getpgquery, $dblink) or die ("Unable to delete. Try again later.");
$getpgnum = mysql_num_rows($getpgresult);
if ($getpgnum == 1)
{
$getpgrow = mysql_fetch_array($getpgresult);
$pgname = stripslashes($getpgrow[PageTitle]);
}
?>
<form method="POST" action="popups.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname"><?php echo "Delete: $pgname"; ?></td>
</tr>
<tr>
<td align="center">Do you want to delete this pop up page? This action is permanent, and you cannot recover 
the page or content in the future.</td>
</tr>
<tr>
<td align="center">
<?php
echo "<input type=\"hidden\" value=\"$popupid\" name=\"popupid\">";
echo "<input type=\"hidden\" value=\"$getpgrow[PageTitle]\" name=\"popuppg\">";
?>
<input type="submit" class="button" value="Yes" name="popup"> 
<input type="submit" class="button" value="No" name="popup">
</td>
</tr>
</table>
</center>
</div>
</form>
<p align="center"><a href="popups.php">Back to Pages</a></p>
<?php
}

else
{
?>
<form method="POST" action="popups.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname" colspan="2">Pop Up Pages</td>
</tr>
<tr>
<td align="left" colspan="2">Pop up pages are used to add a link in your item description to view more information. 
For instance, if you want to let customers view a pop up page that shows all the colors available and additional 
descriptions, you can add that page in this area, then select the page in your item administration area.</td>
</tr>
<?php
$popupquery = "SELECT ID, PageTitle FROM " .$DB_Prefix ."_popups ORDER BY PageTitle";
$popupresult = mysql_query($popupquery, $dblink) or die ("Unable to select. Try again later.");
$popupnum = mysql_num_rows($popupresult);
if ($popupnum == 0)
echo "<tr><td align=\"center\" colspan=\"2\"><i>There are no pop up pages in your system.</i></td></tr>";
else
{
echo "<tr>";
echo "<td align=\"right\" width=\"50%\">Pop-up Page:</td>";
echo "<td align=\"left\" width=\"50%\">";
echo "<select size=\"1\" name=\"popupid\">";
for ($p=1; $popuprow = mysql_fetch_row($popupresult); ++$p)
{
echo "<option ";
if ($p == 1)
echo "selected ";
echo "value=\"$popuprow[0]\">" .stripslashes($popuprow[1]) ."</option>";
}
echo "</select>";
echo "</td>";
echo "</tr>";
}
?>
<tr>
<td align="center" colspan="2">
<input type="submit" class="button" value="Add" name="popup"> 
<?php
if ($popupnum > 0)
echo "<input type=\"submit\" class=\"button\" value=\"Edit\" name=\"popup\"> <input type=\"submit\" class=\"button\" value=\"Delete\" name=\"popup\">";
?>
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}

include("includes/links2.php");
include("includes/footer.htm");
?>

</html>
