<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// DISPLAY LIST OF PAGES
$pagequery = "SELECT PageName, PageTitle, PageType, NavImage FROM " .$DB_Prefix ."_pages ";
$pagequery .= "WHERE Active = 'Yes' AND ShowLink='Yes' ";
if ($pggrp)
$pagequery .= "AND LinkGroup LIKE '%$pggrp%' ";
if ($show_home == "no")
$pagequery .= "AND PageName <> 'index' ";
$pagequery .= "ORDER BY LinkOrder, PageTitle";
$pageresult = mysql_query($pagequery, $dblink) or die ("Unable to access database.");
$pagenum = mysql_num_rows($pageresult);
$cl_wdth = intval(100/$pagenum);
echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
echo "<tr>";
for ($p=1; $pagerow = mysql_fetch_row($pageresult); ++$p)
{
// Show page link
$pg_title = stripslashes($pagerow[1]);
if ($pagerow[2] == "additional")
$pagelink = "pages/$pagerow[0].$pageext";
else
$pagelink = "$pagerow[0].$pageext";
if ($p > 1)
echo "<td>&nbsp;</td>";
if ($pgnm_noext == $pagerow[0])
$settabstyle = "tabactive";
else
$settabstyle = "tabcell";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$settabstyle\">";
echo "<a href=\"$URL/$pagelink\">";
echo "$pg_title</a>";
echo "</td>";
}
echo "</tr>";
echo "</table>";
?>
