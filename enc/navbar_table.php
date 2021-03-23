<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$categoryquery = "SELECT Category, Image, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '' AND Active <> 'No' ORDER BY CatOrder, Category";
$categoryresult = mysql_query($categoryquery, $dblink) or die ("Unable to access database.");
$categorynum = mysql_num_rows($categoryresult);
if ($categorynum > 0)
{
echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"5\">";
for ($count = 1; $catrow = mysql_fetch_row($categoryresult); ++$count)
{
$stripcat = stripslashes($catrow[0]);
$catname = str_replace(" ","&nbsp;",$stripcat);
$horiz_link = "$Catalog_Page?category=$catrow[2]";
if ($category == $catrow[2])
$setbuttonstyle = "buttonactive";
else
$setbuttonstyle = "buttoncell";
echo "<tr>";
echo "<td class=\"$setbuttonstyle\" valign=\"middle\" align=\"center\">";
echo "<a href=\"$horiz_link\">$catname</a>";
echo "</td>";
echo "</tr>";
}

// ADD NEW PRODUCT LINK
if ($New_Product_Link == "Yes")
{
if ($new == "yes")
$newtablestyle = "buttonactive";
else
$newtablestyle = "buttoncell";
echo "<tr>";
echo "<td class=\"$newtablestyle\" valign=\"middle\" align=\"center\">";
echo "<a href=\"$Catalog_Page?new=yes\">New Items</a>";
echo "</td>";
echo "</tr>";
}

// ADD FEATURED ITEM LINK
if ($Featured_Product_Link == "Yes")
{
if ($featured == "yes")
$featuredtablestyle = "buttonactive";
else
$featuredtablestyle = "buttoncell";
echo "<tr>";
echo "<td class=\"$featuredtablestyle\" valign=\"middle\" align=\"center\">";
echo "<a href=\"$Catalog_Page?featured=yes\">Featured Items</a>";
echo "</td>";
echo "</tr>";
}

// ADD SALES ITEM LINK
$salequery = "SELECT ID FROM " .$DB_Prefix ."_items WHERE SalePrice <> '0'";
$saleresult = mysql_query($salequery, $dblink) or die ("Unable to access database.");
if (mysql_num_rows($saleresult) != 0 AND $Sales_Product_Link == "Yes")
{
if ($sale == "yes")
$saletablestyle = "buttonactive";
else
$saletablestyle = "buttoncell";
echo "<tr>";
echo "<td class=\"$saletablestyle\" valign=\"middle\" align=\"center\">";
echo "<a href=\"$Catalog_Page?sale=yes\">Sale Items</a>";
echo "</td>";
echo "</tr>";
}

// ADD ALL PRODUCT LINK
if ($All_Product_Link == "Yes")
{
if ($all == "yes")
$alltablestyle = "buttonactive";
else
$alltablestyle = "buttoncell";
echo "<tr>";
echo "<td class=\"$alltablestyle\" valign=\"middle\" align=\"center\">";
echo "<a href=\"$Catalog_Page?all=yes\">All Items</a>";
echo "</td>";
echo "</tr>";
}

echo "</table>";
}
?>
