<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Get total table count
if ($Item_Columns == 0)
$totscount = $searchnum;
else if (($searchnum%$Item_Columns == 0) OR ($searchnum < $Item_Columns))
$totscount = $searchnum;
else
$totscount = $searchnum+($Item_Columns-($searchnum%$Item_Columns));
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" ";
if ($Product_Line)
echo "class=\"linetable\" ";
echo "width=\"100%\">";
// Display one item
for ($scount = 1; $searchrow = mysql_fetch_array($searchresult), $scount <= $totscount; ++$scount)
{
if ($Item_Columns == 0)
{
$toptr = 0;
$bottr = 0;
$tcwidth = 100;
}
else
{
$toptr = ($scount + ($Item_Columns-1)) % $Item_Columns;
$bottr = $scount % $Item_Columns;
if ($searchnum < $Item_Columns)
$tcwidth = floor(100/$searchnum);
else
$tcwidth = floor(100/$Item_Columns);
}
$stripitem = stripslashes($searchrow[2]);
$stripitem = str_replace("\"", "&quot;", $stripitem);
$keyword = urlencode($keyword);
if ($searchrow[12] == "Yes" OR ($searchrow[14] <= 0 AND $searchrow[14] != "" AND $inventorycheck == "Yes"))
$showprice = "<span class=\"accent\">Out of Stock</span>";
else if ($searchrow[11] > 0)
$showprice = "<strike>$Currency$searchrow[10]</strike> <span class=\"salecolor\">$Currency$searchrow[11]</span>";
else if ($searchrow[10] > 0)
$showprice = "$Currency$searchrow[10]";
else
$showprice = "";
if ($toptr == 0)
echo "<tr>";
echo "<td align=\"center\" valign=\"top\" ";
if ($Product_Line)
echo "class=\"linecell\" ";
echo "width=\"$tcwidth%\">";
if ($searchrow[0] == "")
echo "&nbsp;";
else
{
// Set return link
$m_ret = $setpg;
if ($_SERVER['QUERY_STRING'])
$m_ret .= "?" .$_SERVER['QUERY_STRING'];
$m_ret = urlencode($m_ret);

// If there is a small image, display it
if ($searchrow[4])
{
echo "<a href=\"$Catalog_Page?item=$searchrow[0]";
if ($category)
echo "&catid=$category";
if ($new == "yes")
echo "&new=yes";
if ($all == "yes")
echo "&all=yes";
if ($sale == "yes")
echo "&sale=yes";
echo "&ret=$m_ret\">";
if (substr($searchrow[4], 0, 7) == "http://")
$imgname = "$searchrow[4]";
else
$imgname = "$URL/$searchrow[4]";
echo "<img src=\"$imgname\" alt=\"$stripitem\" ";
if ($imgwidth > 0)
echo "width=\"$imgwidth\" ";
if ($imgheight > 0)
echo "height=\"$imgheight\" ";
echo "border=\"0\" ></a><br>";
}
// Display title
echo "<a href=\"$Catalog_Page?item=$searchrow[0]";
if ($category)
echo "&catid=$category";
if ($new == "yes")
echo "&new=yes";
if ($all == "yes")
echo "&all=yes";
if ($sale == "yes")
echo "&sale=yes";
echo "&ret=$m_ret\" class=\"itemcolor\">$stripitem</a>";
}
echo "<br>$showprice</td>";
if ($bottr == 0)
echo "</tr>";
}

// Finish off table if needed
if ($Item_Columns > 0)
{
$remaining = $Item_Columns - (($scount-1) % $Item_Columns);
if ($remaining > 0 AND $remaining < $Item_Columns)
{
if ($scount-1 < $Item_Columns)
echo "</tr>";
else
{
for ($rem=1; $rem <= $remaining; ++$rem)
{
echo "<td width=\"$tcwidth%\">&nbsp;</td>";
}
echo "</tr>";
}
}
}
echo "</table>";
?>