<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// DISPLAY LIST OF PAGES
$pagequery = "SELECT PageName, PageTitle, PageType, NavImage, ID FROM " .$DB_Prefix ."_pages ";
$pagequery .= "WHERE Active = 'Yes' AND ShowLink='Yes' ";
if ($pggrp)
$pagequery .= "AND LinkGroup LIKE '%$pggrp%' ";
if ($show_home == "no")
$pagequery .= "AND PageName <> 'index' ";
$pagequery .= "ORDER BY LinkOrder, PageTitle";
$pageresult = mysql_query($pagequery, $dblink) or die ("Unable to access database.");
for ($p=1; $pagerow = mysql_fetch_row($pageresult); ++$p)
{
$pg_title = stripslashes($pagerow[1]);
if ($pagerow[2] == "additional")
$pagelink = "pages/$pagerow[0].$pageext";
else
$pagelink = "$pagerow[0].$pageext";
if (substr_count($pagerow[3], "~") == 1)
{
echo "<a href=\"$URL/$pagelink\"";
$pgimgsplit = explode("~", $pagerow[3]);
if (substr($pgimgsplit[0], 0, 7) == "http://")
$pgimg1 = "$pgimgsplit[0]";
else
$pgimg1 = "$URL/$pgimgsplit[0]";
if (substr($pgimgsplit[1], 0, 7) == "http://")
$pgimg2 = "$pgimgsplit[1]";
else
$pgimg2 = "$URL/$pgimgsplit[1]";
if (!empty($pgimgsplit[0]) AND !empty($pgimgsplit[1]))
echo " onMouseover=\"document.hpg_$pagerow[4].src='$pgimg2'\" onMouseout=\"document.hpg_$pagerow[4].src='$pgimg1'\"";
echo ">";
echo "<img src=\"$pgimg1\" border=\"0\" name=\"hpg_$pagerow[4]\" alt=\"$pg_title\"></a>";
}
else if ($pagerow[3])
{
if (substr($pagerow[3], 0, 7) == "http://")
$imgurl = "$pagerow[3]";
else
$imgurl = "$URL/$pagerow[3]";
echo "<a href=\"$URL/$pagelink\">";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"$pg_title\"></a>";
}
else
{
if ($p > 1)
echo " | ";
echo "<a href=\"$URL/$pagelink\" class=\"pagelinkcolor\" nowrap>$pg_title</a>";
}
// Show horizontal categories
if ($show_cat_list == "horizontal" AND $pagelink == $catpg)
{
include("$Home_Path/$Inc_Dir/navbar_horizontal.php");
}
}
?>