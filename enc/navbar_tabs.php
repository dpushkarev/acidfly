<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$categoryquery = "SELECT Category, Image, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '' AND Active <> 'No' ORDER BY CatOrder, Category";
$categoryresult = mysql_query($categoryquery, $dblink) or die ("Unable to access database.");
$categorynum = mysql_num_rows($categoryresult);
$salequery = "SELECT ID FROM " .$DB_Prefix ."_items WHERE SalePrice <> '0'";
$saleresult = mysql_query($salequery, $dblink) or die ("Unable to access database.");
if ($New_Product_Link == "Yes")
++ $categorynum;
if ($Featured_Product_Link == "Yes")
++ $categorynum;
if (mysql_num_rows($saleresult) != 0 AND $Sales_Product_Link == "Yes")
++ $categorynum;
if ($All_Product_Link == "Yes")
++ $categorynum;

if ($categorynum > 0)
{
$cl_wdth = intval(100/$categorynum);
echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
echo "<tr>";
for ($count = 1; $catrow = mysql_fetch_row($categoryresult); ++$count)
{
$stripcat = stripslashes($catrow[0]);
$catname = str_replace(" ","&nbsp;",$stripcat);
$horiz_link = "$Catalog_Page?category=$catrow[2]";
if ($category == $catrow[2])
$settabstyle = "tabactive";
else
$settabstyle = "tabcell";
if ($count > 1)
echo "<td>&nbsp;</td>";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$settabstyle\">";
echo "<a href=\"$horiz_link\">$catname</a>";
echo "</td>";
}

// ADD NEW PRODUCT LINK
if ($New_Product_Link == "Yes")
{
if ($new == "yes")
$newtabstyle = "tabactive";
else
$newtabstyle = "tabcell";
echo "<td>&nbsp;</td>";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$newtabstyle\">";
echo "<a href=\"$Catalog_Page?new=yes\">New Items</a>";
echo "</td>";
}

// ADD FEATURED ITEM LINK
if ($Featured_Product_Link == "Yes")
{
if ($featured == "yes")
$featuredtabstyle = "tabactive";
else
$featuredtabstyle = "tabcell";
echo "<td>&nbsp;</td>";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$featuredtabstyle\">";
echo "<a href=\"$Catalog_Page?featured=yes\">Featured Items</a>";
echo "</td>";
}

// ADD SALES ITEM LINK
if (mysql_num_rows($saleresult) != 0 AND $Sales_Product_Link == "Yes")
{
if ($sale == "yes")
$saletabstyle = "tabactive";
else
$saletabstyle = "tabcell";
echo "<td>&nbsp;</td>";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$saletabstyle\">";
echo "<a href=\"$Catalog_Page?sale=yes\">Sale Items</a>";
echo "</td>";
}

// ADD ALL PRODUCT LINK
if ($All_Product_Link == "Yes")
{
if ($all == "yes")
$alltabstyle = "tabactive";
else
$alltabstyle = "tabcell";
echo "<td>&nbsp;</td>";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$alltabstyle\">";
echo "<a href=\"$Catalog_Page?all=yes\">All Items</a>";
echo "</td>";
}

echo "</tr>";
echo "</table>";
}
?>
