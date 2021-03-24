<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
<script language="php">include("includes/htmlarea.php");</script>
</head>

<body<script language="php">$java = "sitemessage"; include("includes/htmlareabody.php");</script>>
<?php
include("includes/open.php");

$showquery = "SELECT SubGroup, GivePermission FROM " .$DB_Prefix ."_permissions WHERE SetPg='$setpg' AND SubGroup<>''";
$showresult = mysqli_query($dblink, $showquery) or die ("Unable to select. Try again later.");
while ($showrow = mysqli_fetch_row($showresult))
{
if ($showrow[0] == "advanced")
$show_advanced = $showrow[1];
}

if ($Submit == "Update Variables")
{
$addkeywords = addslash_mq($keywords);
$adddescription = addslash_mq($description);
$addmetatitle = addslash_mq($metatitle);
$addemaillink = addslash_mq($emaillink);
$addsitemessage = addslash_mq($sitemessage);
if ($page_ext != "php" AND $page_ext != "htm" AND $page_ext != "html")
$page_ext = "php";
$catpgroot = explode(".", $catalogpage);
$catalogpage = $catpgroot[0] ."." .$page_ext;
$updvarquery = "UPDATE " .$DB_Prefix ."_vars SET SiteName='$sitename', AdminEmail='$adminemail', ";
$updvarquery .= "Keywords='$addkeywords', MetaTitle='$addmetatitle', ";
if ($domain_name)
$updvarquery .= "URL='$domain_name', ";
if ($set_master_key == "yes" OR $show_advanced == "Yes")
{
if ($imagedir)
$updvarquery .= "ImageDir='$imagedir', ";
if ($thumbnaildir)
$updvarquery .= "ThumbnailDir='$thumbnaildir', ";
if ($lgimagedir)
$updvarquery .= "LgImageDir='$lgimagedir', ";
if ($catimagedir)
$updvarquery .= "CatImageDir='$catimagedir', ";
if ($oldcatalog != $catalogpage AND $catpgroot[0] != "index" AND $catpgroot[0] != "search" AND $catpgroot[0] != "sitesearch" AND $catpgroot[0] != "sitemap" AND $catpgroot[0] != "weblinks" AND $catpgroot[0] != "contact" AND $catpgroot[0] != "certificates" AND $catpgroot[0] != "registry" AND $catpgroot[0] != "wholesale" AND $catpgroot[0] != "orders" AND $catpgroot[0] != "affiliates" AND $catpgroot[0] != "events" AND $catpgroot[0] != "articles" AND $catpgroot[0] != "guestbook" AND $catpgroot[0] != "faqs")
$updvarquery .= "CatalogPage='$catalogpage', ";
$updvarquery .= "PageExt='$page_ext', ";
}
$updvarquery .= "Description='$adddescription', SiteMessage='$addsitemessage', ";
$updvarquery .= "EmailLink='$addemaillink' WHERE ID=1";
$updvarresult = mysqli_query($dblink, $updvarquery) or die("Unable to update. Please try again later.");
$updmsg = "<p align=\"center\">Your system settings have been updated.";
// Update catalog page as well
if (($set_master_key == "yes" OR $show_general == "Yes") AND $oldcatalog != $catalogpage AND $catpgroot[0] != "index" AND $catpgroot[0] != "search" AND $catpgroot[0] != "sitesearch" AND $catpgroot[0] != "sitemap" AND $catpgroot[0] != "weblinks" AND $catpgroot[0] != "contact" AND $catpgroot[0] != "certificates" AND $catpgroot[0] != "registry" AND $catpgroot[0] != "wholesale" AND $catpgroot[0] != "orders" AND $catpgroot[0] != "affiliates" AND $catpgroot[0] != "events" AND $catpgroot[0] != "articles" AND $catpgroot[0] != "guestbook" AND $catpgroot[0] != "faqs")
{
$oldcatroot = explode(".", $oldcatalog);
$updpgquery .= "UPDATE " .$DB_Prefix ."_pages SET PageName='$catpgroot[0]' WHERE PageName='$oldcatroot[0]' AND PageType='required'";
$updpgresult = mysqli_query($dblink, $updpgquery) or die("Unable to update. Please try again later.");
$updmsg .= "<br>Please make sure to rename your catalog page!";
}
$updmsg .= "</p>";
}

if ($_POST[Submit] == "Update" AND $_POST[sitedomain])
{
$updsitequery .= "UPDATE " .$DB_Prefix ."_vars SET ";
if (substr($_POST[sitedomain], 0, 7) == "http://")
$site_domain = str_replace("http://","",$_POST[sitedomain]);
else
$site_domain = $_POST[sitedomain];
$updsitequery .= "URL='$site_domain'";
if ($unlock)
$updsitequery .= ", OpenSet='$_POST[unlock]'";
$updsitequery .= " WHERE ID=1";
$updsiteresult = mysqli_query($dblink, $updsitequery) or die ("Unable to update your system variables. Try again later.");
}

// Get variables
$openquery = "SELECT OpenSet, URL, PageExt FROM " .$DB_Prefix ."_vars WHERE ID=1";
$openresult = mysqli_query($dblink, $openquery) or die ("Unable to select your system variables. Try again later.");
$openrow = mysqli_fetch_array($openresult);
// Check for correct domain
$key=strtolower($openrow[URL]);
$key=str_replace("www.","",$key);
$key=str_replace(".","",$key);
$key=str_replace("/","",$key);
$key="oc_sb$key";
$key=md5($key);
if ($key != $openrow[OpenSet])
{
if (!$openrow[URL])
$sitedomain = trim ($_SERVER['HTTP_HOST'] .str_replace("/$Adm_Dir", "", dirname($_SERVER[PHP_SELF])),"/");
else
$sitedomain = $openrow[URL];
// If key is processed but incorrect
if ($_POST[Submit] == "Update" AND $_POST[sitedomain])
{
echo "<p align=\"center\" class=\"highlighttext\">";
echo "The site and/or unlock code is not correct.<br>";
echo "Contact your administrator for assistance.</p>";
}
echo "<form action=\"variables.php\" method=\"post\">";
echo "<p align=\"center\">";
echo "Web Site: <i>http://</i>";
echo "<input type=\"text\" name=\"sitedomain\" value=\"$sitedomain\" size=\"30\"><br>";
echo "Unlock Code #: ";
echo "<input type=\"text\" name=\"unlock\" size=\"30\"><br>";
echo "<input type=\"submit\" class=\"button\" value=\"Update\" name=\"Submit\">";
echo "<br>If you do not have an unlock code, please contact us for assistance.</p>";
echo "</form>";
}

else
{
include("includes/header.htm");
include("includes/links.php");

// If updated, print finish message
if ($updmsg)
echo "$updmsg";

$varquery = "SELECT * FROM " .$DB_Prefix ."_vars WHERE ID=1";
$varresult = mysqli_query($dblink, $varquery) or die("Unable to select your variables. Please try again later.");
$varnum = mysqli_num_rows($varresult);
if ($varnum == 1)
{
$varrow = mysqli_fetch_array($varresult);
$SiteName=stripslashes($varrow[SiteName]);
$AdminEmail=$varrow[AdminEmail];
$Keywords=stripslashes($varrow[Keywords]);
$Description=stripslashes($varrow[Description]);
$MetaTitle=str_replace('"', "&quot;", stripslashes($varrow[MetaTitle]));
$EmailLink=$varrow[EmailLink];
$SiteMessage=stripslashes($varrow[SiteMessage]);
$domain_name = $varrow[URL];
$catalogpage = $varrow[CatalogPage];
$thumbnaildir = $varrow[ThumbnailDir];
$lgimagedir = $varrow[LgImageDir];
$catimagedir = $varrow[CatImageDir];
$imagedir = $varrow[ImageDir];
}
?>

<form method="POST" name="Vars" action="variables.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Company:</a></td>
<td vAlign="top" align="left" colspan="3">
<input type="text" name="sitename" value="<?php echo "$SiteName"; ?>"  size="46"> 
</td>
</tr>
<?php
if ($set_master_key == "yes" OR $show_advanced == "Yes")
{
?>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Web Site URL:</a></td>
<td vAlign="top" align="left" colspan="3">
<input type="text" name="domain_name" value="<?php echo "$domain_name"; ?>" size="46">
</td>
</tr>
<?php
}
else
echo "<input type=\"hidden\" name=\"domain_name\" value=\"$domain_name\">";
?>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Admin Email:</a></td>
<td vAlign="top" align="left" colspan="3">
<input type="text" name="adminemail" value="<?php echo "$AdminEmail"; ?>" size="46"> 
</td>
</tr>
<?php
if ($set_master_key == "yes" OR $show_advanced == "Yes")
{
?>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Catalog Page:</a></td>
<td vAlign="top" align="left">
<input type="text" name="catalogpage" value="<?php echo "$catalogpage"; ?>" size="12">
<input type="hidden" name="oldcatalog" value="<?php echo "$catalogpage"; ?>">
</td>
<td align="right" vAlign="top" nowrap>
<a title="" class="fieldname">Page Extension:</a></td>
<td vAlign="top" align="left">
<select style="width: 50px" size="1" name="page_ext">
<?php
echo "<option ";
if ($openrow[PageExt] == "php" OR !$openrow[PageExt])
echo "selected ";
echo "value=\"php\">.php</option>";
echo "<option ";
if ($openrow[PageExt]== "htm")
echo "selected ";
echo "value=\"htm\">.htm</option>";
echo "<option ";
if ($openrow[PageExt]== "html")
echo "selected ";
echo "value=\"html\">.html</option>";
?>
</select> 
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Sm Image Dir:</a></td>
<td vAlign="top" align="left">
<input type="text" name="thumbnaildir" value="<?php echo "$thumbnaildir"; ?>"  size="12">
</td>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Lg Image Dir:</a></td>
<td vAlign="top" align="left">
<input type="text" name="lgimagedir" value="<?php echo "$lgimagedir"; ?>"  size="12">
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Category Img Dir:</a></td>
<td vAlign="top" align="left">
<input type="text" name="catimagedir" value="<?php echo "$catimagedir"; ?>"  size="12">
</td>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">General Img Dir:</a></td>
<td vAlign="top" align="left">
<input type="text" name="imagedir" value="<?php echo "$imagedir"; ?>"  size="12">
</td>
</tr>
<?php
}
?>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Site Message:</a>
<br><span class="smalltext">255 chars max</span></td>
<td vAlign="top" align="left" colspan="3">
<textarea rows="5" name="sitemessage" id="sitemessage" cols="40"><?php echo "$SiteMessage"; ?></textarea>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Email Link:</a></td>
<td vAlign="top" align="left" colspan="3">
<textarea rows="2" name="emaillink" cols="40"><?php echo "$EmailLink"; ?></textarea>
</td>
</tr>
<tr>
<td vAlign="top" align="right" nowrap>
<a title="" class="fieldname">Meta Title:</a></td>
<td vAlign="top" align="left" colspan="3">
<input type="text" name="metatitle" value="<?php echo "$MetaTitle"; ?>"  size="46" maxLength="50"> 
</td>
</tr>
<tr>
<td valign="top" align="right" nowrap>
<a title="" class="fieldname">Meta Description:</a>
<br><span class="smalltext">100 chars max</span>
</td>
<td valign="top" align="left" colspan="3">
<textarea rows="2" name="description" cols="40"><?php echo "$Description"; ?></textarea>
</td>
</tr>
<tr>
<td valign="top" align="right" nowrap>
<a title="" class="fieldname">Meta Keywords:</a>
<br><span class="smalltext">100 chars max</span></td>
<td valign="top" align="left" colspan="3">
<textarea rows="2" name="keywords" cols="40"><?php echo "$Keywords"; ?></textarea>
</td>
</tr>
<tr>
<td vAlign="center" align="middle" colSpan="4">
<input type="submit" class="button" value="Update Variables" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
include("includes/links2.php");
include("includes/footer.htm");

}
?>

</html>