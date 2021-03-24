<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">";
echo "<tr>";
echo "<td valign=\"top\" width=\"50%\" class=\"boldtext\">";
echo "SITE PAGES";
// DISPLAY LIST OF PAGES
$pagequery = "SELECT PageName, PageTitle, PageType, NavImage FROM " .$DB_Prefix ."_pages ";
$pagequery .= "WHERE Active = 'Yes' AND ShowLink = 'Yes'";
$pagequery .= "ORDER BY LinkOrder, PageTitle";
$pageresult = mysqli_query($dblink, $pagequery) or die ("Unable to access database.");
while ($pagerow = mysqli_fetch_row($pageresult))
{
$pg_title = stripslashes($pagerow[1]);
if ($pagerow[2] == "additional")
$pagelink = "pages/$pagerow[0].$pageext";
else
$pagelink = "$pagerow[0].$pageext";
echo "<br><a href=\"$URL/$pagelink\">$pg_title</a>";
}
echo "</td>";
echo "<td valign=\"top\" width=\"50%\" class=\"boldtext\">";
echo "CATALOG";

// DISPLAY  PRODUCT LIST
echo "<br><a href=\"$URL/go/itemlist.$pageext\">Product List</a>";

// DISPLAY CATEGORIES
$categoryquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent = '' AND Active <> 'No' ORDER BY Category";
$categoryresult = mysqli_query($dblink, $categoryquery) or die ("Unable to access database.");
while ($catrow = mysqli_fetch_row($categoryresult))
{
$stripcat = stripslashes($catrow[1]);
// Sub category list
$subcatquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent = '$catrow[0]' AND Active <> 'No' ORDER BY Category";
$subcatresult = mysqli_query($dblink, $subcatquery) or die ("Unable to access database.");
$subcatnum = mysqli_num_rows($subcatresult);
$gotocat = "$Catalog_Page?category=$catrow[0]";
echo "<br><a href=\"$gotocat\">$stripcat</a>";
while ($subcatrow = mysqli_fetch_row($subcatresult))
{
$stripsubcat = stripslashes($subcatrow[1]);
// End category list
$endcatquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent = '$subcatrow[0]' AND Active <> 'No' ORDER BY Category";
$endcatresult = mysqli_query($dblink, $endcatquery) or die ("Unable to access database.");
$endcatnum = mysqli_num_rows($endcatresult);
$gotosubcat = "$Catalog_Page?category=$subcatrow[0]";
echo "<br>&nbsp;&nbsp;-&nbsp;";
echo "<a href=\"$gotosubcat\">$stripsubcat</a>";
while ($endcatrow = mysqli_fetch_row($endcatresult))
{
$stripendcat = stripslashes($endcatrow[1]);
$gotoendcat = "$Catalog_Page?category=$endcatrow[0]";
echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;";
echo "<a href=\"$gotoendcat\">$stripendcat</a>";
}
}
}

echo "</td>";
echo "</tr>";
echo "</table>";
?>
