<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$categoryquery = "SELECT Category, Image, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '' AND Active <> 'No' ORDER BY CatOrder, Category";
$categoryresult = mysqli_query($dblink, $categoryquery) or die ("Unable to access database.");
$categorynum = mysqli_num_rows($categoryresult);
$salequery = "SELECT ID FROM " .$DB_Prefix ."_items WHERE SalePrice <> '0'";
$saleresult = mysqli_query($dblink, $salequery) or die ("Unable to access database.");
if ($New_Product_Link == "Yes")
++ $categorynum;
if ($Featured_Product_Link == "Yes")
++ $categorynum;
if (mysqli_num_rows($saleresult) != 0 AND $Sales_Product_Link == "Yes")
++ $categorynum;
if ($All_Product_Link == "Yes")
++ $categorynum;

if ($categorynum > 0)
{
$cl_wdth = intval(100/$categorynum);
echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"bar\">";
echo "<tr>";
for ($count = 1; $catrow = mysqli_fetch_row($categoryresult); ++$count)
{
$stripcat = stripslashes($catrow[0]);
$catname = str_replace(" ","&nbsp;",$stripcat);
$horiz_link = "$Catalog_Page?category=$catrow[2]";
if ($category == $catrow[2])
$setbarstyle = "baractive";
else
$setbarstyle = "barcell";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$setbarstyle\">";
echo "<a href=\"$horiz_link\">$catname</a>";
echo "</td>";
}

// ADD NEW PRODUCT LINK
if ($New_Product_Link == "Yes")
{
if ($new == "yes")
$newbarstyle = "baractive";
else
$newbarstyle = "barcell";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$newbarstyle\">";
echo "<a href=\"$Catalog_Page?new=yes\">New Items</a>";
echo "</td>";
}

// ADD FEATURED ITEM LINK
if ($Featured_Product_Link == "Yes")
{
if ($featured == "yes")
$featuredbarstyle = "baractive";
else
$featuredbarstyle = "barcell";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$featuredbarstyle\">";
echo "<a href=\"$Catalog_Page?featured=yes\">Featured Items</a>";
echo "</td>";
}

// ADD SALES ITEM LINK
if (mysqli_num_rows($saleresult) != 0 AND $Sales_Product_Link == "Yes")
{
if ($sale == "yes")
$salebarstyle = "baractive";
else
$salebarstyle = "barcell";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$salebarstyle\">";
echo "<a href=\"$Catalog_Page?sale=yes\">Sale Items</a>";
echo "</td>";
}

// ADD ALL PRODUCT LINK
if ($All_Product_Link == "Yes")
{
if ($all == "yes")
$allbarstyle = "baractive";
else
$allbarstyle = "barcell";
echo "<td valign=\"middle\" align=\"center\" width=\"$cl_wdth%\" class=\"$allbarstyle\">";
echo "<a href=\"$Catalog_Page?all=yes\">All Items</a>";
echo "</td>";
}

echo "</tr>";
echo "</table>";
}
?>
