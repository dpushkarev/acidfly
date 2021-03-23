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

if ($Submit == "Add Guest")
{
$addname = addslash_mq($name);
$addlocation = addslash_mq($location);
$addcomments = addslash_mq($comments);
if ($date != 0)
{
$splitdate = explode("/",$date);
$insdate = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
else if ($gbactive == "yes")
$insdate = date("Y-m-d");
if ($gbactive != "yes")
$insdate = "0000-00-00";
if ($name)
{
$insquery = "INSERT INTO " .$DB_Prefix ."_guestbook (Name, Email, Location, Comments, Date) ";
$insquery .= "VALUES ('$addname', '$email', '$addlocation', '$addcomments', '$insdate')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
}
}

if ($Submit == "Edit Guest")
{
$addname = addslash_mq($name);
$addlocation = addslash_mq($location);
$addcomments = addslash_mq($comments);
if ($date != 0)
{
$splitdate = explode("/",$date);
$insdate = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
else if ($gbactive == "yes")
$insdate = date("Y-m-d");
if ($gbactive != "yes")
$insdate = "0000-00-00";
if ($name)
{
$updquery = "UPDATE " .$DB_Prefix ."_guestbook SET Name='$addname', Email='$email', Location='$addlocation', ";
$updquery .= "Comments='$addcomments', Date='$insdate' WHERE ID='$gbid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to edit your guest. Please try again later.");
}
}

if ($Submit == "Yes, Delete Guest")
{
$delgbquery = "DELETE FROM " .$DB_Prefix ."_guestbook WHERE ID = '$gbid'";
$delgbresult = mysql_query($delgbquery, $dblink) or die("Unable to delete this item. Please try again later.");
}

if ($mode == "Activate" AND $gbid)
{
$insdate = date("Y-m-d");
$updquery = "UPDATE " .$DB_Prefix ."_guestbook SET Date='$insdate' WHERE ID='$gbid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to edit your guest. Please try again later.");
}

if ($mode == "Add" OR $mode == "Edit")
{
if ($mode == "Edit")
{
$getgbquery = "SELECT * FROM " .$DB_Prefix ."_guestbook WHERE ID = '$gbid'";
$getgbresult = mysql_query($getgbquery, $dblink) or die ("Could not show categories. Try again later.");
$getgbrow = mysql_fetch_row($getgbresult);
$stripname = stripslashes($getgbrow[1]);
$stripname = str_replace("\"", "&quot;", $stripname);
$striplocation = stripslashes($getgbrow[3]);
$striplocation = str_replace("\"", "&quot;", $striplocation);
$stripcomments = stripslashes($getgbrow[5]);
$ipaddress = $getgbrow[4];
if ($getgbrow[6] != 0)
{
$splitdate = explode("-",$getgbrow[6]);
$gbdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
}
?>
<form method="POST" action="guestbook.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="right">
<b>Name:</b>
</td>
<td valign="top" align="left">
<input type="text" name="name" value="<?php echo "$stripname"; ?>" size="40">
</td>
</tr>
<tr>
<td valign="top" align="right">
<b>Email:</b>
</td>
<td valign="top" align="left">
<input type="text" name="email" value="<?php echo "$getgbrow[2]"; ?>" size="40">
</td>
</tr>
<tr>
<td valign="top" align="right">
<b>Location:</b>
</td>
<td valign="top" align="left">
<input type="text" name="location" value="<?php echo "$striplocation"; ?>" size="40">
</td>
</tr>
<tr>
<td valign="top" align="right">
<b>Comments:</b>
</td>
<td valign="top" align="left">
<textarea rows="3" name="comments" cols="33"><?php echo "$stripcomments"; ?></textarea>
</td>
</tr>
<tr>
<td valign="top" align="right">
<b>Date:</b>
</td>
<td valign="top" align="left">
<?php
if ($gbdate)
$gb_date = $gbdate;
else
$gb_date = date("n/j/y");
echo "<input type=\"text\" name=\"date\" value=\"$gb_date\" size=\"20\"> ";
echo "<input type=\"checkbox\" name=\"gbactive\" ";
if (isset($gbdate) AND $gbdate != "0000-00-00")
echo "checked ";
echo "value=\"yes\">Active";
?>
</td>
</tr>
<?php
if ($ipaddress)
{
echo "<tr>";
echo "<td valign=\"top\" align=\"right\">IP Address:</td>";
echo "<td valign=\"top\" align=\"left\">$ipaddress</td>";
echo "</tr>";
}
?>
<tr>
<td valign="middle" align="center" colspan="2">
<?php
if ($mode == "Edit")
{
echo "<input type=\"hidden\" value=\"$gbid\" name=\"gbid\">";
echo "<input type=\"submit\" class=\"button\" value=\"Edit Guest\" name=\"Submit\">";
}
else
echo "<input type=\"submit\" class=\"button\" value=\"Add Guest\" name=\"Submit\">";
?>
</td>
</tr>
</table>
</center>
</div>
</form>

<p align="center"><a href="guestbook.php">Back to Guestbook</a></p>

<?php
}
else if ($mode == "Delete")
{
$getgbquery = "SELECT * FROM " .$DB_Prefix ."_guestbook WHERE ID = '$gbid'";
$getgbresult = mysql_query($getgbquery, $dblink) or die ("Could not show categories. Try again later.");
$getgbrow = mysql_fetch_row($getgbresult);
$stripname = stripslashes($getgbrow[1]);
?>
<form method="POST" action="guestbook.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td>
You are about to delete the following guest:
<p align="center"><b>
<?php
echo "$stripname";
?>
</b></p>
<p>Do you want to continue?</p>
<?php
echo "<input type=\"hidden\" value=\"$gbid\" name=\"gbid\">";
?>
<input type="submit" class="button" value="Yes, Delete Guest" name="Submit">&nbsp; <input type="submit" class="button" value="No, Don't Delete" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<p align="center"><a href="guestbook.php">Back to Guestbook</a></p>

<?php
}
else
{
$setpg_lower = "guestbook";
$setpg_upper = ucfirst($setpg_lower);

if ($submit == "Activate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
if ($submit == "Deactivate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}

$getquery = "SELECT ID, Active FROM " .$DB_Prefix ."_pages WHERE PageName='$setpg_lower' AND PageType='optional'";
$getresult = mysql_query($getquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($getresult) == 1)
{
$getrow = mysql_fetch_row($getresult);
$pgid = $getrow[0];
$setactive = $getrow[1];
}
if (!$setactive)
$setactive = "No";

if ($setactive == "Yes")
{
?>

<?php
$getaquery = "SELECT ID, Name, SUBSTRING_INDEX(Comments, ' ', 5) AS Snippet FROM " .$DB_Prefix ."_guestbook WHERE Date='0000-00-00' ORDER BY Name";
$getaresult = mysql_query($getaquery, $dblink) or die ("Could not show categories. Try again later.");
$getanum = mysql_num_rows($getaresult);
if ($getanum > 0)
{
?>
<form method="POST" action="guestbook.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center">
The following entries are not yet active:<br> 
<?php
echo "<select size=\"1\" name=\"gbid\">";
for ($getacount = 1; $getarow = mysql_fetch_row($getaresult); ++$getacount)
{
$display = stripslashes($getarow[1]);
if ($getarow[2])
$display .= " (" .stripslashes($getarow[2]) ." ... )";
echo "<option value=\"$getarow[0]\">$display</option>";
}
echo "</select>";
?>
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate" name="mode"> 
<input type="submit" class="button" value="Edit" name="mode"> 
<input type="submit" class="button" value="Delete" name="mode">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}
?>

<form method="POST" action="guestbook.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center">
<b>Active Guestbook Entries:</b> 
<?php
$getgbquery = "SELECT ID, Name, Date FROM " .$DB_Prefix ."_guestbook WHERE Date <> '0000-00-00' ORDER BY Name";
$getgbresult = mysql_query($getgbquery, $dblink) or die ("Could not show categories. Try again later.");
$getgbnum = mysql_num_rows($getgbresult);

if ($getgbnum == 0)
echo "<i>No Guestbook Entries Listed.</i>";
else
{
echo "<select size=\"1\" name=\"gbid\">";
for ($getgbcount = 1; $getgbrow = mysql_fetch_row($getgbresult); ++$getgbcount)
{
$display = stripslashes($getgbrow[1]);
if ($getgbrow[2] != 0)
{
$splitdate = explode("-",$getgbrow[2]);
$display .= " (" .date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0])) .")";
}
echo "<option value=\"$getgbrow[0]\">$display</option>";
}
echo "</select>";
}
?>
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Add" name="mode">
<?php
if ($getgbnum != 0)
{
echo " <input type=\"submit\" class=\"button\" value=\"Edit\" name=\"mode\">";
echo " <input type=\"submit\" class=\"button\" value=\"Delete\" name=\"mode\">";
}
?>
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
if ($pgid)
$setpg_lower = "<a href=\"pages.php?edit=yes&pgid=$pgid\">$setpg_lower</a>";
}

if ($setactive == "No")
{
?>

<form method="POST" action="<?php echo "$setpg.php"; ?>">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center">
The guestbook page is currently inactive. If you would like to<br>activate it, please click the button below.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate Guestbook Page" name="submit">
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
echo "<p align=\"center\" class=\"smalltext\">";
echo "<a href=\"pages.php?edit=yes&pgid=$pgid\">Contents</a> | ";
echo "<a href=\"guestbook.php?submit=Deactivate+Guestbook+Page\">Deactivate</a></p>";
}

}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>