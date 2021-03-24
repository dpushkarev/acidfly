<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$categoryquery = "SELECT Category, Image, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '' AND Active = 'Yes' ORDER BY CatOrder, Category";
$categoryresult = mysqli_query($dblink, $categoryquery) or die ("Unable to access database.");
for ($count = 1; $catrow = mysqli_fetch_row($categoryresult); ++$count)
{
$stripcat = stripslashes($catrow[0]);
$catname = str_replace(" ","&nbsp;",$stripcat);
$horiz_link = "$Catalog_Page?category=$catrow[2]";
// Mouseovers
if (substr_count($catrow[1], "~") == 1)
{
echo "<a href=\"$horiz_link\"";
$catimgsplit = explode("~", $catrow[1]);
if (substr($catimgsplit[0], 0, 7) == "http://")
$catimg1 = "$catimgsplit[0]";
else
$catimg1 = "$URL/$catimgsplit[0]";
if (substr($catimgsplit[1], 0, 7) == "http://")
$catimg2 = "$catimgsplit[1]";
else
$catimg2 = "$URL/$catimgsplit[1]";
if (!empty($catimgsplit[0]) AND !empty($catimgsplit[1]))
echo " onMouseover=\"document.hcat_$catrow[2].src='$catimg2'\" onMouseout=\"document.hcat_$catrow[2].src='$catimg1'\"";
echo ">";
echo "<img src=\"$catimg1\" border=\"0\" name=\"hcat_$catrow[2]\" alt=\"$catname\"></a>";
}
else if ($catrow[1])
{
if (substr($catrow[1], 0, 7) == "http://")
$imgurl = "$catrow[1]";
else
$imgurl = "$URL/$catrow[1]";
echo "<a href=\"$horiz_link\" class=\"catcolor\">";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"$catname\"></a>";
}
else
{
if ($count > 1)
echo " | ";
echo "<a href=\"$horiz_link\" class=\"catcolor\">$catname</a>";
}
}

// ADD NEW PRODUCT LINK
if ($New_Product_Link == "Yes")
{
$gotonew = "$Catalog_Page?new=yes";
if (substr_count($newnavimg, "~") == 1)
{
echo "<a href=\"$gotonew\" ";
$newnavsplit = explode("~", $newnavimg);
if (substr($newnavsplit[0], 0, 7) == "http://")
$newnav1 = "$newnavsplit[0]";
else
$newnav1 = "$URL/$newnavsplit[0]";
if (substr($newnavsplit[1], 0, 7) == "http://")
$newnav2 = "$newnavsplit[1]";
else
$newnav2 = "$URL/$newnavsplit[1]";
if (!empty($newnavsplit[0]) AND !empty($newnavsplit[1]))
echo "onMouseover=\"document.newhnav.src='$newnav2'\" onMouseout=\"document.newhnav.src='$newnav1'\"";
echo ">";
echo "<img src=\"$newnav1\" border=\"0\" name=\"newhnav\" alt=\"New Items\"></a>";
}
else if ($newnavimg)
{
if (substr($newnavimg, 0, 7) == "http://")
$imgurl = "$newnavimg";
else
$imgurl = "$URL/$newnavimg";
echo "<a href=\"$gotonew\">";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"New Items\"></a>";
}
else
echo " | <a href=\"$gotonew\" class=\"catcolor\" nowrap>New Items</a>";
}

// ADD FEATURED ITEM LINK
if ($Featured_Product_Link == "Yes")
{
$gotofeat = "$Catalog_Page?featured=yes";
if (substr_count($featurednavimg, "~") == 1)
{
echo "<a href=\"$gotofeat\" ";
$featurednavsplit = explode("~", $featurednavimg);
if (substr($featurednavsplit[0], 0, 7) == "http://")
$featurednav1 = "$featurednavsplit[0]";
else
$featurednav1 = "$URL/$featurednavsplit[0]";
if (substr($featurednavsplit[1], 0, 7) == "http://")
$featurednav2 = "$featurednavsplit[1]";
else
$featurednav2 = "$URL/$featurednavsplit[1]";
if (!empty($featurednavsplit[0]) AND !empty($featurednavsplit[1]))
echo "onMouseover=\"document.featuredhnav.src='$featurednav2'\" onMouseout=\"document.featuredhnav.src='$featurednav1'\"";
echo ">";
echo "<img src=\"$featurednav1\" border=\"0\" name=\"featuredhnav\" alt=\"Featured Items\"></a>";
}
else if ($featurednavimg)
{
if (substr($featurednavimg, 0, 7) == "http://")
$imgurl = "$featurednavimg";
else
$imgurl = "$URL/$featurednavimg";
echo "<a href=\"$gotofeat\">";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"Featured Items\"></a>";
}
else
echo " | <a href=\"$gotofeat\" class=\"catcolor\" nowrap>Featured Items</a>";
}

// ADD SALES ITEM LINK
$salequery = "SELECT ID FROM " .$DB_Prefix ."_items WHERE SalePrice <> '0'";
$saleresult = mysqli_query($dblink, $salequery) or die ("Unable to access database.");
if (mysqli_num_rows($saleresult) != 0 AND $Sales_Product_Link == "Yes")
{
$gotosale = "$Catalog_Page?sale=yes";
if (substr_count($salenavimg, "~") == 1)
{
echo "<a href=\"$gotosale\" ";
$salenavsplit = explode("~", $salenavimg);
if (substr($salenavsplit[0], 0, 7) == "http://")
$salenav1 = "$salenavsplit[0]";
else
$salenav1 = "$URL/$salenavsplit[0]";
if (substr($salenavsplit[1], 0, 7) == "http://")
$salenav2 = "$salenavsplit[1]";
else
$salenav2 = "$URL/$salenavsplit[1]";
if (!empty($salenavsplit[0]) AND !empty($salenavsplit[1]))
echo "onMouseover=\"document.salehnav.src='$salenav2'\" onMouseout=\"document.salenav.src='$salehnav1'\"";
echo ">";
echo "<img src=\"$salenav1\" border=\"0\" name=\"salehnav\" alt=\"Sale Items\"></a>";
}
else if ($salenavimg)
{
if (substr($salenavimg, 0, 7) == "http://")
$imgurl = "$salenavimg";
else
$imgurl = "$URL/$salenavimg";
echo "<a href=\"$gotosale\">";
echo "<img src=\"$salenavimg\" border=\"0\" alt=\"Sale Items\"></a>";
}
else
echo " | <a href=\"$gotosale\" class=\"catcolor\" nowrap>Sale Items</a>";
}

// ADD ALL PRODUCT LINK
if ($All_Product_Link == "Yes")
{
$gotoall = "$Catalog_Page?all=yes";
if (substr_count($allnavimg, "~") == 1)
{
echo "<a href=\"$gotoall\" ";
$allnavsplit = explode("~", $allnavimg);
if (substr($allnavsplit[0], 0, 7) == "http://")
$allnav1 = "$allnavsplit[0]";
else
$allnav1 = "$URL/$allnavsplit[0]";
if (substr($allnavsplit[1], 0, 7) == "http://")
$allnav2 = "$allnavsplit[1]";
else
$allnav2 = "$URL/$allnavsplit[1]";
if (!empty($allnavsplit[0]) AND !empty($allnavsplit[1]))
echo "onMouseover=\"document.allhnav.src='$allnav2'\" onMouseout=\"document.allhnav.src='$allnav1'\"";
echo ">";
echo "<img src=\"$allnav1\" border=\"0\" name=\"allhnav\" alt=\"All Items\"></a>";
}
else if ($allnavimg)
{
if (substr($allnavimg, 0, 7) == "http://")
$imgurl = "$allnavimg";
else
$imgurl = "$URL/$allnavimg";
echo "<a href=\"$gotoall\">";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"All Items\"></a>";
}
else
echo " | <a href=\"$gotoall\" class=\"catcolor\" nowrap>All Items</a>";
}

?>