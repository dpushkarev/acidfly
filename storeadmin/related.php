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

// Sets Option Number
$varquery = "SELECT OptionNumber, ShowItemLimit FROM " .$DB_Prefix ."_vars";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select your system variables. Try again later.");
$varrow = mysql_fetch_row($varresult);
$Option_Number=$varrow[0];
$Item_Select=$varrow[1];
if ($action == "continue")
{
for ($count = 1; $count <= $Option_Number; ++$count)
{
$nameval = "${"name$count"}";
$attributesval = "${"attributes$count"}";
$typeval = "${"type$count"}";
$activeval = "${"active$count"}";
if ($nameval)
{
// Strip out the quotes
$addnameval = str_replace('"', "”", $nameval);
$addnameval = str_replace("'", "’", $addnameval);
$addnameval = str_replace(":", "", $addnameval);
$addnameval = str_replace("~", "", $addnameval);
$addnameval = str_replace("#", "", $addnameval);
$addnameval = str_replace("{b}", "", $addnameval);
$addnameval = str_replace("{/b}", "", $addnameval);
$addnameval = str_replace("{br}", "", $addnameval);
$addnameval = str_replace(" - ", "-", $addnameval);
$addattributesval = str_replace('"', "”", stripslashes($attributesval));
$addattributesval = str_replace("'", "’", stripslashes($addattributesval));
$addattributesval = str_replace(" - ", "-", stripslashes($addattributesval));
$addattributesval = str_replace("#", "", $addattributesval);
$addattributesval = str_replace("{b}", "", $addattributesval);
$addattributesval = str_replace("{/b}", "", $addattributesval);
$addattributesval = str_replace("{br}", "", $addattributesval);
$getoptquery = "SELECT ID FROM " .$DB_Prefix ."_options WHERE ItemID='$itemid' AND OptionNum = '$count'";
$getoptresult = mysql_query($getoptquery, $dblink) or die ("Could not update options. Try again later.");
if (mysql_num_rows($getoptresult) == 0)
{
$optquery = "INSERT INTO " .$DB_Prefix ."_options (ItemID, OptionNum, Name, Type, Attributes, Active) VALUES ('$itemid', '$count', '$addnameval', '$typeval', '$addattributesval', '$activeval')";
$optresult = mysql_query($optquery, $dblink) or die("Unable to edit your options. Please try again later.");
}
else
{
$optquery = "UPDATE " .$DB_Prefix ."_options SET Name='$addnameval',Type='$typeval',Attributes='$addattributesval', Active='$activeval' WHERE ItemID='$itemid' AND OptionNum = '$count'";
$optresult = mysql_query($optquery, $dblink) or die("Unable to edit your options. Please try again later.");
}
}
else
{
$optquery = "DELETE FROM " .$DB_Prefix ."_options WHERE ItemID='$itemid' AND OptionNum = '$count'";
$optresult = mysql_query($optquery, $dblink) or die("Unable to edit your options. Please try again later.");
}
}
}

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

$itemquery = "SELECT Item, Inventory FROM " .$DB_Prefix ."_items WHERE ID='$itemid'";
$itemresult = mysql_query($itemquery, $dblink) or die ("Could not view items. Try again later.");
$itemrow = mysql_fetch_row($itemresult);
$itemname = stripslashes($itemrow[0]);
$iteminventory = $itemrow[1];

// Display Product Name
echo "<p align=\"center\" class=\"fieldname\">";
if ($itemname)
echo "Editing: $itemname";
else
echo "Add New Product";
echo "</p>";

// Displays back to admin link
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
<i>If desired, add up to five related products and/or inventory quantities to this item.</i></p>

<form method="POST" action="<?php echo "$outputpage"; ?>">
<?php
echo "<input type=\"hidden\" value=\"$itemid\" name=\"itemid\">";
echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
?>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="left" colspan="2">
<i>Related Items</i>
</td>
</tr>
<?php
$getrelquery = "SELECT * FROM " .$DB_Prefix ."_related WHERE ProductID = $itemid ORDER BY ID";
$getrelresult = mysql_query($getrelquery, $dblink) or die("Could not select related items.");
$getrelnum = mysql_num_rows($getrelresult);
for ($getrelcount = 1; $getrelrow = mysql_fetch_row($getrelresult),$getrelcount <= 5; ++$getrelcount)
{
echo "<tr>";
echo "<td align=\"right\" class=\"fieldname\">";
echo "Item&nbsp;#$getrelcount";
echo "</td>";
echo "<td align=\"left\">";
$getitemquery = "SELECT ID, Catalog, Item FROM " .$DB_Prefix ."_items ORDER BY Item";
$getitemresult = mysql_query($getitemquery, $dblink) or die ("Could not show items. Try again later.");
$getitemnum = mysql_num_rows($getitemresult);
if ($getitemnum == 0)
echo "<i>No Items Listed.</i>";
else if ($Item_Select == "Yes")
{
echo "<select size=\"1\" name=\"item[$getrelcount]\">";
if (!$getrelrow[2])
echo "<option selected value=\"0\">None</option>";
else
echo "<option value=\"0\">None</option>";
for ($getitemcount = 1; $getitemrow = mysql_fetch_row($getitemresult); ++$getitemcount)
{
$display = stripslashes($getitemrow[2]);
if ($getitemrow[1])
$display .= " (# $getitemrow[1])";
if ($getrelrow[2] == $getitemrow[0])
echo "<option selected value=\"$getitemrow[0]\">$display</option>";
else
echo "<option value=\"$getitemrow[0]\">$display</option>";
}
echo "</select>";
}
else
{
$catid = "";
$idquery = "SELECT Catalog FROM " .$DB_Prefix ."_items WHERE ID='$getrelrow[2]'";
$idresult = mysql_query($idquery, $dblink) or die ("Could not show items. Try again later.");
if (mysql_num_rows($idresult) == 1)
{
$idrow = mysql_fetch_row($idresult);
$catid = str_replace('"', '&quot;', stripslashes($idrow[0]));
}
echo "<span class=\"accent\">(Enter Catalog #)</span> ";
echo "<input type=\"text\" size=\"12\" name=\"relitem[$getrelcount]\" value=\"$catid\">";
}
echo "</td>";
echo "</tr>";
}
?>
<tr>
<td valign="bottom" align="left" colspan="2">
&nbsp;<br>
Inventory Quantities&nbsp;&nbsp;&nbsp;
<div class="smalltext">(Leave blank to delete; 0 means none in stock)</div> 
</td>
</tr>
<tr>
<td valign="top" align="left">Qty</td>
<td valign="top" align="left">Item</td>
</tr>

<?php
// Display inventory quantities
$getinvquery = "SELECT * FROM " .$DB_Prefix ."_options WHERE ItemID = '$itemid' AND OptionNum = '1'";
$getinvquery .= " AND (Type = 'Drop Down' OR Type = 'Radio Button') ORDER BY ID";
$getinvresult = mysql_query($getinvquery, $dblink) or die("Could not select options.");
$getinvnum = mysql_num_rows($getinvresult);

// No inventory options available
if ($getinvnum != 1)
{
echo "<tr>";
echo "<td align=\"left\" width=\"10\">";
echo "<input type=\"text\" name=\"iteminventory\" value=\"$iteminventory\"  size=\"3\">";
echo "</td>";
echo "<td align=\"left\" width=\"100%\" class=\"fieldname\">";
echo "$itemname";
echo "</td>";
echo "</tr>";
}

// Inventory for options
else
{
$getinvrow = mysql_fetch_array($getinvresult);
$optarray = explode("~", $getinvrow[Attributes]);
$optcount = count($optarray);

for ($invcount = 0; $invcount < $optcount; ++$invcount)
{
$attarray = explode(":", $optarray[$invcount]);
$option = $attarray[0];
// Get prices
if ($attarray[1])
$stripoption = stripslashes($option) ." - add $" .$attarray[1];
else
$stripoption = stripslashes($option);

// Check for inv quantities
$invquery = "SELECT Quantity FROM " .$DB_Prefix ."_inventory WHERE ProductID = '$itemid' AND Attribute = '$option'";
$invresult = mysql_query($invquery, $dblink) or die("Could not select inv qtys.");
$invnum = mysql_num_rows($invresult);
if ($invnum == 1)
{
$invrow = mysql_fetch_row($invresult);
$invquantity = $invrow[0];
}
else
$invquantity = "";

echo "<tr>";
echo "<td align=\"left\" width=\"10\">";
echo "<input type=\"text\" name=\"inventory[$invcount]\" value=\"$invquantity\" size=\"3\">";
echo "<input type=\"hidden\" name=\"attribute[$invcount]\" value=\"$option\" size=\"3\">";
echo "</td>";
echo "<td align=\"left\" width=\"100%\" class=\"fieldname\">";
echo "$stripoption";
echo "</td>";
echo "</tr>";
}
}
?>

<tr>
<td valign="top" align="center" colspan="2">
<?php
echo "<input type=\"hidden\" value=\"$optcount\" name=\"optcount\">";
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
