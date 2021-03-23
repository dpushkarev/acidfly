<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

echo "line: $Product_Line";
// Get total table count
if ($Product_Line)
echo "<table border=\"1\" bordercolor=\"$Product_Line\" cellpadding=\"3\" cellspacing=\"0\">";
else
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
echo "<tr>";
echo "<td align=\"left\" valign=\"top\" class=\"accent\">Item</td>";
echo "<td align=\"left\" valign=\"top\" class=\"accent\">Price</td>";
echo "<td>&nbsp;</td>";
echo "</tr>";
// Display items
while ($searchrow = mysql_fetch_array($searchresult))
{
$stripitem = stripslashes($searchrow[2]);
$stripitem = str_replace("\"", "&quot;", $stripitem);
if ($searchrow[0])
$stripitem = "#$searchrow[0]. " .$stripitem;
$keyword = urlencode($keyword);
if ($searchrow[12] == "Yes" OR ($searchrow[14] <= 0 AND $searchrow[14] != "" AND $inventorycheck == "Yes"))
$showprice = "<span class=\"accent\">Out of Stock</span>";
else if ($searchrow[11] > 0)
$showprice = "<strike>$Currency$searchrow[10]</strike> <span class=\"salecolor\">$Currency$searchrow[11]</span>";
else if ($searchrow[10] > 0)
$showprice = "$Currency$searchrow[10]";
else
$showprice = "";
// Set return link
$m_ret = $setpg;
if ($_SERVER['QUERY_STRING'])
$m_ret .= "?" .$_SERVER['QUERY_STRING'];
$m_ret = urlencode($m_ret);
echo "<tr>";
echo "<td align=\"left\" valign=\"top\" class=\"itemcolor\">$stripitem</td>";
echo "<td align=\"left\" valign=\"top\">$showprice</td>";
echo "<td align=\"left\" valign=\"top\">";
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
echo "&ret=$m_ret\">More Info</a></td>";
echo "</tr>";
}
echo "</table>";
?>
