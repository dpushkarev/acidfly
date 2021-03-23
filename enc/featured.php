<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Set return link
if ($dirname)
$f_ret = $dirname ."/" .$setpg;
else
$f_ret = $setpg;
if ($_SERVER['QUERY_STRING'])
$f_ret .= "?" .$_SERVER['QUERY_STRING'];
$f_ret = urlencode($f_ret);

$featquery = "SELECT * FROM " .$DB_Prefix ."_items WHERE Featured='Yes' AND Active='Yes'";
if (!$wspass AND !$wsemail AND $wsnum != 1)
$featquery .= " AND WSOnly = 'No'";
$featquery .= " ORDER BY DateEdited LIMIT 5";
$featresult = mysql_query($featquery, $dblink) or die ("Unable to select. Try again later.");
$featnum = mysql_num_rows($featresult);
if ($featnum == 0)
{
$featquery = "SELECT * FROM " .$DB_Prefix ."_items WHERE Active='Yes' ORDER BY DateEdited LIMIT 5";
$featresult = mysql_query($featquery, $dblink) or die ("Unable to select. Try again later.");
}

while ($featrow = mysql_fetch_array($featresult))
{
$featitem = stripslashes($featrow[Item]);
$linktoitem = "$Catalog_Page?item=$featrow[ID]";
if ($f_ret)
$linktoitem .= "&ret=$f_ret";
if ($featrow[12] == "Yes" OR ($featrow[14] <= 0 AND $featrow[14] != "" AND $inventorycheck == "Yes"))
$showprice = "<span class=\"accent\">Out of Stock</span>";
else if ($featrow[11] > 0)
$showprice = "<strike>$Currency$featrow[10]</strike> <span class=\"salecolor\">$Currency$featrow[11]</span>";
else if ($featrow[10] > 0)
$showprice = "$Currency$featrow[10]";
else
$showprice = "";
echo "<p align=\"center\">";
if ($featrow[SmImage])
{
echo "<a href=\"$linktoitem\">";
if (substr($featrow[SmImage], 0, 7) == "http://")
$imgname = "$featrow[SmImage]";
else
$imgname = "$URL/$featrow[SmImage]";
echo "<img src=\"$imgname\" alt=\"$featitem\" ";
if ($imgwidth > 0)
echo "width=\"$imgwidth\" ";
if ($imgheight > 0)
echo "height=\"$imgheight\" ";
echo "border=\"0\"></a><br>";
}
echo "<a href=\"$linktoitem\" class=\"featurecolor\">$featitem</a>";
echo "<br>$showprice";
}
?>
