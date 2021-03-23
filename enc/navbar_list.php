<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($catpg == $setpg)
{
echo "<p>";

// Main Catalog Page
echo "<a href=\"$Catalog_Page\" class=\"drilldown\">$navlisttitle</a> &gt; ";

if ($category)
{
$catquery = "SELECT Category, Parent FROM " .$DB_Prefix ."_categories WHERE ID='$category'";
$catresult = mysql_query($catquery, $dblink) or die ("Unable to select. Try again later.");
$catrow = mysql_fetch_row($catresult);
$category_link = "$catrow[0]";

// Check to see if a parent exists
if ($catrow[1] > 0)
{
$parquery = "SELECT Category, Parent, ID FROM " .$DB_Prefix ."_categories WHERE ID='$catrow[1]'";
$parresult = mysql_query($parquery, $dblink) or die ("Unable to select. Try again later.");
$parrow = mysql_fetch_row($parresult);
$parlink = "$Catalog_Page?category=$parrow[2]";
$parent_link = "<a href=\"$parlink\" class=\"drilldown\">$parrow[0]</a>";

// Check to see if a grandparent exists
if ($parrow[1] > 0)
{
$gparquery = "SELECT Category, Parent, ID FROM " .$DB_Prefix ."_categories WHERE ID='$parrow[1]'";
$gparresult = mysql_query($gparquery, $dblink) or die ("Unable to select. Try again later.");
$gparrow = mysql_fetch_row($gparresult);
$gparlink = "$Catalog_Page?category=$gparrow[2]";
$grandparent_link = "<a href=\"$gparlink\" class=\"drilldown\">$gparrow[0]</a>";
}
}

if ($grandparent_link)
echo "$grandparent_link &gt; ";
if ($parent_link)
echo "$parent_link &gt; ";
if ($category_link)
echo "$category_link";
}

else if ($all == "yes")
{
$gotoall = "$Catalog_Page?all=yes";
if ($item)
echo "<a href=\"$gotoall\" class=\"drilldown\">All Items</a>";
else
echo "All Items";
}

else if ($new == "yes")
{
$gotonew = "$Catalog_Page?new=yes";
if ($item)
echo "<a href=\"$gotonew\" class=\"drilldown\">New Items</a>";
else
echo "New Items";
}

else if ($sale == "yes")
{
$gotosale = "$Catalog_Page?sale=yes";
if ($item)
echo "<a href=\"$gotosale\" class=\"drilldown\">Sale Items</a>";
else
echo "Sale Items";
}

else if ($featured == "yes")
{
$gotosale = "$Catalog_Page?featured=yes";
if ($item)
echo "<a href=\"$gotosale\" class=\"drilldown\">Featured Items</a>";
else
echo "Featured Items";
}

else if ($item OR $catalog)
echo "Product Details";

else if ($keyword OR $price)
echo "Search Results";

else
echo "Information";

echo "</p>";
}
?>
