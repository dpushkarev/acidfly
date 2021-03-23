<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

if ($adminsrc == "multi" AND $limitofitems > 10)
$limitofitems = 10;
else if (!$limitofitems)
$limitofitems = 20;
if (!$orderby)
$orderby = "Item";

if ($Submit == "Yes, Delete Item")
{
$delitemquery = "DELETE FROM " .$DB_Prefix ."_items WHERE ID = '$itemid'";
$delitemresult = mysql_query($delitemquery, $dblink) or die("Unable to delete this item. Please try again later.");
$deloptquery = "DELETE FROM " .$DB_Prefix ."_options WHERE ItemID = '$itemid'";
$deloptresult = mysql_query($deloptquery, $dblink) or die("Unable to delete this item. Please try again later.");
$delinvquery = "DELETE FROM " .$DB_Prefix ."_inventory WHERE ProductID = '$itemid'";
$delinvresult = mysql_query($delinvquery, $dblink) or die("Unable to delete this item. Please try again later.");
$delrelquery = "DELETE FROM " .$DB_Prefix ."_related WHERE ProductID = '$itemid' OR RelatedID = '$itemid'";
$delrelresult = mysql_query($delrelquery, $dblink) or die("Unable to delete this item. Please try again later.");
$delregquery = "DELETE FROM " .$DB_Prefix ."_reglist WHERE ProductID = '$itemid'";
$delregresult = mysql_query($delregquery, $dblink) or die("Unable to delete this item. Please try again later.");
}

if ($action == "continue")
{
// Delete old related and add new ones
$delrelquery = "DELETE FROM " .$DB_Prefix ."_related WHERE ProductID='$itemid'";
$delrelresult = mysql_query($delrelquery, $dblink) or die("Unable to edit your options. Please try again later.");
for ($count = 1; $count <= 5; ++$count)
{
// If related items are catalog numbers, find IDs
if ($relitem[$count] != "")
{
$idquery = "SELECT ID FROM " .$DB_Prefix ."_items WHERE Catalog='$relitem[$count]'";
$idresult = mysql_query($idquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($idresult) == 1)
{
$idrow = mysql_fetch_row($idresult);
$item[$count] = $idrow[0];
}
}
if ($item[$count] != 0)
{
$addrelquery = "INSERT INTO " .$DB_Prefix ."_related (ProductID, RelatedID) VALUES ('$itemid', '$item[$count]')";
$addrelresult = mysql_query($addrelquery, $dblink) or die("Unable to edit your options. Please try again later.");
}
}

// Delete all inventory records in preparation of entering new ones
$delattquery = "DELETE FROM " .$DB_Prefix ."_inventory WHERE ProductID='$itemid'";
$delattresult = mysql_query($delattquery, $dblink) or die("Unable to delete. Please try again later.");

// If no option inventories
if ($optcount > 0)
{
$iteminventory = "";
for ($i = 0; $i < $optcount; ++$i)
{
if ($inventory[$i] >= 0 AND $inventory[$i] != "")
{
$iteminventory = $iteminventory + $inventory[$i];
$addinvquery = "INSERT INTO " .$DB_Prefix ."_inventory (ProductID, Attribute, Quantity) ";
$addinvquery .= "VALUES ('$itemid', '$attribute[$i]', '$inventory[$i]')";
$addinvresult = mysql_query($addinvquery, $dblink) or die("Unable to edit your options. Please try again later.");
}
}
}

// Update inventory for all records
$updattquery = "UPDATE " .$DB_Prefix ."_items SET ";
if ($iteminventory == "" AND $iteminventory != "0")
$updattquery .= "Inventory=NULL ";
else
$updattquery .= "Inventory='$iteminventory' ";
$updattquery .= "WHERE ID='$itemid'";
$updattresult = mysql_query($updattquery, $dblink) or die("Unable to update item inventory. Please try again later.");
}
// END continue

// UPDATE multi mode
if ($itemlistmode == "update")
{
for ($t = 1; $t <= $num_of_items - 1; ++$t)
{
$idval = "${"id$t"}";
$itemval = "${"item$t"}";
$itemval = addslash_mq($itemval);
$itemval = str_replace(":", "", $itemval);
$itemval = str_replace("~", "", $itemval);
$itemval = str_replace("#", "", $itemval);
$itemval = str_replace("{b}", "", $itemval);
$itemval = str_replace("{/b}", "", $itemval);
$itemval = str_replace("{br}", "", $itemval);
$catalogval = "${"catalog$t"}";
$catalogval = addslash_mq($catalogval);
$catalogval = str_replace(":", "", $catalogval);
$catalogval = str_replace("~", "", $catalogval);
$catalogval = str_replace("#", "", $catalogval);
$catalogval = str_replace("{b}", "", $catalogval);
$catalogval = str_replace("{/b}", "", $catalogval);
$catalogval = str_replace("{br}", "", $catalogval);
$descriptionval = "${"description$t"}";
$descriptionval = addslash_mq($descriptionval);
$activeval = "${"active$t"}";
$oosval = "${"oos$t"}";
$featureval = "${"feature$t"}";
$wsonly = "${"wsonly$t"}";
$regpriceval = "${"regprice$t"}";
$salepriceval = "${"saleprice$t"}";
$wsdiscval = "${"wsdisc$t"}";
$editdateval = "${"editdate$t"}";
if ($editdateval != 0)
{
$splitdate = explode("/",$editdateval);
$editdateval = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
else
$editdateval = date("Y-m-d", mktime (0,0,0,date("m"),date("d"),date("Y")));
$smimageval = "${"smimage$t"}";
$lgimageval = "${"lgimage$t"}";
$categoryval = "${"category$t"}";

$updquery = "UPDATE " .$DB_Prefix ."_items SET Catalog='$catalogval', Item='$itemval', Description='$descriptionval', ";
$updquery .= "SmImage='$smimageval', LgImage='$lgimageval', Category1='$categoryval', ";
$updquery .= "RegPrice='$regpriceval', SalePrice='$salepriceval', OutOfStock='$oosval', ";
$updquery .= "DateEdited='$editdateval', Wholesale='$wsdiscval', Featured='$featureval', ";
$updquery .= "WSOnly='$wsonly', Active='$activeval' WHERE ID='$idval'";
$updresult = mysql_query($updquery, $dblink) or die("Unable to update. Please try again later.");
}
}
// END update multi mode

// UPDATE INVENTORY
else if ($itemlistmode == "inv")
{
// LOOP THROUGH ITEMS
for ($c=1; $c<=count($itemid); ++$c)
{
// OPTION INVENTORY
if ($optcount[$c] > 0)
{
// DELETE ALL OPTION INVENTORY
$delattquery = "DELETE FROM " .$DB_Prefix ."_inventory WHERE ProductID='$itemid[$c]'";
$delattresult = mysql_query($delattquery, $dblink);

// TALLY TOTAL INV
$generalinv = "";
$thisinv = ${"inv_" .$c};
$attribute = ${"attname_" .$c};
for ($x = 0; $x < $optcount[$c]; ++$x)
{
if ($thisinv[$x] >= 0 AND $thisinv[$x] != "")
{
$generalinv = $generalinv + $thisinv[$x];
$addinvquery = "INSERT INTO " .$DB_Prefix ."_inventory (ProductID, Attribute, Quantity) ";
$addinvquery .= "VALUES ('$itemid[$c]', '$attribute[$x]', '$thisinv[$x]')";
$addinvresult = mysql_query($addinvquery, $dblink) or die("Unable to edit your options. Please try again later.");
}
}

}

// GENERAL ITEM INVENTORY
else
$generalinv = $inv[$c];

// UPDATE MAIN ITEM INVENTORY
$updattquery = "UPDATE " .$DB_Prefix ."_items SET ";
if ($generalinv == "" AND $generalinv != "0")
$updattquery .= "Inventory=NULL ";
else
$updattquery .= "Inventory='$generalinv' ";
$updattquery .= "WHERE ID='$itemid[$c]'";
$updattresult = mysql_query($updattquery, $dblink);
}
// END PRODUCT LOOP
}
// END UPDATE INVENTORY

// START SEARCHING ITEMS
$itemquery = "SELECT * FROM " .$DB_Prefix ."_items WHERE ID<>''";
if ($catsearch)
{
$catsearchquery = "SELECT Category FROM " .$DB_Prefix ."_categories WHERE ID='$catsearch'";
$catsearchresult = mysql_query($catsearchquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($catsearchresult) == 1)
{
$catsearchrow = mysql_fetch_row($catsearchresult);
$catsearchname = $catsearchrow[0];
}
$itemquery .= " AND (Category1='$catsearch' OR Category2='$catsearch'";
$itemquery .= " OR Category3='$catsearch' OR Category4='$catsearch'";
$itemquery .= " OR Category5='$catsearch'";
$subquery = "SELECT Category, ID FROM " .$DB_Prefix ."_categories WHERE Parent='$catsearch'";
$subresult = mysql_query($subquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($subresult) > 0)
{
while ($subrow = mysql_fetch_row($subresult))
{
$itemquery .= " OR Category1='$subrow[1]' OR Category2='$subrow[1]' OR Category3='$subrow[1]'";
$itemquery .= "OR Category4='$subrow[1]' OR Category5='$subrow[1]'";
$endquery = "SELECT Category, ID FROM " .$DB_Prefix ."_categories WHERE Parent='$subrow[1]'";
$endresult = mysql_query($endquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($endresult) > 0)
{
while ($endrow = mysql_fetch_row($endresult))
{
$itemquery .= " OR Category1='$endrow[1]' OR Category2='$endrow[1]' OR Category3='$endrow[1]'";
$itemquery .= "OR Category4='$endrow[1]' OR Category5='$endrow[1]'";
}
}
}
}
$itemquery .= ")";
}

if ($kwsearch != "")
{
$searchterm = explode(" ", trim($kwsearch));
$countsearch = count($searchterm);
$itemquery .= " AND (";
for ($scounter = 0; $scounter < $countsearch; ++$scounter)
{
$addlterm = addslash_mq($searchterm[$scounter]);
if ($scounter != 0)
$itemquery .= " AND ";
$itemquery .= "(Item LIKE '%$addlterm%' OR Keywords LIKE '%$addlterm%')";
}
$itemquery .= ")";
}
if ($limitview == "inventory")
$itemquery .= " AND Inventory >= 0";
if ($limitview == "outofstock")
$itemquery .= " AND (OutOfStock = 'Yes' OR Inventory = 0)";
if ($limitview == "sale")
$itemquery .= " AND SalePrice > 0";
if ($limitview == "inactive")
$itemquery .= " AND Active = 'No'";
if ($limitview == "soldout")
$itemquery .= " AND OutOfStock = 'No' AND Inventory = 0";
if ($limitview == "featured")
$itemquery .= " AND Featured = 'Yes'";

// Set page numbers
if (!$page)
{
$page = 1;
$offset = 0;
}
else
$offset = (($limitofitems * $page)-$limitofitems);

$totalitemquery = $itemquery;

// Total number of records in this particular page
$itemquery .= " ORDER BY $orderby LIMIT $offset, $limitofitems";
$itemresult = mysql_query($itemquery, $dblink) or die ("Unable to view items.");
$itemnum = mysql_num_rows($itemresult);

// Total records altogether
$totalitemresult = mysql_query($totalitemquery, $dblink) or die ("Unable to view items.");
$totalitemnum = mysql_num_rows($totalitemresult);

// Display Page Numbers on page
$offset = ($page-1)*$limitofitems;
if ($totalitemnum % $limitofitems == 0)
$page_count = ($totalitemnum-($totalitemnum%$limitofitems)) / $limitofitems;
else
$page_count = ($totalitemnum-($totalitemnum%$limitofitems)) / $limitofitems + 1;

if ($catsearch)
$linkinfo .= "&catsearch=$catsearch";
if ($kwsearch)
{
$kwlink = stripslashes($kwsearch);
$kwlink = urlencode($kwlink);
$linkinfo .= "&kwsearch=$kwlink";
}
$linkinfo .= "&adminsrc=$adminsrc";
$linkinfo .= "&limitview=$limitview";
$linkinfo .= "&limitofitems=$limitofitems";
$linkinfo .= "&orderby=$orderby";

$previous = $page - 1;
$next = $page + 1;

if ($page_count != 1)
{
$i = 1;
$n = 3;
if ($page < $n+1)
$pagestart = 1;
else if ($page > ($page_count-$n))
$pagestart = $page_count-$n*2;
else
$pagestart = $page-$n;

while ($i <= $page_count)
{
if (($i >= $pagestart) AND ($i <= $n*2+$pagestart))
{
if ($i != $page)
$output_string .= "<a href=\"itemlist.php?page=$i$linkinfo\">$i</a>";
else
$output_string .= "<b>$i</b>";
if ($i != $page_count)
$output_string .= " | ";
}
$i++;
}
}

// Set Page Numbers
if ($page_count != 1)
{
echo "<p align=\"center\">";
if ($page > 1)
echo "<a href=\"itemlist.php?page=1$linkinfo\"><<</a> | ";
if ($page-$n > 1)
{
if ($page-$n*2+1 > 1)
$prevpage = $page-$n*2+1;
else
$prevpage = 1;
echo "<a href=\"itemlist.php?page=$prevpage$linkinfo\"><</a> | ";
}
echo "$output_string";
if ($page+$n < $page_count)
{
if ($page+$n*2+1 < $page_count)
$nextpage = $page+$n*2+1;
else
$nextpage = $page_count;
echo " <a href=\"itemlist.php?page=$nextpage$linkinfo\">></a>";
}
if ($page < $page_count)
echo " | <a href=\"itemlist.php?page=$page_count$linkinfo\">>></a>";
echo "</p>";
}
// End Page Numbers

if ($itemnum == 0)
echo "<p align=\"center\">Sorry, but there were no items with the criteria you specified. Please try again.</p>";
else
{
echo "<p align=\"center\" class=\"fieldname\">";
if ($catsearch AND $kwsearch)
echo "Category: $catsearchname Keyword(s): $kwsearch";
else if ($catsearch)
echo "Category: $catsearchname";
else if ($kwsearch)
echo "Keyword(s): $kwsearch";
else if (!$catsearch AND !$kwsearch)
echo "Product List";
if ($limitview == "inventory")
echo " (Inventoried Items)";
else if ($limitview == "outofstock")
echo " (Out of Stock)";
else if ($limitview == "inactive")
echo " (Inactive)";
else if ($limitview == "sale")
echo " (Sale Items)";
else if ($limitview == "soldout")
echo " (Sold Out)";
else if ($limitview == "featured")
echo " (Featured Items)";
echo "</b></p>";
?>
<form method="POST" action="items.php">
<p align="center">
Add a New Item: 
<?php
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
?>
<input type="submit" class="button" value="Add" name="Submit"></p>
</form>
<?php
// INVENTORY ONLY
if ($adminsrc == "inventory")
{
?>
<form action="itemlist.php" method="POST">
<div align="center">
<table class="generaltable" cellSpacing="0" cellPadding="0" border="0">
<tr>
<td colspan="2" class="fieldname">Product</td>
<td class="fieldname">Status</td>
<td class="fieldname">Price</td>
<td class="fieldname">Quantity</td>
</tr>
<?php
for ($i = 1; $itemrow = mysql_fetch_array($itemresult); ++$i)
{
$stripitem = stripslashes($itemrow[Item]);
if (!empty($itemrow[Catalog]))
$stripitem = "#" .$itemrow[Catalog] .". " .$stripitem;
$units = (float)$itemrow[Units];
if ($itemrow[SalePrice] > 0)
$itemprice = "<strike>" .$itemrow[RegPrice] ."</strike> " .$itemrow[SalePrice];
else
$itemprice = $itemrow[RegPrice];
if ($itemrow[Active] == "No")
$status = "Inactive";
else
$status = "Active";
if ($itemrow[OutOfStock] == "Yes")
$status .= " (OOS)";

// CHECK INVENTORY FOR EACH ITEM
$getinvquery = "SELECT * FROM " .$DB_Prefix ."_options WHERE ItemID = '$itemrow[ID]' AND OptionNum = '1'";
$getinvquery .= " AND (Type = 'Drop Down' OR Type = 'Radio Button') ORDER BY ID";
$getinvresult = mysql_query($getinvquery, $dblink) or die("Could not select options.");

// SINGLE ITEM INVENTORY
if (mysql_num_rows($getinvresult) != 1)
{
echo "<tr";
if ($i % 2 == 0)
echo " class=\"cellaccent\"";
echo ">";
echo "<td colspan=\"2\">$stripitem</td>";
echo "<td>$status</td>";
echo "<td>" .number_format($itemprice, 2, '.', '') ."</td>";
echo "<td>";
echo "<input type=\"text\" size=\"3\" name=\"inv[$i]\" value=\"$itemrow[Inventory]\">";
echo "<input type=\"hidden\" name=\"itemid[$i]\" value=\"$itemrow[ID]\">";
echo "</td>";
echo "</tr>";
}
// OPTION INVENTORY
else
{
$getinvrow = mysql_fetch_array($getinvresult);
$optarray = explode("~", $getinvrow[Attributes]);
$optcount = count($optarray);
for ($v = 0; $v < $optcount; ++$v)
{
$attarray = explode(":", $optarray[$v]);
$option = $attarray[0];
// Get prices
if ($attarray[1])
$optprice = $itemprice + $attarray[1];
else
$optprice = $itemprice;
// Check for inv quantities
$invquery = "SELECT Quantity FROM " .$DB_Prefix ."_inventory WHERE ProductID = '$itemrow[ID]' AND Attribute = '$option'";
$invresult = mysql_query($invquery, $dblink) or die("Could not select inv qtys.");
$invnum = mysql_num_rows($invresult);
if ($invnum == 1)
{
$invrow = mysql_fetch_row($invresult);
$inventory = $invrow[0];
}
else
$inventory = "";
if ($v == 0)
{
echo "<tr";
if ($i % 2 == 0)
echo " class=\"cellaccent\"";
echo ">";
echo "<td colspan=\"2\">$stripitem</td>";
echo "<td colspan=\"3\">";
echo $status;
echo "<input type=\"hidden\" name=\"itemid[$i]\" value=\"$itemrow[ID]\">";
echo "<input type=\"hidden\" name=\"optcount[$i]\" value=\"$optcount\">";
echo "</td>";
echo "</tr>";
}
echo "<tr";
if ($i % 2 == 0)
echo " class=\"cellaccent\"";
echo ">";
echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
echo "<td>" .stripslashes($option) ."</td>";
echo "<td>&nbsp;<input type=\"hidden\" name=\"attname_" .$i ."[$v]\" value=\"" .stripslashes($option) ."\"></td>";
echo "<td>" .number_format($optprice, 2, '.', '') ."</td>";
echo "<td>";
echo "<input type=\"text\" size=\"3\" name=\"inv_" .$i ."[$v]\" value=\"$inventory\">";
echo "</td>";
echo "</tr>";
}
}
// END OPTION INVENTORY
}
?>
<tr>
<td align="center" colspan="5">
<input type="hidden" name="itemlistmode" value="inv">
<input type="hidden" name="adminsrc" value="inventory">
<input type="submit" name="submit" class="button" value="Update Inventory">
</td>
</tr>
</table>
</div>
</form>
<?php
}

// START MULTI
else if ($adminsrc == "multi")
{
?>
<form action="itemlist.php" method="POST" name="EditForm">
<div align="center">
<center>
<table class="generaltable" cellSpacing="0" cellPadding="0" border="0">
<?php
for ($i = 1; $itemrow = mysql_fetch_row($itemresult); ++$i)
{
$prodid = $itemrow[0];
$catnum = $itemrow[1];
$stripitem = stripslashes($itemrow[2]);
$stripitem = str_replace("\"", "&quot;", $stripitem);
$stripdescrip = stripslashes($itemrow[3]);
$stripdescrip = str_replace("\"", "&quot;", $stripdescrip);
$smallimage = $itemrow[4];
$largeimage = $itemrow[5];
$cat1 = $itemrow[6];
$cat2 = $itemrow[7];
$cat3 = $itemrow[8];
$units = (float)$itemrow[9];
$regularprice = $itemrow[10];
$saleprice = $itemrow[11];
$outofstock = $itemrow[12];
$active = $itemrow[13];
$wholesale = $itemrow[19];
$featured = $itemrow[20];
$wsonly = $itemrow[30];
if ($itemrow[21] != 0)
{
$dateedit = explode("-",$itemrow[21]);
$editdate = date("n/j/y", mktime(0,0,0,$dateedit[1],$dateedit[2],$dateedit[0]));
}
else
$editdate = date("n/j/y", mktime (0,0,0,date("m"),date("d"),date("Y")));
if ($i > 1)
echo "<tr><td colspan=\"8\"><hr></td></tr>";
?>

<tr>
<td vAlign="top" align="right">Item:</td>
<td vAlign="top" colSpan="3">
<?php
echo "<input type=\"hidden\" value=\"$prodid\" name=\"id$i\">";
echo "<input type=\"text\" name=\"item$i\" value=\"$stripitem\" size=\"28\">";
?>
</td>
<td vAlign="top" align="right">Catalog:</td>
<td vAlign="top">
<?php echo "<input type=\"text\" name=\"catalog$i\" value=\"$catnum\" size=\"8\">"; ?>
</td>
<td vAlign="top" align="right">Active:</td>
<td vAlign="top">
<?php
echo "<select name=\"active$i\" size=\"1\">";
if ($active == "Yes")
echo "<option selected value=\"Yes\">Yes&nbsp;</option><option value=\"No\">No&nbsp;</option>";
else
echo "<option value=\"Yes\">Yes&nbsp;</option><option selected value=\"No\">No&nbsp;</option>";
echo "</select>";
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right" rowSpan="2">Description:</td>
<td vAlign="top" colSpan="5" rowSpan="2">
<?php echo "<textarea rows=\"3\" name=\"description$i\" cols=\"45\">$stripdescrip</textarea>"; ?>
</td>
<td vAlign="top" align="right">OOS:</td>
<td vAlign="top">
<?php
echo "<select name=\"oos$i\" size=\"1\">";
if ($outofstock == "Yes")
echo "<option selected value=\"Yes\">Yes&nbsp;</option><option value=\"No\">No&nbsp;</option>";
else
echo "<option value=\"Yes\">Yes&nbsp;</option><option selected value=\"No\">No&nbsp;</option>";
echo "</select>";
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">Feature:</td>
<td vAlign="top">
<?php
echo "<select name=\"feature$i\" size=\"1\">";
if ($featured == "Yes")
echo "<option selected value=\"Yes\">Yes&nbsp;</option><option value=\"No\">No&nbsp;</option>";
else
echo "<option value=\"Yes\">Yes&nbsp;</option><option selected value=\"No\">No&nbsp;</option>";
echo "</select>";
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">Reg Price:</td>
<td vAlign="top">
<?php echo "<input type=\"text\" name=\"regprice$i\" value=\"$regularprice\" size=\"8\">"; ?>
</td>
<td vAlign="top" align="right">Sale:</td>
<td vAlign="top">
<?php echo "<input type=\"text\" name=\"saleprice$i\" value=\"$saleprice\" size=\"8\">"; ?>
</td>
<td vAlign="top" align="right">WS Disc:</td>
<td vAlign="top">
<?php echo "<input type=\"text\" name=\"wsdisc$i\" value=\"$wholesale\" size=\"5\">"; ?>
%
</td>
<td vAlign="top" align="right" nowrap>WS Only:</td>
<td vAlign="top">
<?php
echo "<select name=\"wsonly$i\" size=\"1\">";
if ($wsonly == "Yes")
echo "<option selected value=\"Yes\">Yes&nbsp;</option><option value=\"No\">No&nbsp;</option>";
else
echo "<option value=\"Yes\">Yes&nbsp;</option><option selected value=\"No\">No&nbsp;</option>";
echo "</select>";
?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
<?php
if ($adminpswd == md5(demo))
echo "Thumbnail:";
else
echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=smimage$i\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=smimage$i', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=200,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Thumbnail</a>:";
?>
</td>
<td vAlign="top" colSpan="3">
<?php echo "<input type=\"text\" name=\"smimage$i\" value=\"$smallimage\" size=\"28\">"; ?>
</td>
<td vAlign="top" align="right">
<?php
if ($adminpswd == md5(demo))
echo "Lg&nbsp;Image:";
else
echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=lgimage$i\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=lgimage$i', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=200,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Lg&nbsp;Image</a>:";
?>
</td>
<td vAlign="top" colSpan="4">
<?php echo "<input type=\"text\" name=\"lgimage$i\" value=\"$largeimage\" size=\"25\">"; ?>
</td>
</tr>
<tr>
<td vAlign="top" align="right">Category:</td>
<td vAlign="top" colSpan="3">
<?php
// Display category
$sclimit = 20;
echo "<select size=\"1\" name=\"category$i\">";
echo "<option ";
if ($itemrow[6] == 0)
echo "selected ";
echo "value=\"\">None</option>";
$getcatquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent='0' ORDER BY Category";
$getcatresult = mysql_query($getcatquery, $dblink) or die("Could not select categories");
for ($getcatcount = 1; $getcatrow = mysql_fetch_row($getcatresult); ++$getcatcount)
{
$smallgetcat = substr($getcatrow[1], 0, $sclimit); 
// Are there subcategories?
$subcatquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent = '$getcatrow[0]' ORDER BY Category";
$subcatresult = mysql_query($subcatquery, $dblink) or die ("Could not show categories. Try again later.");
$subcatnum = mysql_num_rows($subcatresult);
// No there are no subcats so display the parent only
if ($subcatnum == 0)
{
echo "<option ";
if ($itemrow[6] == $getcatrow[0])
echo "selected ";
echo "value=\"$getcatrow[0]\">$getcatrow[1]</option>";
}
// Yes there are subcats - go through loop and display them
else
{
while ($subcatrow = mysql_fetch_row($subcatresult))
{
$smallsubcat = substr($subcatrow[1], 0, $sclimit); 
// Are there end categories?
$endcatquery = "SELECT ID, Category FROM " .$DB_Prefix ."_categories WHERE Parent = '$subcatrow[0]' ORDER BY Category";
$endcatresult = mysql_query($endcatquery, $dblink) or die ("Could not show categories. Try again later.");
// No there are no endcats so display the parent and subcat only
if (mysql_num_rows($endcatresult) == 0)
{
echo "<option ";
if ($itemrow[6] == $subcatrow[0])
echo "selected ";
echo "value=\"$subcatrow[0]\">$smallgetcat > $subcatrow[1]</option>";
}
else
{
while ($endcatrow = mysql_fetch_row($endcatresult))
{
echo "<option ";
if ($itemrow[6] == $endcatrow[0])
echo "selected ";
echo "value=\"$endcatrow[0]\">$smallgetcat > $smallsubcat > $endcatrow[1]</option>";
}
}
}
}
}
echo "</select>";
?>
</td>
<td vAlign="top" align="right">Date:</td>
<td vAlign="top">
<?php echo "<input type=\"text\" name=\"editdate$i\" value=\"$editdate\" size=\"6\">"; ?>
</td>
<td vAlign="top" align="right" colSpan="2" class="smalltext">
<?php
echo "<a href=\"items.php?Submit=Edit&itemid=$itemrow[0]&catsearch=$catsearch&kwsearch=$kwsearch&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">Info</a>";
// Get total option number and display option button only if options are more than zero.
$optquery = "SELECT OptionNumber FROM " .$DB_Prefix ."_vars";
$optresult = mysql_query($optquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($optresult) == 1)
{
$optrow = mysql_fetch_row($optresult);
if ($optrow[0] > 0)
echo " | <a href=\"options.php?itemid=$itemrow[0]&catsearch=$catsearch&kwsearch=$kwsearch&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc&action=continue&itemmode=EditItem\">Options</a>";
}
echo " | <a href=\"related.php?itemid=$itemrow[0]&catsearch=$catsearch&kwsearch=$kwsearch&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">Inv</a>";
?>
</td>
</tr>

<?php
}
?>
</table>
</center>
</div>

<?php
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
echo "<input type=\"hidden\" value=\"update\" name=\"itemlistmode\">";
echo "<input type=\"hidden\" value=\"$i\" name=\"num_of_items\">";
?>
<p align="center">
<input type="submit" class="button" value="Update" name="Submit"></p>
</form>

<?php
}
// END MULTI

// START STANDARD
else
{
?>

<div align="center">
<center>
<table border=0 cellpadding=0 cellspacing=0 class="largetable">
<tr>
<td align="left" class="fieldname">Item</td>
<td align="left" class="fieldname">Price</td>
<td align="left" class="fieldname">Status</td>
<td align="left" class="fieldname">Qty</td>
<td align="left" colspan="4">&nbsp;</td>
</tr>
<?php
for ($i = 1; $itemrow = mysql_fetch_row($itemresult); ++$i)
{
$itemname = stripslashes($itemrow[2]);
$itemname = str_replace(" ", "&nbsp;", $itemname);
?>
<tr>

<td align="left" valign="top">
<?php
if ($itemrow[1])
echo "#&nbsp;$itemrow[1]. ";
echo substr($itemname, 0, 60);
?>
</td>

<td align="left" valign="top">
<?php
if ($itemrow[10] != 0)
{
if ($itemrow[11] != 0)
echo "<strike>$$itemrow[10]</strike> ($$itemrow[11])";
else
echo "$itemrow[10]";
}
else
echo "&nbsp;";
?>
</td>

<td align="left" valign="top">
<?php
if ($itemrow[13] == "No")
echo "<i>Inactive</i>";
else
echo "Active";
if ($itemrow[12] == "Yes")
echo " <i>(OOS)</i>";
?>
</td>

<td align="center" valign="top">
<?php
if ($itemrow[14] == NULL)
echo "&nbsp;";
else
echo "$itemrow[14]";
?>
</td>

<td align="left" valign="top">
<form method="POST" action="items.php">
<?php
echo "<input type=\"hidden\" value=\"$itemrow[0]\" name=\"itemid\">";
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
?>
<input type="submit" class="button" value="Edit" name="Submit"></form>
</td>

<td align="left" valign="top">
<?php
// Get total option number and display option button only if options are more than zero.
$optquery = "SELECT OptionNumber FROM " .$DB_Prefix ."_vars";
$optresult = mysql_query($optquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($optresult) == 1)
{
$optrow = mysql_fetch_row($optresult);
if ($optrow[0] > 0)
{
?>

<form method="POST" action="options.php">
<?php
echo "<input type=\"hidden\" value=\"$itemrow[0]\" name=\"itemid\">";
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
echo "<input type=\"hidden\" value=\"continue\" name=\"action\">";
echo "<input type=\"hidden\" value=\"EditItem\" name=\"itemmode\">";
?>
<input type="submit" class="button" value="Options" name="Submit"></form>

<?php
}
}
?>
</td>

<td align="left" valign="top">
<form method="POST" action="related.php">
<?php
echo "<input type=\"hidden\" value=\"$itemrow[0]\" name=\"itemid\">";
echo "<input type=\"hidden\" value=\"$itemname\" name=\"item\">";
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
?>
<input type="submit" class="button" value="Inventory" name="Submit"></form>
</td>

<td align="left" valign="top">
<form method="POST" action="items.php">
<?php
echo "<input type=\"hidden\" value=\"$itemrow[0]\" name=\"itemid\">";
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
?>
<input type="submit" class="button" value="Delete" name="Submit"></form>
</td>

</tr>
<?php
}
?>
</table>
</center>
</div>

<?php
}
// END STANDARD
?>

<?php
}
// Set Page Numbers
if ($page_count != 1)
{
echo "<p align=\"center\">";
if ($page > 1)
echo "<a href=\"itemlist.php?page=1$linkinfo\"><<</a> | ";
if ($page-$n > 1)
{
if ($page-$n*2+1 > 1)
$prevpage = $page-$n*2+1;
else
$prevpage = 1;
echo "<a href=\"itemlist.php?page=$prevpage$linkinfo\"><</a> | ";
}
echo "$output_string";
if ($page+$n < $page_count)
{
if ($page+$n*2+1 < $page_count)
$nextpage = $page+$n*2+1;
else
$nextpage = $page_count;
echo " <a href=\"itemlist.php?page=$nextpage$linkinfo\">></a>";
}
if ($page < $page_count)
echo " | <a href=\"itemlist.php?page=$page_count$linkinfo\">>></a>";
echo "</p>";
}
// End Page Numbers

if (empty($adminsrc) OR $adminsrc != "inventory")
{
?>
<form method="POST" action="items.php">
<p align="center">
Add a New Item: 
<?php
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
?>
<input type="submit" class="button" value="Add" name="Submit"></p>
</form>
<?php
}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>