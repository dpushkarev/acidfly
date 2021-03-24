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

$today = date("n/j/y", mktime (0,0,0,date("m"),date("d"),date("Y")));
$todayphp = date("Y-m-d", mktime (0,0,0,date("m"),date("d"),date("Y")));

$linkinfo = "&startmonth=$startmonth";
$linkinfo .= "&startyear=$startyear";
$linkinfo .= "&endmonth=$endmonth";
$linkinfo .= "&endyear=$endyear";
$linkinfo .= "&kw=$kw";
$linkinfo .= "&status=$status";
$linkinfo .= "&orderfieldsby=$orderfieldsby";
$linkinfo .= "&limitofsales=$limitofsales";

// Get currency
$varquery = "SELECT Currency FROM " .$DB_Prefix ."_vars";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select your system variables. Try again later.");
if (mysqli_num_rows($varresult) == 1)
{
$varrow = mysqli_fetch_row($varresult);
$Currency="$varrow[0]";
}

// Update record
if ($Submit == "Update Sale" AND $itemname)
{
if (!$datesold)
$solddate = $todayphp;
else
{
$splitdate = explode("/","$datesold");
$solddate = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
if (!$quantity)
$quantity = 1;
$additem = addslash_mq($itemname);
if ($options)
$additem .= ": " .addslash_mq($options);
// Edit record
if ($saleid)
{
$updquery = "UPDATE " .$DB_Prefix ."_sales SET Quantity='$quantity', Item='$additem', Price='$price', Units='$units', DateSold='$solddate' WHERE ID='$saleid'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
// Add new record
else
{
$insquery = "INSERT INTO " .$DB_Prefix ."_sales (Quantity, Item, Price, Units, DateSold) VALUES ('$quantity', '$additem', '$price', '$units', '$solddate')";
$insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
}
}

// Delete record
if ($Submit == "Yes" AND $saleid)
{
$delquery = "DELETE FROM " .$DB_Prefix ."_sales WHERE ID='$saleid'";
$delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
}

// Show check delete form
if ($mode == "delsales" AND $saleid)
{
?>
<form method="POST" action="sales.php">
<?php
echo "<input type=\"hidden\" value=\"$startmonth\" name=\"startmonth\">";
echo "<input type=\"hidden\" value=\"$startyear\" name=\"startyear\">";
echo "<input type=\"hidden\" value=\"$endmonth\" name=\"endmonth\">";
echo "<input type=\"hidden\" value=\"$endyear\" name=\"endyear\">";
echo "<input type=\"hidden\" value=\"$kw\" name=\"kw\">";
echo "<input type=\"hidden\" value=\"$orderfieldsby\" name=\"orderfieldsby\">";
echo "<input type=\"hidden\" value=\"$limitofsales\" name=\"limitofsales\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
echo "<input type=\"hidden\" value=\"$saleid\" name=\"saleid\">";
?>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="largetable">
<tr>
<td align="center">Are you sure you want to delete this sale? It will be removed permanently.
</td>
</tr>
<tr>
<td align="center">
<input type="submit" class="button" value="Yes" name="Submit"> 
<input type="submit" class="button" value="No" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\"><a href=\"sales.php?page=$page$linkinfo\">Back to Sales</a></p>";
}

// Show edit form
else if (($mode == "updsales" AND $saleid) OR $mode == "Add New Sale")
{
if ($saleid)
{
$salesquery = "SELECT * FROM " .$DB_Prefix ."_sales WHERE ID='$saleid'";
$salesresult = mysqli_query($dblink, $salesquery) or die ("Unable to view sales.");
$salesrow = mysqli_fetch_array($salesresult);
if ($salesrow[DateSold] > 0)
{
$splitdate = explode("-","$salesrow[DateSold]");
$datesold = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
$item_name = stripslashes($salesrow[Item]);
$item_name = str_replace('"', "&quot;", $item_name);
$splititem = explode(": ", $item_name);
$itemname = $splititem[0];
$options = $splititem[1];
$quantity = $salesrow[Quantity];
}
if (!$datesold)
$datesold = $today;
if (!$quantity)
$quantity = 1;
?>
<form method="POST" action="sales.php">
<?php
echo "<input type=\"hidden\" value=\"$startmonth\" name=\"startmonth\">";
echo "<input type=\"hidden\" value=\"$startyear\" name=\"startyear\">";
echo "<input type=\"hidden\" value=\"$endmonth\" name=\"endmonth\">";
echo "<input type=\"hidden\" value=\"$endyear\" name=\"endyear\">";
echo "<input type=\"hidden\" value=\"$kw\" name=\"kw\">";
echo "<input type=\"hidden\" value=\"$orderfieldsby\" name=\"orderfieldsby\">";
echo "<input type=\"hidden\" value=\"$limitofsales\" name=\"limitofsales\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
if ($saleid AND $mode == "updsales")
echo "<input type=\"hidden\" value=\"$saleid\" name=\"saleid\">";
?>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="largetable">
<tr>
<td align="right" class="fieldname">Item:</td>
<td align="left" colspan="3">&nbsp;
<input type="text" name="itemname" value="<?php echo "$itemname"; ?>" size="40">
</td>
</tr>
<tr>
<td align="right" class="fieldname">Options:</td>
<td align="left" colspan="3">&nbsp;
<input type="text" name="options" value="<?php echo "$options"; ?>" size="40">
</td>
</tr>
<tr>
<td align="right" class="fieldname">Price:</td>
<td align="left">
<?php echo "$Currency"; ?>
<input type="text" name="price" value="<?php echo "$salesrow[Price]"; ?>" size="10">
</td>
<td align="right" class="fieldname">Quantity Sold:</td>
<td align="left">
<input type="text" name="quantity" value="<?php echo (float)$quantity; ?>" size="3">
</td>
</tr>
<tr>
<td align="right" class="fieldname">Date:</td>
<td align="left">&nbsp;
<input type="text" name="datesold" value="<?php echo "$datesold"; ?>" size="10">
</td>
<td align="right" class="fieldname">Shipping Units:</td>
<td align="left">
<input type="text" name="units" value="<?php echo (float)$salesrow[Units]; ?>" size="3">
</td>
</tr>
<tr>
<td colspan="4" align="center">
<input type="submit" class="button" value="Update Sale" name="Submit">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
echo "<p align=\"center\"><a href=\"sales.php?page=$page$linkinfo\">Back to Sales</a></p>";
}

// View Bestsellers
else if ($mode == "bestsellers")
{
if (!$numsales)
$numsales = 50;
$salesquery = "SELECT Item, SUM(Quantity)AS SumQty, MAX(Price) AS MaxPrice, MAX(DateSold) AS MaxDate FROM " .$DB_Prefix ."_sales GROUP BY Item";
$salesquery .= " ORDER BY SumQty DESC, Item DESC LIMIT $numsales";
$salesresult = mysqli_query($dblink, $salesquery) or die ("Unable to view sales.");
?>

<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname" colspan="4">Bestseller List</td>
</tr>
<tr>
<td align="center" class="accent">Total Sold</td>
<td align="center" class="accent">Item Name</td>
<td align="center" class="accent">Highest Price</td>
<td align="center" class="accent">Last Date Sold</td>
</tr>
<?php
while ($salesrow = mysqli_fetch_array($salesresult))
{
$maxdate = $salesrow[MaxDate];

echo "<tr>";
echo "<td align=\"center\">" .(float)$salesrow[SumQty] ."</td>";
echo "<td>" .stripslashes($salesrow[Item]) ."</td>";
echo "<td align=\"center\">$Currency" .number_format($salesrow[MaxPrice], 2) ."</td>";
echo "<td align=\"center\">$maxdate</td>";
echo "</tr>";
}
?>
</table>
</center>
</div>
<p align="center"><a href="sales.php">Back to Sales</a></p>
<?php
}

else if ($startmonth AND $startyear AND $endmonth AND $endyear)
{
if (!$limitofsales)
$limitofsales = 20;
$startdate = $startyear ."-" .$startmonth ."-01";
$endday = date("t", mktime(0,0,0,$endmonth,"01",$endyear));
$enddate = $endyear ."-" .$endmonth ."-" .$endday;
$salesquery = "SELECT * FROM " .$DB_Prefix ."_sales WHERE DateSold >= '$startdate' AND DateSold <= '$enddate'";
// KEYWORD SEARCH
if ($kw)
{
$searchterm = explode(" ", trim($kw));
$salesquery .= " AND (";
for ($i = 0; $i < count($searchterm); ++$i)
{
$addlterm = addslash_mq($searchterm[$i]);
if ($i > 0)
$salesquery .= " OR ";
$salesquery .= "" .$DB_Prefix ."_sales.OrderNumber LIKE '%$addlterm%' OR " .$DB_Prefix ."_sales.Item LIKE '%$addlterm%'";
}
$salesquery .= ")";
}
// STATUS SELECTION
if ($status)
{
$status_set = str_replace("_", " ", $status);
$status_set = ucwords($status_set);
$salesquery .= " AND " .$DB_Prefix ."_orders.Status = '$status_set'";
}

if ($orderfieldsby == "date_")
$salesquery .= " ORDER BY DateSold DESC";
else if ($orderfieldsby == "date")
$salesquery .= " ORDER BY DateSold";
else if ($orderfieldsby == "cost_")
$salesquery .= " ORDER BY Price DESC";
else if ($orderfieldsby == "cost")
$salesquery .= " ORDER BY Price";
else if ($orderfieldsby == "itemcust_")
$salesquery .= " ORDER BY Item DESC";
else if ($orderfieldsby == "itemcust")
$salesquery .= " ORDER BY Item";
else if ($orderfieldsby == "qty_")
$salesquery .= " ORDER BY Quantity DESC";
else if ($orderfieldsby == "qty")
$salesquery .= " ORDER BY Quantity";
// Set page numbers
if (!$page)
{
$page = 1;
$offset = 0;
}
else
$offset = (($limitofsales * $page)-$limitofsales);

$totalsalesquery = $salesquery;

// Total number of records in this particular page
$salesquery .= " LIMIT $offset, $limitofsales";
$salesresult = mysqli_query($dblink, $salesquery) or die ("Unable to view sales.");
$salesnum = mysqli_num_rows($salesresult);

// Total records altogether
$totalsalesresult = mysqli_query($dblink, $totalsalesquery) or die ("Unable to view sales.");
$totalsalesnum = mysqli_num_rows($totalsalesresult);

// Display Page Numbers on page
$offset = ($page-1)*$limitofsales;
if ($totalsalesnum % $limitofsales == 0)
$page_count = ($totalsalesnum-($totalsalesnum%$limitofsales)) / $limitofsales;
else
$page_count = ($totalsalesnum-($totalsalesnum%$limitofsales)) / $limitofsales + 1;

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
$output_string .= "<a href=\"sales.php?page=$i$linkinfo\">$i</a>";
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
echo "<a href=\"sales.php?page=1$linkinfo\"><<</a> | ";
if ($page-$n > 1)
{
if ($page-$n*2+1 > 1)
$prevpage = $page-$n*2+1;
else
$prevpage = 1;
echo "<a href=\"sales.php?page=$prevpage$linkinfo\"><</a> | ";
}
echo "$output_string";
if ($page+$n < $page_count)
{
if ($page+$n*2+1 < $page_count)
$nextpage = $page+$n*2+1;
else
$nextpage = $page_count;
echo " <a href=\"sales.php?page=$nextpage$linkinfo\">></a>";
}
if ($page < $page_count)
echo " | <a href=\"sales.php?page=$page_count$linkinfo\">>></a>";
echo "</p>";
}
// End Page Numbers

if ($salesnum == 0)
{
?>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="center" class="fieldname">No sales found</td>
</tr>
<tr>
<td align="center">
No sales were found with the criteria you specified. Please <a href="sales.php">try again</a>.
</td>
</tr>
</table>
</center>
</div>
<?php
}
else
{
$stmonth = date("F", mktime(0,0,0,$startmonth,"01",$startyear));
$enmonth = date("F", mktime(0,0,0,$endmonth,$endday,$endyear));
echo "<p align=\"center\" class=\"fieldname\">";
echo "Sales Between $stmonth $startyear and $enmonth $endyear</p>";
?>

<form action="sales.php" method="POST">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td align="left" class="fieldname">Qty</td>
<td align="left" class="fieldname">Item</td>
<td align="left" class="fieldname">Price</td>
<td align="left" class="fieldname">Units</td>
<td align="left" class="fieldname">Date</td>
<td align="center" class="fieldname">Action</td>
</tr>
<?php
for ($i = 1; $salesrow = mysqli_fetch_array($salesresult); ++$i)
{
$splitdate = explode("-","$salesrow[DateSold]");
$datesold = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
$itemname = stripslashes($salesrow[Item]);
?>
<tr>
<td align="left" valign="top">
<?php echo (float)$salesrow[Quantity]; ?>
</td>
<td align="left" valign="top">
<?php echo "$itemname"; ?>
</td>
<td align="left" valign="top">
<?php echo "$Currency$salesrow[Price]"; ?>
</td>
<td align="left" valign="top">
<?php echo (float)$salesrow[Units]; ?>
</td>
<td align="left" valign="top">
<?php echo "$datesold"; ?>
</td>
<td align="center" valign="top" nowrap>
<?php
if ($salesrow[OrderNumber] AND $salesrow[OrderNumber] != "N/A")
echo "<a href=\"orders.php?mode=updord&ordid=$salesrow[OrderNumber]\">Order #" ."$salesrow[OrderNumber]</a>";
else
{
echo "<a href=\"sales.php?mode=updsales&saleid=$salesrow[ID]&page=$page$linkinfo\">Edit Sale</a> | ";
echo "<a href=\"sales.php?mode=delsales&saleid=$salesrow[ID]&page=$page$linkinfo\">Delete</a>";
}
?>
</td>
</tr>
<?php
}
?>
<tr>
<td colspan="7" align="center">
<?php
echo "<input type=\"hidden\" value=\"$startmonth\" name=\"startmonth\">";
echo "<input type=\"hidden\" value=\"$startyear\" name=\"startyear\">";
echo "<input type=\"hidden\" value=\"$endmonth\" name=\"endmonth\">";
echo "<input type=\"hidden\" value=\"$endyear\" name=\"endyear\">";
echo "<input type=\"hidden\" value=\"$kw\" name=\"kw\">";
echo "<input type=\"hidden\" value=\"$status\" name=\"status\">";
echo "<input type=\"hidden\" value=\"$orderfieldsby\" name=\"orderfieldsby\">";
echo "<input type=\"hidden\" value=\"$limitofsales\" name=\"limitofsales\">";
echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
?>
<input type="submit" class="button" value="Add New Sale" name="mode">
</td>
</tr>
</table>
</center>
</div>
</form>

<p align="center"><a href="sales.php">Search Again</a></p>

<?php
}
}

else
{
$thisyear = date("Y", mktime (0,0,0,date("m"),date("d"),date("Y")));
$startyear = $thisyear-4;
?>

<form method="POST" action="sales.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" colspan="2" class="fieldname">View Sales:</td>
</tr>
<tr>
<td valign="middle" align="right">
Sold Between:
</td>
<td valign="middle" align="left">
<select size="1" name="startmonth">
<?php
for ($sm=1; $sm <= 12; ++$sm)
{
$stmo = date("F", mktime (0,0,0,date($sm),"1",$thisyear));
$stmoab = str_pad($sm, 2, "0", STR_PAD_LEFT); 
echo "<option ";
if ($sm == 1)
echo "selected ";
echo "value=\"$stmoab\">$stmo</option>";
}
?>
</select>
<select size="1" name="startyear">
<?php
for ($sy=$startyear; $sy <= $thisyear; ++$sy)
{
echo "<option ";
if ($startyear == $sy)
echo "selected ";
echo "value=\"$sy\">$sy</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td valign="middle" align="right">
and:
</td>
<td valign="middle" align="left">
<select size="1" name="endmonth">
<?php
for ($em=1; $em <= 12; ++$em)
{
$edmo = date("F", mktime (0,0,0,date($em),"1",$thisyear));
$edmoab = str_pad($em, 2, "0", STR_PAD_LEFT); 
echo "<option ";
if ($em == 12)
echo "selected ";
echo "value=\"$edmoab\">$edmo</option>";
}
?>
</select>
<select size="1" name="endyear">
<?php
for ($ey=$startyear; $ey <= $thisyear; ++$ey)
{
echo "<option ";
if ($thisyear == $ey)
echo "selected ";
echo "value=\"$ey\">$ey</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td valign="middle" align="right">Keywords:</td>
<td valign="middle" align="left"><input type="text" name="kw" size="24"></td>
</tr>
<tr>
<td valign="middle" align="right">
Order By:
</td>
<td valign="middle" align="left">
<select size="1" name="orderfieldsby">
<option selected value="date_">Order Date - New to Old</option>
<option value="date">Order Date - Old to New</option>
<option value="cost">Totals - Low to High</option>
<option value="cost_">Totals - High to Low</option>
<option value="qty">Quantity Sold - Low to High</option>
<option value="qty_">Quantity Sold - High to Low</option>
<option value="itemcust">Item or Customer - A to Z</option>
<option value="itemcust_">Item or Customer - Z to A</option></select>
</td>
</tr>
<tr>
<td vAlign="top" align="right">
Display Per Page: 
</td>
<td vAlign="top" align="left">
<select size="1" name="limitofsales">
<option value="5">5</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20" selected>20</option>
<option value="25">25</option>
<option value="30">30</option>
</select>
</td>
</tr>
<tr>
<td valign="middle" align="center" colspan="2">
<input type="submit" class="button" value="View Sales" name="mode">
</td>
</tr>
</table>
</center>
</div>
</form>

<form method="POST" action="sales.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">Bestsellers</td>
</tr>
<tr>
<td valign="middle" align="center">
View the top 50 bestselling products in your store.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="hidden" name="mode" value="bestsellers">
<input type="hidden" name="numsales" value="50">
<input type="submit" class="button" value="Show List">
</td>
</tr>
</table>
</center>
</div>
</form>


<?php
}
?>

<?php
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>