<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Set page numbers
if (!$page)
{
$offset = 0;
$page = 1;
}
else
$offset = (($LimitOfItems * $page)-$LimitOfItems);

$searchquery .= " GROUP BY " .$DB_Prefix ."_items.ID";

$totalsearchquery = $searchquery;
if ($new)
$totalsearchquery .= " LIMIT $LimitOfItems";

// Total number of records in this particular page
if ($new == "yes")
$searchquery .= " ORDER BY " .$DB_Prefix ."_items.ID DESC";
else if ($Order_Of_Product)
$searchquery .= " ORDER BY $Order_Of_Product";
$searchquery .= " LIMIT $offset, $LimitOfItems";

$searchresult = mysqli_query($dblink, $searchquery) or die ("Unable to access database. Please make sure you have entered your system variables in your administration area.");
$searchnum = mysqli_num_rows($searchresult);

// Total records altogether
$totalsearchresult = mysqli_query($dblink, $totalsearchquery) or die ("Unable to access database.");
$totalsearchnum = mysqli_num_rows($totalsearchresult);

// Unsuccessful wholesale login
if ($mode == "ws" AND ($wsnum != 1 OR !$wsrow[0]))
{
echo "<p align=\"center\" class=\"accent\">";
echo "Sorry, your login was not successful. Please <a href=\"wholesale.$pageext?wslg=y\">try again</a>.";
echo "</p>";
}

// No wholesale login or successful login
else
{

// Get wholesale info if needed
if ($wsmsg AND $wspass AND $wsemail)
echo "$wsmsg";

// Get member code info if needed and no wholesale
else if ($memmsg AND $membercode AND $memdisc > 0)
echo "$memmsg";

// Catalog page is displayed with no vars
if (!$category AND !$keyword AND !$cond AND !$item AND !$catalog AND !$price AND !$sale AND !$featured AND !$all AND !$new)
{
// Display initial page (based on feature listing for catalog setting)
if (substr($catalogproducts, 0, 7) == "catlist")
{
$Item_Columns = substr($catalogproducts, 7,1);
include("$Home_Path/$Inc_Dir/products_maincat.php");
include("$Home_Path/$Inc_Dir/search.php");
}
else if ($catalogproducts != "")
{
$searchquery = "SELECT * FROM " .$DB_Prefix ."_items WHERE Active='Yes' ";
if (empty($wspass) AND empty($wsemail) AND $wsnum != 1)
$searchquery .= "AND WSOnly = 'No' ";
if ($catalogproducts == "new")
$searchquery .= "ORDER BY DateEdited ";
else
{
if ($catalogproducts == "sale")
$searchquery .= "AND SalePrice > '0'";
else
$searchquery .= "AND Featured='Yes'";
if ($Order_Of_Product)
$searchquery .= " ORDER BY $Order_Of_Product ";
}
$searchquery .= "LIMIT $LimitOfItems";
$searchresult = mysqli_query($dblink, $searchquery) or die ("Unable to select. Try again later.");
$searchnum = mysqli_num_rows($searchresult);
if ($Item_Columns == "G")
{
$Item_Columns = 0;
include("$Home_Path/$Inc_Dir/products_list.php");
}
else if ($Item_Columns == "D" OR $Item_Columns == "T")
{
if ($Item_Columns == "T")
$Item_Columns = 3;
else
$Item_Columns = 2;
include("$Home_Path/$Inc_Dir/products_multisingle.php");
}
else if ($Item_Columns == "1")
{
include("$Home_Path/$Inc_Dir/products_single.php");
}
else
{
include("$Home_Path/$Inc_Dir/products_multiple.php");
}
}
}

// If there are no records, display error message
else if ($totalsearchnum == 0)
{
echo "<p align=\"center\">Sorry, but there are no products matching this criteria. Please try again.</p>";
include("$Home_Path/$Inc_Dir/search.php");
}

// *** DISPLAY SINGLE ITEM LISTING (DETAILED LISTING) ***
else if ($item OR $catalog)
{
include ("$Home_Path/$Inc_Dir/products_detail.php");
}

else
{
// Show main category listing if it exists
if ($maincat_set == "yes" AND $showmaincat != "Products")
{
include ("$Home_Path/$Inc_Dir/products_maincat.php");
echo "<p>&nbsp;</p>";
}

if (($maincat_set == "yes" AND $showmaincat != "Categories") OR $maincat_set != "yes")
{
// *** DISPLAY LIST FORMAT ***
if ($Item_Columns == "G")
{
include ("$Home_Path/$Inc_Dir/products_pages.php");
include ("$Home_Path/$Inc_Dir/products_list.php");
include ("$Home_Path/$Inc_Dir/products_pages.php");
}
// *** DISPLAY SINGLE COLUMN LISTING ***
else if ($Item_Columns == "1")
{
include ("$Home_Path/$Inc_Dir/products_pages.php");
include ("$Home_Path/$Inc_Dir/products_single.php");
include ("$Home_Path/$Inc_Dir/products_pages.php");
}
// *** DISPLAY DOUBLE OR TRIPLE COLUMN LISTING ***
else if ($Item_Columns == "D" OR $Item_Columns == "T")
{
if ($Item_Columns == "T")
$Item_Columns = 3;
else
$Item_Columns = 2;
include ("$Home_Path/$Inc_Dir/products_pages.php");
include ("$Home_Path/$Inc_Dir/products_multisingle.php");
include ("$Home_Path/$Inc_Dir/products_pages.php");
}
// *** DISPLAY MULTIPLE COLUMN LISTING ***
else
{
include ("$Home_Path/$Inc_Dir/products_pages.php");
include ("$Home_Path/$Inc_Dir/products_multiple.php");
include ("$Home_Path/$Inc_Dir/products_pages.php");
}
}

}
}
?>
