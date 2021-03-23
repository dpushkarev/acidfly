<script language="php">
include("includes/open.php");
$pgextquery = "SELECT PageExt FROM " .$DB_Prefix ."_vars WHERE ID=1";
$pgextresult = mysql_query($pgextquery, $dblink) or die ("Unable to select your system variables. Try again later.");
$pgextrow = mysql_fetch_row($pgextresult);
$pageext = $pgextrow[0];

if ($action == "continue")
{
if ($item)
{
$item = trim($item);
$item = str_replace(":", "", $item);
$item = str_replace("~", "", $item);
$item = str_replace("#", "", $item);
$item = str_replace("{b}", "", $item);
$item = str_replace("{/b}", "", $item);
$item = str_replace("{br}", "", $item);
$stripitem = stripslashes($item);
$additem = addslash_mq($item);
$catalogval = str_replace(":", "", $catalog);
$catalogval = str_replace("~", "", $catalogval);
$catalogval = str_replace("#", "", $catalogval);
$catalogval = str_replace("{b}", "", $catalogval);
$catalogval = str_replace("{/b}", "", $catalogval);
$catalogval = str_replace("{br}", "", $catalogval);
$addcatalog = addslash_mq($catalogval);
$stripcatalog = stripslashes($catalogval);
$adddescription = addslash_mq($description);
$addkeywords = addslash_mq($keywords);
$addmetatitle = addslash_mq($metatitle);
if (!$editdate)
$editdate = date("Y-m-d", mktime (0,0,0,date("m"),date("d"),date("Y")));
else
{
$dateedit = explode("/",$editdate);
$editdate = date("Y-m-d", mktime(0,0,0,$dateedit[0],$dateedit[1],$dateedit[2]));
}
if (!$wsonly)
$wsonly = "No";

// Set discount prices
$discarray = array();
for ($i = 1; $i <= 5; ++$i)
{
$oldqty = $qtyval;
$qtyval = "${"qty$i"}";
$amtval = "${"amt$i"}";
$disctypeval = "${"disctype$i"}";
// If the quantity exists
if ($qtyval > 1 AND $amtval > 0 AND $oldqty < $qtyval)
{
array_push($discarray, "$qtyval,$amtval,$disctypeval");
}
}
if ($discarray)
$discountpr = implode("~", $discarray);

if ($itemmode == "AddItem")
{
$insitemquery = "INSERT INTO " .$DB_Prefix ."_items (Item, Description, SmImage, LgImage, Units, ";
$insitemquery .= "Catalog, RegPrice, SalePrice, OutOfStock, Active, Category1, Category2, ";
$insitemquery .= "Category3, Category4, Category5, Keywords, DiscountPr, Wholesale, MetaTitle, ";
$insitemquery .= "WSOnly, Featured, DateEdited, PopUpPg, SmImage2, LgImage2, SmImage3, LgImage3, ";
$insitemquery .= "TaxPercent, ItemCost) VALUES ('$additem', '$adddescription', '$smimage', '$lgimage', ";
$insitemquery .= "'$units', '$addcatalog', '$regprice', '$saleprice', '$outofstock', '$active', ";
$insitemquery .= "'$category1', '$category2', '$category3', '$category4', '$category5', ";
$insitemquery .= "'$addkeywords', '$discountpr', '$wholesale', '$addmetatitle', '$wsonly', ";
$insitemquery .= "'$featured', '$editdate', '$popuppg', '$smimage2', '$lgimage2', '$smimage3', ";
$insitemquery .= "'$lgimage3', '$taxpercent', '$itemcost')";
$insitemresult = mysql_query($insitemquery, $dblink) or die("Unable to add your item. Please try again later.");
$itemid = mysql_insert_id();
}
else
{
$upditemquery = "UPDATE " .$DB_Prefix ."_items SET Item='$additem', Description='$adddescription', ";
$upditemquery .= "SmImage='$smimage', LgImage='$lgimage', Units='$units', Catalog='$addcatalog', ";
$upditemquery .= "RegPrice='$regprice', SalePrice='$saleprice', OutOfStock='$outofstock', ";
$upditemquery .= "Active='$active', Category1='$category1', Category2='$category2', ";
$upditemquery .= "Category3='$category3', Category4='$category4', Category5='$category5', ";
$upditemquery .= "Keywords='$addkeywords', DiscountPr='$discountpr', Wholesale='$wholesale', ";
$upditemquery .= "Featured='$featured', DateEdited='$editdate', PopUpPg='$popuppg', ";
$upditemquery .= "SmImage2='$smimage2', LgImage2='$lgimage2', SmImage3='$smimage3', ";
$upditemquery .= "LgImage3='$lgimage3', WSOnly='$wsonly', MetaTitle='$addmetatitle', ";
$upditemquery .= "TaxPercent='$taxpercent', ItemCost='$itemcost' WHERE ID='$itemid'";
$upditemresult = mysql_query($upditemquery, $dblink) or die("Unable to edit your item. Please try again later.");
}

$itemname = str_replace("\"", "&quot;", $stripitem);
}

// if it comes from the item list
else if ($itemid)
{
$itemquery = "SELECT Item FROM " .$DB_Prefix ."_items WHERE ID='$itemid'";
$itemresult = mysql_query($itemquery, $dblink) or die ("Unable to select this item. Try again later.");
$itemrow = mysql_fetch_row($itemresult);
$itemname = str_replace("\"", "&quot;", stripslashes($itemrow[0]));
}
}

// Sets Option Number
$varquery = "SELECT OptionNumber, Currency FROM " .$DB_Prefix ."_vars";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select your system variables. Try again later.");
if (mysql_num_rows($varresult) == 1)
{
$varrow = mysql_fetch_row($varresult);
$Option_Number=$varrow[0];
$currency=$varrow[1];
}

// If no option settings, pass on to the next page.
if ($Option_Number == "0")
{
$gotopage = "related.php?mode=items&action=continue";
$gotopage .= "&itemid=$itemid&item=$itemname";
$gotopage .= "&catsearch=$catsearch";
$gotopage .= "&kwsearch=$kwsearch";
$gotopage .= "&limitview=$limitview";
$gotopage .= "&limitofitems=$limitofitems";
$gotopage .= "&orderby=$orderby";
$gotopage .= "&page=$page";
$gotopage .= "&item=$itemname";
$gotopage .= "&adminsrc=$adminsrc";
header ("location: $gotopage");
header("Connection: close");
}
</script>
<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
include("includes/header.htm");
include("includes/links.php");

// Sets output page
if ($catsearch != "")
{
$catsearch = str_replace('"', "&#34;", stripslashes($catsearch));
$catsearch = str_replace("'", "&#39;", stripslashes($catsearch));
}
if ($catsearch OR $kwsearch OR $limitview)
$outputpage = "itemlist.php";
else
$outputpage = "items.php";

if ($Set AND $itemid)
{
$query = "SELECT Item FROM " .$DB_Prefix ."_items WHERE ID = $itemid";
$result = mysql_query($query, $dblink) or die("Could not select options.");
if (mysql_num_rows($result) == 1)
{
$row = mysql_fetch_row($result);
$defitemname = stripslashes($row[0]);
}
}

// Display Product Name
echo "<p align=\"center\" class=\"fieldname\">";
if ($itemname)
echo "Editing: $itemname";
else if ($defitemname)
echo "Editing: $defitemname";
else
echo "Add New Product";
echo "</p>";

if ($catsearch OR $kwsearch OR $limitview)
{
if ($catsearch)
{
$catlink = stripslashes($catsearch);
$catlink = urlencode($catlink);
}
if ($kwsearch)
{
$kwlink = stripslashes($kwsearch);
$kwlink = urlencode($kwlink);
}
echo "<p align=\"center\">";
echo "<a href=\"itemlist.php?catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
echo "<i>(Back to Item List)</i></a></p>";
}
?>


<p align="center">
<i>If desired, add up to <?php echo "$Option_Number"; ?> colors, sizes, styles or other options.
</i></p>

<?php
$getdefquery = "SELECT DefaultProduct FROM " .$DB_Prefix ."_vars WHERE DefaultProduct <> 0";
$getdefresult = mysql_query($getdefquery, $dblink) or die ("Unable to select. Try again later.");
$getdefnum = mysql_num_rows($getdefresult);
if ($getdefnum == 1)
{
$getdefrow = mysql_fetch_row($getdefresult);
$getdefid = $getdefrow[0];
// Show default links
if ($itemmode == "AddItem")
{
if ($Set == "Default")
{
echo "<p align=\"center\">";
echo "<a href=\"options.php?Set=Remove&itemid=$itemid&itemmode=$itemmode&catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
echo "<i>Remove Default</i></a></p>";
}
else
{
echo "<p align=\"center\">";
echo "<a href=\"options.php?Set=Default&itemid=$itemid&itemmode=$itemmode&catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
echo "<i>Set Default</i></a></p>";
}
}
else
{
if ($Set == "Default")
{
echo "<p align=\"center\">";
echo "<a href=\"options.php?Set=Remove&itemid=$itemid&itemmode=$itemmode&catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
echo "<i>Remove Default (Back to Original Options)</i></a></p>";
}
else
{
echo "<p align=\"center\">";
echo "<a href=\"options.php?Set=Default&itemid=$itemid&itemmode=$itemmode&catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
echo "<i>Set Default (Will Override Existing Options)</i></a></p>";
}
}
}
?>

<form method="POST" name="Options" action="related.php">
<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="generaltable">
<?php
$getoptquery = "SELECT * FROM " .$DB_Prefix ."_options ";
if (IsSet($getdefid) AND $getdefid > 0 AND $Set == "Default")
$getoptquery .= "WHERE ItemID = $getdefid ";
else
$getoptquery .= "WHERE ItemID = $itemid ";
$getoptquery .= "ORDER BY OptionNum";
$getoptresult = mysql_query($getoptquery, $dblink) or die("Could not select options.");
$getoptnum = mysql_num_rows($getoptresult);
for ($getoptcount = 1; $getoptrow = mysql_fetch_row($getoptresult),$getoptcount <= $Option_Number; ++$getoptcount)
{
echo "<tr>
<td valign=\"top\" align=\"left\" colspan=\"4\">
Option #$getoptcount";
$stripoptname = stripslashes($getoptrow[3]);
$stripoptattributes = stripslashes($getoptrow[5]);
$type = $getoptrow[4];
echo "</td>";
echo "</tr>
<tr>
<td valign=\"top\" align=\"right\" class=\"fieldname\">Name:</td>
<td valign=\"top\" align=\"left\" colspan=\"3\" class=\"smalltext\">";
$stripoptname = str_replace("\"", "&quot;", $stripoptname);
echo "<input type=\"text\" name=\"name$getoptcount\" value=\"$stripoptname\" size=\"25\"> (eg. <b><i>Colors</i></b>, <b><i>Size</i></b>, <b><i>Style</i></b>...)</td>
</tr>
<tr>
<td valign=\"top\" align=\"right\" class=\"fieldname\">Type:</td>
<td valign=\"top\" align=\"left\">
<select size=\"1\" name=\"type$getoptcount\">";
echo "<option ";
if ($type == "Text Box" OR !$type)
echo "selected ";
echo "value=\"Text Box\">Text Box</option>";
echo "<option ";
if ($type == "Drop Down")
echo "selected ";
echo "value=\"Drop Down\">Drop Down</option>";
echo "<option ";
if ($type == "Radio Button")
echo "selected ";
echo "value=\"Radio Button\">Radio Button</option>";
echo "<option ";
if ($type == "Check Box")
echo "selected ";
echo "value=\"Check Box\">Check Box</option>";
echo "<option ";
if ($type == "Memo Field")
echo "selected ";
echo "value=\"Memo Field\">Memo Field</option>";
echo "</select>
</td>
<td valign=\"top\" align=\"right\" class=\"fieldname\">Active:</td>
<td valign=\"top\" align=\"left\">
<select size=\"1\" name=\"active$getoptcount\">";
if ($getoptrow[6] == "No")
echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
else
echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
echo "</select>
</td>
</tr>
<tr>
<td valign=\"top\" align=\"right\"><div class=\"fieldname\">Attributes:</div>
<a class=\"smalltext\" href=\"includes/attributes.php?opt=$getoptcount&id=$getoptrow[0]\" target=\"_blank\" onClick=\"PopUp=window.open('includes/attributes.php?opt=$getoptcount&id=$getoptrow[0]', 'NewWin', 'resizable=yes,scrollbars=yes,width=300,height=475,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Attribute<br>Wizard</a>
</td>
<td valign=\"top\" align=\"left\" colspan=\"3\">
<textarea rows=\"3\" name=\"attributes$getoptcount\" cols=\"40\">$stripoptattributes</textarea>
</tr>
<tr>";
}
?>

<tr>
<td valign="middle" align="center" colspan="4">
<?php
echo "<input type=\"hidden\" value=\"$itemid\" name=\"itemid\">";
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
echo "<input type=\"hidden\" value=\"$itemname\" name=\"item\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
?>
<input type="hidden" value="items" name="mode">
<input type="hidden" value="continue" name="action">
<input type="submit" class="button" value="Continue" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
if ($catsearch OR $kwsearch OR $limitview)
{
echo "<p align=\"center\">";
echo "<a href=\"itemlist.php?catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
echo "<i>(Back to Item List)</i></a></p>";
}
?>

<?php
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>