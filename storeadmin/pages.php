<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
<script language="php">include("includes/htmlarea.php");</script>
</head>

<body onload="editor_generate('content');">
<script language="php">$java = "content"; include("includes/htmlareabody.php");</script>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

$showquery = "SELECT SubGroup, GivePermission FROM " .$DB_Prefix ."_permissions WHERE SetPg='$setpg' AND SubGroup<>''";
$showresult = mysql_query($showquery, $dblink) or die ("Unable to select. Try again later.");
while ($showrow = mysql_fetch_row($showresult))
{
if ($showrow[0] == "activate")
$show_activate = $showrow[1];
if ($showrow[0] == "order")
$show_order = $showrow[1];
if ($showrow[0] == "group")
$show_group = $showrow[1];
if ($showrow[0] == "update")
$show_content = $showrow[1];
if ($showrow[0] == "themes")
$show_theme = $showrow[1];
if ($showrow[0] == "add")
$show_add = $showrow[1];
}

// If editing pages/content is allowed, which features should be included?

if ($pgid AND $active == "yes")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE ID='$pgid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
// Update wish list if needed
$pgquery = "SELECT PageName, PageType FROM " .$DB_Prefix ."_pages WHERE ID='$pgid'";
$pgresult = mysql_query($pgquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($pgresult) == 1)
{
$pgrow = mysql_fetch_row($pgresult);
if ($pgrow[0] == "registry" AND $pgrow[1] == "optional")
{
$uppgquery = "UPDATE " .$DB_Prefix ."_vars SET WishList='gift' WHERE WishList='none'";
$uppgresult = mysql_query($uppgquery, $dblink) or die("Unable to update. Please try again later.");
}
}
}

if ($pgid AND $active == "no")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE ID='$pgid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
// Update wish list if needed
$pgquery = "SELECT PageName, PageType FROM " .$DB_Prefix ."_pages WHERE ID='$pgid'";
$pgresult = mysql_query($pgquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($pgresult) == 1)
{
$pgrow = mysql_fetch_row($pgresult);
if ($pgrow[0] == "wishlist" AND $pgrow[1] == "optional")
{
$uppgquery = "UPDATE " .$DB_Prefix ."_vars SET WishList='none' WHERE ID=1";
$uppgresult = mysql_query($uppgquery, $dblink) or die("Unable to update. Please try again later.");
}
}
}

if ($pgid AND $curord AND $neword)
{
$upd1query = "UPDATE " .$DB_Prefix ."_pages SET LinkOrder='$curord' WHERE LinkOrder='$neword'";
$upd1result = mysql_query($upd1query, $dblink) or die("Unable to update. Please try again later.");
$upd2query = "UPDATE " .$DB_Prefix ."_pages SET LinkOrder='$neword' WHERE ID='$pgid'";
$upd2result = mysql_query($upd2query, $dblink) or die("Unable to update. Please try again later.");
}

if ($pgid AND $del == "Yes")
{
$getpgquery = "SELECT PageName, LinkOrder FROM " .$DB_Prefix ."_pages WHERE ID='$pgid'";
$getpgresult = mysql_query($getpgquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($getpgresult) == 1)
{
$getpgrow = mysql_fetch_row($getpgresult);
$pagename = "$Home_Path/pages/$getpgrow[0].$pageext";
// Change order to final if it is not the same as the page total.
if ($getpgrow[1] != $pgtot)
{
$upd1query = "UPDATE " .$DB_Prefix ."_pages SET LinkOrder='$getpgrow[1]' WHERE LinkOrder='$pgtot'";
$upd1result = mysql_query($upd1query, $dblink) or die("Unable to update. Please try again later.");
$upd2query = "UPDATE " .$DB_Prefix ."_pages SET LinkOrder='$pgtot' WHERE ID='$pgid'";
$upd2result = mysql_query($upd2query, $dblink) or die("Unable to update. Please try again later.");
}
// Delete file and corresponding page entry
if (!empty($ftp_site))
{
$ftp_con = @ftp_connect($ftp_site);
$ftp_login = @ftp_login($ftp_con, $ftp_user, $ftp_pass);
$ftppagename = "$ftp_path/pages/$getpgrow[0].$pageext";
$ftp_del_file = @ftp_delete($ftp_con, $ftppagename);
if (!$ftp_con OR !$ftp_login OR !$ftp_del_file)
die("Could not remove page");
}
else
@unlink ($pagename) or die("Could not remove page.");
$delquery = "DELETE FROM " .$DB_Prefix ."_pages WHERE ID='$pgid' AND PageType='additional'";
$delresult = mysql_query($delquery, $dblink) or die("Unable to delete. Please try again later.");
}
}

if ($addnew == "yes" AND $pagename)
{
$pagename = ereg_replace("[^[:alnum:]_-]", "", $pagename);
$source_info = "<script language=\"php\">\r\n";
$source_info .= "require_once(\"../stconfig.php\");\r\n";
$source_info .= "require(\"../$" ."Inc_Dir/openinfo.php\");\r\n";
$source_info .= "require(\"../template/$" ."themename\");\r\n";
$source_info .= "</script>";
$destination_file = "../pages/$pagename.$pageext";
if (!file_exists($destination_file))
{
$upd_path = "$Home_Path/pages";
if (!empty($ftp_site))
{
$fupd_path = "$ftp_path/pages";
$conn_id = @ftp_connect($ftp_site);
$login_result = @ftp_login($conn_id, $ftp_user, $ftp_pass);
@ftp_site($conn_id, "CHMOD " .$chmod_update ." " .$fupd_path);
}
$handle = @fopen ($destination_file, "w+");
if (!$handle)
die ("Page could not be created.");
@fwrite($handle, $source_info);
@fclose($handle);
if (!empty($ftp_site))
{
@ftp_site($conn_id, "CHMOD " .$chmod_folder ." " .$fupd_path);
@ftp_close($conn_id);
}
}
$chkquery = "SELECT ID FROM " .$DB_Prefix ."_pages WHERE PageName='$pagename' AND PageType='additional'";
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($chkresult) == 0)
{
$cntquery = "SELECT COUNT(ID) FROM " .$DB_Prefix ."_pages";
$cntresult = mysql_query($cntquery, $dblink) or die ("Unable to select. Try again later.");
$cntrow = mysql_fetch_row($cntresult);
$newlinkorder = $cntrow[0]+1;
if ($pagetitle)
$addpagetitle = addslash_mq($pagetitle);
else
$addpagetitle = $pagename;
$addcontent = addslash_mq($content);
$addkeywords = addslash_mq($keywords);
$adddescription = addslash_mq($description);
$addmetatitle = addslash_mq($metatitle);
$addthemename = addslash_mq($themename);
$insquery = "INSERT INTO " .$DB_Prefix ."_pages (PageTitle, PageName, NavImage, HeaderImage, Content, ";
$insquery .= "Keywords, Description, LinkOrder, PageType, ShowTitle, ShowLink, Active, MetaTitle, ";
$insquery .= "ThemeName) VALUES ('$addpagetitle', '$pagename', '$navimage', '$headerimage', '$addcontent', ";
$insquery .= "'$addkeywords', '$adddescription', '$newlinkorder', 'additional', '$showtitle', '$showlink', ";
$insquery .= "'$active', '$addmetatitle', '$addthemename')";
$insresult = mysql_query($insquery, $dblink) or die("Unable to add. Please try again later.");
}
}

if ($update == "yes" AND $pagename)
{
$pagename = ereg_replace("[^[:alnum:]_-]", "", $pagename);
if ($pagetitle)
$addpagetitle = addslash_mq($pagetitle);
else
$addpagetitle = $pagename;
$addcontent = addslash_mq($content);
$addcontent = str_replace('<html', '<div', $addcontent);
$addcontent = str_replace('</html>', '</div>', $addcontent);
$addcontent = str_replace('<title', '<div', $addcontent);
$addcontent = str_replace('</title>', '</div>', $addcontent);
$addcontent = str_replace('<meta', '<div', $addcontent);
$addcontent = str_replace('</meta>', '</div>', $addcontent);
$addcontent = str_replace('<body', '<div', $addcontent);
$addcontent = str_replace('</body>', '</div>', $addcontent);
$addkeywords = addslash_mq($keywords);
$adddescription = addslash_mq($description);
$addmetatitle = addslash_mq($metatitle);
$addthemename = addslash_mq($themename);
$old_file = "../pages/$oldpagename.$pageext";
$new_file = "../pages/$pagename.$pageext";

if ($oldpagename == $pagename OR (!file_exists($new_file) AND $oldpagename != $pagename))
{
if ($oldpagename != $pagename)
@rename ($old_file, $new_file);
if ($linkgroup)
$linkset = implode("", $linkgroup);
else
$linkset = "";
$updquery = "UPDATE " .$DB_Prefix ."_pages SET PageTitle='$addpagetitle', PageName='$pagename', ";
$updquery .= "NavImage='$navimage', HeaderImage='$headerimage', Content='$addcontent', ";
$updquery .= "Active='$active', Keywords='$addkeywords', Description='$adddescription', ";
$updquery .= "ShowTitle='$showtitle', ShowLink='$showlink', LinkGroup='$linkset', ";
$updquery .= "MetaTitle='$addmetatitle', ThemeName='$addthemename' WHERE ID='$pgid'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
if (ISSET($prodselection))
{
$updvarquery = "UPDATE " .$DB_Prefix ."_vars ";
if ($pagename == "index")
$updvarquery .= "SET IndexProducts='$prodselection'";
else
$updvarquery .= "SET CatalogProducts='$prodselection'";
$updvarquery .= "WHERE ID=1";
$updvarresult = mysql_query($updvarquery, $dblink) or die("Unable to select your variables. Please try again later.");
}
}


// Start tables
if (($pgid AND $edit == "yes") OR $add == "yes")
{
if ($pgid)
{
$getpgquery = "SELECT * FROM " .$DB_Prefix ."_pages WHERE ID='$pgid'";
$getpgresult = mysql_query($getpgquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($getpgresult) == 1)
{
$getpgrow = mysql_fetch_array($getpgresult);
$pagetitle = stripslashes($getpgrow[PageTitle]);
$pagename = $getpgrow[PageName];
$pagetype = $getpgrow[PageType];
$navimage = $getpgrow[NavImage];
$headerimage = $getpgrow[HeaderImage];
$content = stripslashes($getpgrow[Content]);
$keywords = stripslashes($getpgrow[Keywords]);
$description = stripslashes($getpgrow[Description]);
$metatitle = str_replace('"', "&quot;", stripslashes($getpgrow[MetaTitle]));
$showtitle = $getpgrow[ShowTitle];
$showlink = $getpgrow[ShowLink];
$showgroup = $getpgrow[LinkGroup];
$active = $getpgrow[Active];
$themename = str_replace('"', "&quot;", stripslashes($getpgrow[ThemeName]));
}
}
else
{
$pagetype = "additional";
$showtitle = "Yes";
$showlink = "Yes";
$active = "Yes";
}
?>
<form method="POST" action="pages.php" name="Pages">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname" colspan="4">
<?php
if ($pgid)
echo "Edit: $pagetitle";
else
echo "Add New Page";
?>
</td>
</tr>
<tr>
<td align="right" valign="top">Page Name:</td>
<td align="left" valign="top">
<input type="hidden" name="oldpagename" value="<?php echo "$pagename"; ?>">
<?php
if ($pagetype == "additional")
echo "<input type=\"text\" name=\"pagename\" value=\"$pagename\" size=\"20\">";
else
echo "<input type=\"hidden\" name=\"pagename\" value=\"$pagename\">$pagename";
echo ".$pageext";
?>
</td>
<td align="right" valign="top">
Active:</td>
<td align="left" valign="top">
<?php
if ($pagetype == "required")
echo "Yes <input type=\"hidden\" name=\"active\" value=\"Yes\">";
else
{
echo "<select size=\"1\" name=\"active\">";
if ($active == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
echo "</select>";
}
?>
</td>
</tr>
<tr>
<td align="right" valign="top">Show Title?</td>
<td align="left" valign="top">
<select size="1" name="showtitle">
<?php
if ($showtitle == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
?>
</select>
</td>
<td align="right" valign="top">Show Link?</td>
<td align="left" valign="top">
<select size="1" name="showlink">
<?php
if ($showlink == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
?>
</select>
</td>
</tr>
<tr>
<td align="right" valign="top">Page Title:</td>
<td align="left" valign="top" colspan="3">
<input type="text" name="pagetitle" value="<?php echo "$pagetitle"; ?>" size="53">
</td>
</tr>
<tr>
<td valign="top" align="right">Nav Image:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="navimage" value="<?php echo "$navimage";?>" size="43"> 
<?php
echo "<a href=\"includes/imgload.php?formsname=Pages&fieldsname=navimage&mo=y\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=Pages&fieldsname=navimage&mo=y', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
?>
</td>
</tr>
<tr>
<td valign="top" align="right">Header Image:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="headerimage" value="<?php echo "$headerimage";?>" size="43"> 
<?php
echo "<a href=\"includes/imgload.php?formsname=Pages&fieldsname=headerimage\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=Pages&fieldsname=headerimage', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
?>
</td>
</tr>
<tr>
<td align="right" valign="top">Content:</td>
<td align="left" valign="top" colspan="3">
<textarea rows="12" name="content" id="content" cols="45">
<?php echo htmlentities($content); ?>
</textarea>
</td>
</tr>
<tr>
<td valign="top" align="right">Meta Title:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="metatitle" value="<?php echo "$metatitle"; ?>" size="53" maxlength="50">
</td>
</tr>
<tr>
<td valign="top" align="right" nowrap>Meta Keywords:<br><span class="smalltext">100 chars max </span></td>
<td valign="top" align="left" colspan="3">
<textarea rows="2" name="keywords" cols="45">
<?php echo "$keywords"; ?>
</textarea></td>
</tr>
<tr>
<td valign="top" align="right" nowrap>Meta Description:<br><span class="smalltext">100 chars max </span></td>
<td valign="top" align="left" colspan="3">
<textarea rows="2" name="description" cols="45">
<?php echo "$description"; ?>
</textarea></td>
</tr>
<?php
if ($pagetype == "required")
{
echo "<tr>";
echo "<td valign=\"top\" align=\"right\">Show Products:</td>";
echo "<td valign=\"top\" align=\"left\" colspan=\"3\">";
$varquery = "SELECT IndexProducts, CatalogProducts FROM " .$DB_Prefix ."_vars WHERE ID=1";
$varresult = mysql_query($varquery, $dblink) or die("Unable to select your variables. Please try again later.");
$varrow = mysql_fetch_row($varresult);
if ($pagename == "index")
$prod_selection=$varrow[0];
else
$prod_selection=$varrow[1];
echo "<select size=\"1\" name=\"prodselection\">";
echo "<option ";
if ($prod_selection == "new" OR !$prod_selection)
echo "selected ";
echo "value=\"new\">New Products</option>";
echo "<option ";
if ($prod_selection == "featured")
echo "selected ";
echo "value=\"featured\">Featured Products</option>";
echo "<option ";
if ($prod_selection == "sale")
echo "selected ";
echo "value=\"sale\">Sale Products</option>";
echo "<option ";
if ($prod_selection == "catlist1")
echo "selected ";
echo "value=\"catlist1\">Category List (Single Column)</option>";
echo "<option ";
if ($prod_selection == "catlist3")
echo "selected ";
echo "value=\"catlist3\">Category List (3 Columns)</option>";
echo "<option ";
if ($prod_selection == "catlist5")
echo "selected ";
echo "value=\"catlist5\">Category List (5 Columns)</option>";
echo "<option ";
if ($prod_selection == "")
echo "selected ";
echo "value=\"\">No Display</option>";
echo "</select>";
echo "</td>";
echo "</tr>";
}

// PAGE GROUPINGS
if ($set_master_key == "yes" OR $show_group == "Yes")
{
echo "<tr>";
echo "<td valign=\"top\" align=\"right\">Page Groupings:</td>";
echo "<td valign=\"top\" align=\"left\" colspan=\"3\">";
for ($g = 1; $g <= 5; ++$g)
{
if ($g > 1)
echo " ";
echo "<input type=\"checkbox\" ";
if (strpos("$showgroup", "$g") !== false)
echo "checked ";
echo "name=\"linkgroup[]\" value=\"$g\">Group $g";
}
echo "</td>";
echo "</tr>";
}

// START THEME OPTIONS
$tmquery = "SELECT COUNT(ID) FROM " .$DB_Prefix ."_permissions WHERE SetPg='template' AND GivePermission = 'No'";
$tmresult = mysql_query($tmquery, $dblink) or die ("Unable to select. Try again later.");
$tmrow = mysql_fetch_row($tmresult);
if ($tmrow[0] == 0 OR $set_master_key == "yes")
{
if ($dir = @opendir("../template")) 
{
while (($file = readdir($dir)) !== false) 
{
$tempval = str_replace("_", " ", substr($file, 0, -4));
if(substr($file, -4) == ".htm" AND $file != "index.htm")
{
$setselection = "<";
$setselection .= "option value=\"$file\"";
if ($file == $themename)
$setselection .= "selected ";
$setselection .= ">$tempval</";
$setselection .= "option>";
$setsellist[] = $setselection;
}
} 
closedir($dir);
}
//Show form if templates exist
if ($setselection != "" AND ($set_master_key == "yes" OR $show_theme == "Yes"))
{
?>
<tr>
<td valign="top" align="right">Optional Theme:</td>
<td valign="top" align="left" colspan="3">
<select name="themename" size="1">
<?php
echo "<option ";
if (!$themename)
echo "selected ";
echo "value=\"\">-- Select Template --</option>";
asort($setsellist);
$setselect = implode("", $setsellist);
echo "$setselect";
?>
</select>
</td>
</tr>
<?php
}
}
// END THEME OPTIONS
?>

<tr>
<td align="center" valign="top" colspan="4">
<?php
if ($pgid)
{
echo "<input type=\"hidden\" value=\"$pgid\" name=\"pgid\">";
echo "<input type=\"hidden\" value=\"yes\" name=\"update\">";
echo "<input type=\"submit\" class=\"button\" value=\"Update\" name=\"Submit\">";
}
else
{
echo "<input type=\"hidden\" value=\"yes\" name=\"addnew\">";
echo "<input type=\"submit\" class=\"button\" value=\"Add\" name=\"Submit\">";
}
?>
</td>
</tr>
</table>
</center>
</div>
</form>

<p align="center"><a href="pages.php">Back to Pages</a></p>

<?php
}

else if ($pgid AND $del == "check")
{
$getpgquery = "SELECT PageTitle, PageName FROM " .$DB_Prefix ."_pages WHERE ID='$pgid' AND PageType='additional'";
$getpgresult = mysql_query($getpgquery, $dblink) or die ("Unable to select. Try again later.");
$getpgnum = mysql_num_rows($getpgresult);
if ($getpgnum == 1)
{
$getpgrow = mysql_fetch_array($getpgresult);
$pagetitle = stripslashes($getpgrow[PageTitle]);
}
?>

<form method="POST" action="pages.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname"><?php echo "Delete: $pagetitle"; ?></td>
</tr>
<tr>
<td align="center">Do you want to delete this page? This action is permanent, and you cannot recover 
the page or content in the future.</td>
</tr>
<tr>
<td align="center">
<?php
echo "<input type=\"hidden\" value=\"$pgid\" name=\"pgid\">";
echo "<input type=\"hidden\" value=\"$pgtot\" name=\"pgtot\">";
?>
<input type="submit" class="button" value="Yes" name="del"> 
<input type="submit" class="button" value="No" name="del">
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

<form method="POST" action="pages.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname">Web Site Pages</td>
</tr>
<tr>
<td align="center">

<table border="0" cellspacing="0" cellpadding="3">
<tr>
<?php
echo "<td class=\"fieldname\"><u>Page Name</u></td>";
echo "<td class=\"fieldname\"><u>Page Title</u></td>";
if ($set_master_key == "yes" OR $show_activate == "Yes")
echo "<td class=\"fieldname\"><u>Active?</u></td>";
if ($set_master_key == "yes" OR $show_order == "Yes")
echo "<td class=\"fieldname\"><u>Link Order</u></td>";
if ($set_master_key == "yes" OR $show_group == "Yes")
echo "<td class=\"fieldname\"><u>Group</u></td>";
if ($set_master_key == "yes" OR $show_content == "Yes")
echo "<td class=\"fieldname\" align=\"center\"><u>Content</u></td>";
if ($set_master_key == "yes" OR $show_add == "Yes")
echo "<td>&nbsp;</td>";
?>
</tr>

<?php
$pgquery = "SELECT " .$DB_Prefix ."_pages.* FROM " .$DB_Prefix ."_pages ";
if ($set_master_key == "no")
$pgquery .= "LEFT JOIN " .$DB_Prefix ."_permissions ON PageName=SetPg WHERE SetPg IS NULL OR GivePermission='Yes' ";
$pgquery .= "ORDER BY LinkOrder, PageName";
$pgresult = mysql_query($pgquery, $dblink) or die ("Unable to select. Try again later.");
$pgnum = mysql_num_rows($pgresult);
for ($pg=1; $pgrow = mysql_fetch_array($pgresult); ++$pg)
{
echo "<tr";
if ($pgrow[ID] == $pgid)
echo " class=highlighttext";
echo ">";
echo "<td>";
if ($pgrow[PageType] == "additional")
$pg_next = "$urldir/pages/$pgrow[PageName].$pageext";
else
$pg_next = "$urldir/$pgrow[PageName].$pageext";
if ($pgrow[Active] == "Yes")
echo "<a href=\"$pg_next\" target=\"_blank\">$pgrow[PageName].$pageext</a>";
else
echo "$pgrow[PageName].$pageext";
echo "</td>";
echo "<td>" .stripslashes($pgrow[PageTitle]) ."</td>";
if ($set_master_key == "yes" OR $show_activate == "Yes")
{
echo "<td>";
if ($pgrow[PageType] == "required")
echo "Yes";
else
{
if ($pgrow[Active] == "Yes")
echo "Yes (<a href=\"pages.php?pgid=$pgrow[ID]&active=no\">deactivate</a>)";
else
echo "No (<a href=\"pages.php?pgid=$pgrow[ID]&active=yes\">activate</a>)";
}
echo "</td>";
}
if ($set_master_key == "yes" OR $show_order == "Yes")
{
echo "<td>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$dnord = $pgrow[LinkOrder]+1;
$upord = $pgrow[LinkOrder]-1;
if ($pg == $pgnum)
echo "&nbsp;&nbsp;&nbsp;";
if ($pg < $pgnum)
echo " <a href=\"pages.php?pgid=$pgrow[ID]&neword=$dnord&curord=$pgrow[LinkOrder]\"><img alt=\"down\" border=\"0\" src=\"images/downarrow.gif\" width=\"9\" height=\"10\"></a>";
if ($pg > 1)
echo " <a href=\"pages.php?pgid=$pgrow[ID]&neword=$upord&curord=$pgrow[LinkOrder]\"><img alt=\"up\" border=\"0\" src=\"images/uparrow.gif\" width=\"9\" height=\"10\"></a>";
echo "</td>";
}
if ($set_master_key == "yes" OR $show_group == "Yes")
{
echo "<td>";
$linkstring = $pgrow[LinkGroup];
for ($s = 0; $s < strlen($linkstring); ++$s)
{
if ($s > 0)
echo ",";
echo $linkstring[$s];
}
echo "</td>";
}
if ($set_master_key == "yes" OR $show_content == "Yes")
{
echo "<td align=\"center\">";
echo "<a href=\"pages.php?pgid=$pgrow[ID]&edit=yes\">Edit</a>";
echo "</td>";
}
if ($set_master_key == "yes" OR $show_add == "Yes")
{
echo "<td align=\"center\">";
if ($pgrow[PageType] == "additional")
echo "<a href=\"pages.php?pgid=$pgrow[ID]&del=check&pgtot=$pgnum\">Delete</a>";
else
echo "&nbsp;";
echo "</td>";
}
echo "</tr>";
}
?>
</table>

</td>
</tr>
<?php
if ($set_master_key == "yes" OR $show_add == "Yes")
echo "<tr><td align=\"center\"><a href=\"pages.php?add=yes\">Add New Page</a></td></tr>";
?>
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