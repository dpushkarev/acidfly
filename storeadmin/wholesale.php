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

if ($Submit == "Add Vendor")
{
$company = addslash_mq($company);
$contact = addslash_mq($contact);
if ($company)
{
$getwsquery = "SELECT Password FROM " .$DB_Prefix ."_wholesale WHERE Email='$email' AND Password='$password'";
$getwsresult = mysql_query($getwsquery, $dblink) or die("Unable to check your vendor. Please try again later.");
if (mysql_num_rows($getwsresult) == 0)
{
$wsquery = "INSERT INTO " .$DB_Prefix ."_wholesale (Company, Email, Contact, Discount, Password, Active, WebSite) VALUES ('$company', '$email', '$contact', '$discount', '$password', '$active', '$web_site')";
$wsresult = mysql_query($wsquery, $dblink) or die("Unable to edit your vendor. Please try again later.");
}
else
echo "<i>Duplicate Password - wholesaler could not be added</i>";
}
}
if ($Submit == "Edit Vendor")
{
$company = addslash_mq($company);
$contact = addslash_mq($contact);
if ($company)
{
$getwsquery = "SELECT Password FROM " .$DB_Prefix ."_wholesale WHERE Email='$email' AND Password='$password' AND ID<>'$wsid'";
$getwsresult = mysql_query($getwsquery, $dblink) or die("Unable to check your vendor. Please try again later.");
if (mysql_num_rows($getwsresult) == 0)
{
$wsquery = "UPDATE " .$DB_Prefix ."_wholesale SET Company='$company', Email='$email', Contact='$contact', Discount='$discount', Password='$password', Active='$active', WebSite='$web_site' WHERE ID='$wsid'";
$wsresult = mysql_query($wsquery, $dblink) or die("Unable to edit your vendor. Please try again later.");
}
else
echo "<i>Duplicate Password - wholesaler could not be updated</i>";
}
}
if ($Submit == "Yes, Delete Vendor")
{
$delwsquery = "DELETE FROM " .$DB_Prefix ."_wholesale WHERE ID = '$wsid'";
$delwsresult = mysql_query($delwsquery, $dblink) or die("Unable to delete this item. Please try again later.");
}

if ($Submit == "Add" OR $Submit == "Edit")
{
if ($Submit == "Edit" AND $wsid)
{
$getwsquery = "SELECT * FROM " .$DB_Prefix ."_wholesale WHERE ID = '$wsid'";
$getwsresult = mysql_query($getwsquery, $dblink) or die ("Could not show categories. Try again later.");
$getwsrow = mysql_fetch_array($getwsresult);
$stripcompany = stripslashes($getwsrow[Company]);
$stripcontact = stripslashes($getwsrow[Contact]);
$email = $getwsrow[Email];
$web_site = $getwsrow[WebSite];
$ws_pswd = $getwsrow[4];
$ws_disc = $getwsrow[5];
$active = $getwsrow[Active];
}
?>
<form method="POST" action="wholesale.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="top" align="right" class="fieldname">Company:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="company" value="<?php echo "$stripcompany"; ?>" size="40">
</td>
</tr>
<tr>
<td valign="top" align="right" class="fieldname">Web Site:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="web_site" value="<?php echo "$web_site"; ?>" size="40">
</td>
</tr>
<tr>
<td valign="top" align="right" class="fieldname">Email Address:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="email" value="<?php echo "$email"; ?>" size="40">
</td>
</tr>
<tr>
<td valign="top" align="right" class="fieldname">Contact Info:</td>
<td valign="top" align="left" colspan="3">
<textarea rows="3" name="contact" cols="33"><?php echo "$stripcontact"; ?></textarea>
</td>
</tr>
<tr>
<td valign="top" align="right" class="fieldname">Password:</td>
<td valign="top" align="left">
<input type="text" name="password" value="<?php echo "$ws_pswd"; ?>" size="10" maxlength="10">
</td>
<td valign="top" align="right" class="fieldname">Override Disc:</td>
<td valign="top" align="left">
<input type="text" name="discount" value="<?php echo "$ws_disc"; ?>" size="5">%
</td>
</tr>
<tr>
<td valign="top" align="right" class="fieldname">Active:</td>
<td valign="top" align="left">
<select size="1" name="active">
<?php
if ($active == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
?>
</select>
</td>
<td valign="top" align="center" colspan="2">
<?php
if ($wsid)
echo "<a href=\"orders.php?ws=$wsid\">View Orders</a>";
else
echo "&nbsp;";
?>
</td>
</tr>
<tr>
<td valign="middle" align="center" colspan="4">
<?php
echo "<input type=\"hidden\" value=\"wholesale\" name=\"mode\">";
if ($Submit == "Edit" AND $wsid)
{
echo "<input type=\"hidden\" value=\"$wsid\" name=\"wsid\">";
echo "<input type=\"submit\" class=\"button\" value=\"Edit Vendor\" name=\"Submit\">";
}
else
echo "<input type=\"submit\" class=\"button\" value=\"Add Vendor\" name=\"Submit\">";
?>
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}
else if ($Submit == "Delete")
{
$getwsquery = "SELECT Company FROM " .$DB_Prefix ."_wholesale WHERE ID = '$wsid'";
$getwsresult = mysql_query($getwsquery, $dblink) or die ("Could not show categories. Try again later.");
$getwsrow = mysql_fetch_row($getwsresult);
$stripcompany = stripslashes($getwsrow[0]);
?>
<form method="POST" action="wholesale.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td>
You are about to delete the following vendor:
<p align="center" class="fieldname">
<?php
echo "$stripcompany";
?>
</p>
<p>Do you want to continue?</p>
<?php
echo "<input type=\"hidden\" value=\"$wsid\" name=\"wsid\">";
?>
<input type="hidden" value="wholesale" name="mode">
<input type="submit" class="button" value="Yes, Delete Vendor" name="Submit">&nbsp; <input type="submit" class="button" value="No, Don't Delete" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}

else if ($Submit == "List")
{
?>

<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="largetable">
<tr>
<td class="fieldname">Company</td>
<td class="fieldname">Contact</td>
<td class="fieldname">WebSite</td>
<td class="fieldname">Email</td>
<td class="fieldname">Discount</td>
<td class="fieldname">Active</td>
</tr>
<?php
$wsquery = "SELECT * FROM " .$DB_Prefix ."_wholesale ORDER BY Company";
$wsresult = mysql_query($wsquery, $dblink) or die ("Could not show categories. Try again later.");
while ($wsrow = mysql_fetch_array($wsresult))
{
$company = stripslashes($wsrow[Company]);
$contact = stripslashes($wsrow[Contact]);
$email = $wsrow[Email];
$website = str_replace("http://", "", $wsrow[WebSite]);
if ($wsrow[Discount] > 0)
$disc = "$wsrow[Discount]%";
else
$disc = "Per Item";
echo "<tr>";
echo "<td>$company ";
$ordquery = "SELECT COUNT(ID) FROM " .$DB_Prefix ."_orders WHERE WholesaleID='$wsrow[ID]'";
$ordresult = mysql_query($ordquery, $dblink) or die ("Could not show vendors. Try again later.");
$ordrow = mysql_fetch_row($ordresult);
if ($ordrow[0] > 0)
echo "(<a href=\"orders.php?ws=$wsrow[ID]\">$ordrow[0]</a>)";
echo "</td>";
echo "<td>$contact</td>";
echo "<td>$website</td>";
echo "<td>$email</td>";
echo "<td>$disc</td>";
echo "<td>$wsrow[Active]</td>";
echo "</tr>";
}
?>
</table>
</center>
</div>
<p align="center"><a href="wholesale.php">Back to Wholesale</a></p>

<?php
}

else
{
$setpg_lower = "wholesale";
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

<form method="POST" action="wholesale.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center">
<div class="fieldname">Wholesale Vendors:</div>
<?php
$getwsquery = "SELECT ID, Company, Discount FROM " .$DB_Prefix ."_wholesale ORDER BY Company";
$getwsresult = mysql_query($getwsquery, $dblink) or die ("Could not show categories. Try again later.");
$getwsnum = mysql_num_rows($getwsresult);

if ($getwsnum == 0)
echo "No Vendors Listed.";
else
{
echo "<select size=\"1\" name=\"wsid\">";
for ($getwscount = 1; $getwsrow = mysql_fetch_row($getwsresult); ++$getwscount)
{
$display = stripslashes($getwsrow[1]);
if ($getwsrow[2] > 0)
$display .= " " .number_format($getwsrow[2], 0) ."%";
echo "<option value=\"$getwsrow[0]\">$display</option>";
}
echo "</select>";
}
?>
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Add" name="Submit">
<?php
if ($getwsnum != 0)
{
echo "&nbsp;|&nbsp;<input type=\"submit\" class=\"button\" value=\"Edit\" name=\"Submit\">";
echo "&nbsp;|&nbsp;<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"Submit\">";
echo "&nbsp;|&nbsp;<input type=\"submit\" class=\"button\" value=\"List\" name=\"Submit\">";
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
The wholesale page is currently inactive. If you would like to<br>activate it, please click the button below.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate Wholesale Page" name="submit">
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
echo "<a href=\"wholesale.php?submit=Deactivate+Wholesale+Page\">Deactivate</a></p>";
}

}
include("includes/links2.php");
include("includes/footer.htm");
?>

</body>

</html>
