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

if ($mode == "update")
{
if (!$linkname)
{
$errmsg = "You must enter a name for your link. Please try again.";
if ($linkid)
$mode = "Edit";
else
$mode = "Add";
}
else
{
// Update link
if ($linkid)
{
$linkname = addslash_mq($linkname);
$linkcode = addslash_mq($linkcode);
$description = addslash_mq($description);
if ($siteurl AND substr($siteurl, 0, 7) != "http://")
$siteurl = "http://" .$siteurl;
$updquery = "UPDATE " .$DB_Prefix ."_links SET LinkName='$linkname', LinkCode='$linkcode', SiteURL='$siteurl', ";
$updquery .= "Image='$image', Description='$description', Active='$active', LinkOrder='$linkorder' WHERE ID='$linkid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
// Add new link
else
{
$linkname = addslash_mq($linkname);
$linkcode = addslash_mq($linkcode);
$description = addslash_mq($description);
if ($siteurl AND substr($siteurl, 0, 7) != "http://")
$siteurl = "http://" .$siteurl;
$insquery = "INSERT INTO " .$DB_Prefix ."_links (LinkName, LinkCode, SiteURL, Image, Description, Active, LinkOrder)";
$insquery .= " VALUES ('$linkname', '$linkcode', '$siteurl', '$image', '$description', '$active', '$linkorder')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
}
}
}

if ($mode == "Yes")
{
$delquery = "DELETE FROM " .$DB_Prefix ."_links WHERE ID='$linkid'";
$delresult = mysql_query($delquery, $dblink) or die("Unable to delete. Please try again later.");
}

if ($mode == "Add" OR $mode == "Edit")
{
if ($mode == "Edit" AND $linkid AND !$errmsg)
{
$getlinkquery = "SELECT * FROM " .$DB_Prefix ."_links WHERE ID='$linkid'";
$getlinkresult = mysql_query($getlinkquery, $dblink) or die ("Could not show links. Try again later.");
$getlinknum = mysql_num_rows($getlinkresult);
if ($getlinknum == 1)
{
$getlinkrow = mysql_fetch_array($getlinkresult);
$linkname = stripslashes($getlinkrow[LinkName]);
$linkname = str_replace("\"", "&quot;", $linkname);
$linkcode = stripslashes($getlinkrow[LinkCode]);
$linkcode = str_replace("\"", "&quot;", $linkcode);
$siteurl = $getlinkrow[SiteURL];
$description = stripslashes($getlinkrow[Description]);
$description = str_replace("\"", "&quot;", $description);
$image = $getlinkrow[Image];
$active = $getlinkrow[Active];
$linkorder = $getlinkrow[LinkOrder];
}
}
?>

<form method="POST" action="weblinks.php" name="WebLinks">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<?php
if ($errmsg)
echo "<tr><td align=\"center\" colspan=\"4\" valign=\"top\" class=\"error\">$errmsg</td></tr>";
?>
<tr>
<td valign="top" align="right">Link Name:</td>
<td valign="top" align="left" colspan="3">
<?php echo "<input type=\"text\" name=\"linkname\" value=\"$linkname\" size=\"50\">"; ?>
</td>
</tr>
<tr><td align="center" colspan="4" valign="top"><hr noshade size="1" class="highlighttext"></td></tr>
<tr><td align="center" colspan="4" valign="top"><i>If you have link code that a web site provided to you, enter it below:</i></td></tr>
<tr>
<td valign="top" align="right">Link Code:</td>
<td valign="top" align="left" colspan="3"><textarea name="linkcode" cols="42" rows="3"><?php echo "$linkcode"; ?></textarea></td>
</tr>
<tr><td align="center" colspan="4" valign="top"><hr noshade size="1" class="highlighttext"></td></tr>
<tr><td align="center" colspan="4" valign="top"><i>OR Enter the URL (web site address) of the site you want to link to, along with a description of the site, and image or banner URL if desired.</i></td></tr>
<tr>
<td valign="top" align="right">Site URL:</td>
<td valign="top" align="left" colspan="3">
<?php echo "<input type=\"text\" name=\"siteurl\" value=\"$siteurl\" size=\"50\">"; ?>
</td>
</tr>
<tr>
<td valign="top" align="right">Image:</td>
<td valign="top" align="left" colspan="3">
<?php echo "<input type=\"text\" name=\"image\" value=\"$image\" size=\"45\">"; ?>
<a href="includes/imgload.php?formsname=WebLinks&fieldsname=image" target="_blank" onClick="PopUp=window.open('includes/imgload.php?formsname=WebLinks&fieldsname=image', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=300,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
</td>
</tr>
<tr>
<td valign="top" align="right">Description:</td>
<td valign="top" align="left" colspan="3"><textarea name="description" cols="42" rows="2"><?php echo "$description"; ?></textarea></td>
</tr>
<tr><td align="center" colspan="4" valign="top"><hr noshade size="1" class="highlighttext"></td></tr>
<tr>
<td valign="top" align="right">Active:</td>
<td valign="top" align="left">
<?php
echo "<select name=\"active\" size=\"1\">";
if ($active == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
echo "</select>";
?>
</td>
<td valign="top" align="right">Order Of Link:</td>
<td valign="top" align="left">
<?php
echo "<select name=\"linkorder\" size=\"1\">";
echo "<option ";
if (!$linkorder)
echo "selected ";
echo "value=\"\">N/A</option>";
for ($i=1; $i <= 99; ++$i)
{
if ($i < 10)
$i_val = "0" .$i;
else
$i_val = $i;
echo "<option ";
if ($linkorder == $i_val)
echo "selected ";
echo "value=\"$i_val\">$i</option>";
}
echo "</select>";
?>
</td>
</tr>
<tr><td align="center" colspan="4" valign="top">
<input type="hidden" value="update" name="mode">
<?php
if ($mode == "Edit" AND $linkid)
{
echo "<input type=\"hidden\" value=\"$linkid\" name=\"linkid\">";
echo "<input type=\"submit\" class=\"button\" value=\"Update\" name=\"submit\">";
}
else
echo "<input type=\"submit\" class=\"button\" value=\"Add\" name=\"submit\">";
?>
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
}

else if ($mode == "Delete")
{
$getlinkquery = "SELECT LinkName FROM " .$DB_Prefix ."_links WHERE ID='$linkid'";
$getlinkresult = mysql_query($getlinkquery, $dblink) or die ("Could not show links. Try again later.");
$getlinknum = mysql_num_rows($getlinkresult);
if ($getlinknum == 1)
{
$getlinkrow = mysql_fetch_row($getlinkresult);
$linkname = stripslashes($getlinkrow[0]);
}
?>

<form method="POST" action="weblinks.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">
<?php echo "Delete $linkname"; ?>
</td>
</tr>
<tr>
<td valign="middle" align="center">
Do you want to delete this link permanently?
</td>
</tr>
<tr>
<td valign="middle" align="center">
<?php
echo "<input type=\"hidden\" value=\"$linkid\" name=\"linkid\">";
?>
<input type="submit" class="button" value="Yes" name="mode"> <input type="submit" class="button" value="No" name="mode">
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
$setpg_lower = "weblinks";
$setpg_upper = "Web Links";

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

<form method="POST" action="weblinks.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">Web Site Links:</td>
</tr>
<tr>
<td valign="middle" align="center">
Add text links, banners or buttons from other sites onto your links page.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<?php
$getlinkquery = "SELECT ID, LinkName FROM " .$DB_Prefix ."_links ORDER BY LinkOrder, LinkName";
$getlinkresult = mysql_query($getlinkquery, $dblink) or die ("Could not show links. Try again later.");
$getlinknum = mysql_num_rows($getlinkresult);
if ($getlinknum == 0)
echo "No Links Listed.";
else
{
echo "<select size=\"1\" name=\"linkid\">";
for ($getlinkcount = 1; $getlinkrow = mysql_fetch_row($getlinkresult); ++$getlinkcount)
{
$display = stripslashes($getlinkrow[1]);
echo "<option value=\"$getlinkrow[0]\">$display</option>";
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
if ($getlinknum != 0)
{
echo "&nbsp;|&nbsp;<input type=\"submit\" class=\"button\" value=\"Edit\" name=\"mode\">";
echo "&nbsp;|&nbsp;<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"mode\">";
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
<?php
echo "The links page is currently inactive. If you would like to<br>activate it, please click the button below.";
?>
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate Web Links Page" name="submit">
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
echo "<a href=\"weblinks.php?submit=Deactivate+Web+Links+Page\">Deactivate</a></p>";
}

}
?>

<script language="php">
include("includes/links2.php");
include("includes/footer.htm");
</script>
</body>

</html>
