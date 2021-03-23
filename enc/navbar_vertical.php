<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if (!$navtype AND !$NavBarType)
$navtype = "vertical";
else if (!$navtype)
$navtype = $NavBarType;

// DISPLAY CATEGORIES/SUBCATEGORIES
if (!$baralign)
$baralign = "<br>";

// Display main categories
$categoryquery = "SELECT Category, Image, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '' AND Active = 'Yes' ORDER BY CatOrder, Category";
$categoryresult = mysql_query($categoryquery, $dblink) or die ("Unable to access database.");
for ($count = 1; $catrow = mysql_fetch_row($categoryresult); ++$count)
{
if ($count != 1)
echo "$baralign";

$catname = str_replace(" ","&nbsp;",stripslashes($catrow[0]));
// Get total subcategory numbers
$subcatquery = "SELECT Category, Image, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '$catrow[2]' AND Active = 'Yes' ORDER BY CatOrder, Category";
$subcatresult = mysql_query($subcatquery, $dblink) or die ("Unable to access database.");
$subcatnum = mysql_num_rows($subcatresult);

$gotocat = "$Catalog_Page?category=$catrow[2]";

if (substr_count($catrow[1], "~") == 1)
{
echo "<a href=\"$gotocat\" ";
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
echo "onMouseover=\"document.vcat_$catrow[2].src='$catimg2'\" onMouseout=\"document.vcat_$catrow[2].src='$catimg1'\"";
echo ">";
echo "<img src=\"$catimg1\" border=\"0\" name=\"vcat_$catrow[2]\" alt=\"$catname\"></a>";
}
else if ($catrow[1])
{
echo "<a href=\"$gotocat\">";
if (substr($catrow[1], 0, 7) == "http://")
$imgurl = "$catrow[1]";
else
$imgurl = "$URL/$catrow[1]";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"$catname\"></a>";
}
else
echo "<a href=\"$gotocat\" class=\"catcolor\" nowrap>$catname</a>";

// For expanded cats, get parent and grandparent values
if ($navtype == "expanded" AND $category)
{
$parentquery = "SELECT Parent FROM " .$DB_Prefix ."_categories WHERE ID='$category'";
$parentresult = mysql_query($parentquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($parentresult) == 1)
{
$parentrow = mysql_fetch_row($parentresult);
$parentid = $parentrow[0];
$grandparentquery = "SELECT Parent FROM " .$DB_Prefix ."_categories WHERE ID='$parentid'";
$grandparentresult = mysql_query($grandparentquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($grandparentresult) == 1)
{
$grandparentrow = mysql_fetch_row($grandparentresult);
$grandparentid = $grandparentrow[0];
}
else
$grandparentid = 0;
}
}

// Loop through subcats
if ($navtype == "subcategories" OR ($navtype == "expanded" AND $category > 0 AND ($catrow[2] == $category OR $catrow[2] == $parentid OR $catrow[2] == $grandparentid)))
{
for ($subcount = 1; $subcatrow = mysql_fetch_row($subcatresult); ++$subcount)
{
$subcatname = str_replace(" ","&nbsp;",stripslashes($subcatrow[0]));
echo "$baralign";
// Get total end category numbers
$endcatquery = "SELECT Category, Image, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '$subcatrow[2]' AND Active = 'Yes' ORDER BY CatOrder, Category";
$endcatresult = mysql_query($endcatquery, $dblink) or die ("Unable to access database.");
$endcatnum = mysql_num_rows($endcatresult);

$gotocat = "$Catalog_Page?category=$subcatrow[2]";
 
if (substr_count($subcatrow[1], "~") == 1)
{
echo "<a href=\"$gotocat\" ";
$subcatimgsplit = explode("~", $subcatrow[1]);
if (substr($subcatimgsplit[0], 0, 7) == "http://")
$subcatimg1 = "$subcatimgsplit[0]";
else
$subcatimg1 = "$URL/$subcatimgsplit[0]";
if (substr($subcatimgsplit[1], 0, 7) == "http://")
$subcatimg2 = "$subcatimgsplit[1]";
else
$subcatimg2 = "$URL/$subcatimgsplit[1]";
if (!empty($subcatimgsplit[0]) AND !empty($subcatimgsplit[1]))
echo "onMouseover=\"document.vsubcat_$subcatrow[2].src='$subcatimg2'\" onMouseout=\"document.vsubcat_$subcatrow[2].src='$subcatimg1'\"";
echo ">";
echo "<img src=\"$subcatimg1\" border=\"0\" name=\"vsubcat_$subcatrow[2]\" alt=\"$subcatname\"></a>";
}
else if ($subcatrow[1])
{
echo "<a href=\"$gotocat\">";
if (substr($subcatrow[1], 0, 7) == "http://")
$imgurl = "$subcatrow[1]";
else
$imgurl = "$URL/$subcatrow[1]";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"$subcatname\"></a>";
}
else
echo "• <a href=\"$gotocat\" class=\"small\" nowrap>$subcatname</a> •";

// Loop through endcats
if ($navtype == "subcategories" OR ($navtype == "expanded" AND $category > 0 AND ($subcatrow[2] == $category OR $subcatrow[2] == $parentid)))
{
for ($endcount = 1; $endcatrow = mysql_fetch_row($endcatresult); ++$endcount)
{
$endcatname = str_replace(" ","&nbsp;", stripslashes($endcatrow[0]));
echo "$baralign";
$gotocat = "$Catalog_Page?category=$endcatrow[2]";

// Show end category link
if (substr_count($endcatrow[1], "~") == 1)
{
echo "<a href=\"$gotocat\" ";
$endcatimgsplit = explode("~", $endcatrow[1]);
if (substr($endcatimgsplit[0], 0, 7) == "http://")
$endcatimg1 = "$endcatimgsplit[0]";
else
$endcatimg1 = "$URL/$endcatimgsplit[0]";
if (substr($endcatimgsplit[1], 0, 7) == "http://")
$endcatimg2 = "$endcatimgsplit[1]";
else
$endcatimg2 = "$URL/$endcatimgsplit[1]";
if (!empty($endcatimgsplit[0]) AND !empty($endcatimgsplit[1]))
echo "onMouseover=\"document.vendcat_$endcatrow[2].src='$endcatimg2'\" onMouseout=\"document.vendcat_$endcatrow[2].src='$endcatimg1'\"";
echo ">";
echo "<img src=\"$endcatimg1\" border=\"0\" name=\"vendcat_$endcatrow[2]\" alt=\"$endcatname\"></a>";
}
else if ($endcatrow[1])
{
echo "<a href=\"$gotocat\">";
if (substr($endcatrow[1], 0, 7) == "http://")
$imgurl = "$endcatrow[1]";
else
$imgurl = "$URL/$endcatrow[1]";
echo "<img src=\"$imgurl\" border=\"0\" alt=\"$endcatname\"></a>";
}
else
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$gotocat\" class=\"endcatcolor\" nowrap>$endcatname</a>";
}
}
}
}
}

// ADD NEW PRODUCT LINK
if ($New_Product_Link == "Yes")
{
$gotonew = "$Catalog_Page?new=yes";
echo "$baralign";
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
echo "onMouseover=\"document.newvnav.src='$newnav2'\" onMouseout=\"document.newvnav.src='$newnav1'\"";
echo ">";
echo "<img src=\"$newnav1\" border=\"0\" name=\"newvnav\" alt=\"New Items\"></a>";
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
echo "<a href=\"$gotonew\" class=\"catcolor\" nowrap>New Items</a>";
}

// ADD FEATURED ITEM LINK
if ($Featured_Product_Link == "Yes")
{
$gotofeat = "$Catalog_Page?featured=yes";
echo "$baralign";
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
echo "onMouseover=\"document.featuredvnav.src='$featurednav2'\" onMouseout=\"document.featuredvnav.src='$featurednav1'\"";
echo ">";
echo "<img src=\"$featurednav1\" border=\"0\" name=\"featuredvnav\" alt=\"Featured Items\"></a>";
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
echo "<a href=\"$gotofeat\" class=\"catcolor\" nowrap>Featured Items</a>";
}

// ADD SALES ITEM LINK
$salequery = "SELECT ID FROM " .$DB_Prefix ."_items WHERE SalePrice <> '0'";
$saleresult = mysql_query($salequery, $dblink) or die ("Unable to access database.");
if (mysql_num_rows($saleresult) != 0 AND $Sales_Product_Link == "Yes")
{
$gotosale = "$Catalog_Page?sale=yes";
echo "$baralign";
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
echo "onMouseover=\"document.salevnav.src='$salenav2'\" onMouseout=\"document.salevnav.src='$salenav1'\"";
echo ">";
echo "<img src=\"$salenav1\" border=\"0\" name=\"salevnav\" alt=\"Sale Items\"></a>";
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
echo "<a href=\"$gotosale\" class=\"catcolor\" nowrap>Sale Items</a>";
}

// ADD ALL PRODUCT LINK
if ($All_Product_Link == "Yes")
{
$gotoall = "$Catalog_Page?all=yes";
echo "$baralign";
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
echo "onMouseover=\"document.allvnav.src='$allnav2'\" onMouseout=\"document.allvnav.src='$allnav1'\"";
echo ">";
echo "<img src=\"$allnav1\" border=\"0\" name=\"allvnav\" alt=\"All Items\"></a>";
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
echo "<a href=\"$gotoall\" class=\"catcolor\" nowrap>All Items</a>";
}
?>