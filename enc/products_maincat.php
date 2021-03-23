<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($Item_Columns == "T")
$Cat_Columns = 3;
else if ($Item_Columns == "D")
$Cat_Columns = 2;
else if ($Item_Columns == "G")
$Cat_Columns = 1;
else
$Cat_Columns = $Item_Columns;
$maincatquery = "SELECT Category, ListImage, ID FROM " .$DB_Prefix ."_categories ";
if ($category)
$maincatquery .= "WHERE Parent = '$category' ";
else
{
echo "<p>To browse through our products, click on the category from our list to view ";
echo "all items in that category. You may also use our search tool to find a product ";
echo "with one or more keywords.</p>";
$maincatquery .= "WHERE Parent = '0' ";
}
$maincatquery .= "AND Active='Yes' ORDER BY CatOrder, Category";
$maincatresult = mysql_query($maincatquery, $dblink) or die ("Unable to access database.");
$maincatnum = mysql_num_rows($maincatresult);
$maincatarray = array();
$showimages = "no";
while ($maincatrow = mysql_fetch_array($maincatresult))
{
$stripcat = stripslashes($maincatrow[0]);
$maincatlink = "$Catalog_Page?category=$maincatrow[2]";
$maincatlinkval = "";

// Mouseovers
if (substr_count($maincatrow[1], "~") == 1)
{
$maincatlinkval .= "<a href=\"$maincatlink\"";
$mcatimgsplit = explode("~", $maincatrow[1]);
if (substr($mcatimgsplit[0], 0, 7) == "http://")
$mcatimg1 = "$mcatimgsplit[0]";
else
$mcatimg1 = "$URL/$mcatimgsplit[0]";
if (substr($mcatimgsplit[1], 0, 7) == "http://")
$mcatimg2 = "$mcatimgsplit[1]";
else
$mcatimg2 = "$URL/$mcatimgsplit[1]";
if (!empty($mcatimgsplit[0]) AND !empty($mcatimgsplit[1]))
$maincatlinkval .= " onMouseover=\"document.mcat_$maincatrow[2].src='$mcatimg2'\" onMouseout=\"document.mcat_$maincatrow[2].src='$mcatimg1'\"";
$maincatlinkval .= ">";
$maincatlinkval .= "<img src=\"$mcatimg1\" border=\"0\" ";
if ($imgheight)
$maincatlinkval .= "height=\"$imgheight\" ";
if ($imgwidth)
$maincatlinkval .= "width=\"$imgwidth\" ";
$maincatlinkval .= "name=\"mcat_$maincatrow[2]\" alt=\"$stripcat\"></a><br>";
$showimages = "yes";
}

else if ($maincatrow[1])
{
if (substr($maincatrow[1], 0, 7) == "http://")
$imgname = "$maincatrow[1]";
else
$imgname = "$URL/$maincatrow[1]";
$maincatlinkval .= "<a href=\"$maincatlink\" class=\"itemcolor\">";
$maincatlinkval .= "<img border=\"0\" src=\"$imgname\" ";
if ($imgheight)
$maincatlinkval .= "height=\"$imgheight\" ";
if ($imgwidth)
$maincatlinkval .= "width=\"$imgwidth\" ";
$maincatlinkval .= "alt=\"$stripcat\"></a><br>";
$showimages = "yes";
}
// COMMENT THIS ELSE OUT IF YOU WANT TO SHOW THE CATEGORY NAME UNDER THE IMAGE
else
$maincatlinkval .= "<a href=\"$maincatlink\" class=\"itemcolor\">$stripcat</a>";
$maincatarray[] = $maincatlinkval;
}

if ($showimages == "no" AND !$category)
{
// New Products
if ($New_Product_Link == "Yes")
{
$maincatarray[] = "<a href=\"$Catalog_Page?new=yes\" class=\"itemcolor\">New Items</a>";
++$maincatnum;
}
// Featured Products
if ($Featured_Product_Link == "Yes")
{
$maincatarray[] = "<a href=\"$Catalog_Page?featured=yes\" class=\"itemcolor\">Featured Items</a>";
++$maincatnum;
}
// Sale Link
if ($Sales_Product_Link == "Yes")
{
$maincatarray[] = "<a href=\"$Catalog_Page?sale=yes\" class=\"itemcolor\">Sale Items</a>";
++$maincatnum;
}
// All Products
if ($All_Product_Link == "Yes")
{
$maincatarray[] = "<a href=\"$Catalog_Page?all=yes\" class=\"itemcolor\">All Items</a>";
++$maincatnum;
}
}

// Create category table
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" ";
if ($Product_Line)
echo "class=\"linetable\" ";
echo "width=\"100%\">";
for ($count = 1; $count <= $maincatnum; ++$count)
{
$i = $count-1;
if ($Cat_Columns == 0)
{
echo "<tr>";
echo "<td align=\"center\" valign=\"top\" ";
if ($Product_Line)
echo "class=\"linecell\" ";
echo "width=\"100%\">";
echo "$maincatarray[$i]";
echo "</td>";
echo "</tr>";
}
else
{
if ($maincatnum < $Cat_Columns)
$colwidth = floor(100/$maincatnum);
else
$colwidth = floor(100/$Cat_Columns);
if (($count + $Cat_Columns-1) % $Cat_Columns == 0)
echo "<tr>";
echo "<td align=\"center\" valign=\"top\" ";
if ($Product_Line)
echo "class=\"linecell\" ";
echo "width=\"$colwidth%\">";
echo "$maincatarray[$i]";
echo "</td>";
if ($count % $Cat_Columns == 0)
echo "</tr>";
}
}

// Finish off table if needed
if ($Cat_Columns > 0)
{
$remaining = $Cat_Columns - (($count-1) % $Cat_Columns);
if ($remaining > 0 AND $remaining < $Cat_Columns)
{
if ($count-1 < $Cat_Columns)
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