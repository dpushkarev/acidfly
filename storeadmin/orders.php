<script language="php">
include("includes/open.php");
// Update order details
if ($mode == "update")
{
$packing_address = addslash_mq($packing_address);
$updquery = "UPDATE " .$DB_Prefix ."_vars SET InvLogo='$packing_logo', Address='$packing_address', ";
$updquery .= "Phone='$packing_phone', Fax='$packing_fax', Payments='$payments' WHERE ID='1'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
if ($ret == "catalog")
{
@header ("location: catalog.php");
}
}
</script>
<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="/includes/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

<body background="/bg.jpg">
<?php
if (!$setactive)
$setactive = "No";
$show_act_screen = "no";

if ($itemmode != "Save")
echo "<a name=\"itemlist\"></a>";
include("includes/header.htm");
include("includes/links.php");

$today = date("n/j/y", mktime (0,0,0,date("m"),date("d"),date("Y")));
$todayphp = date("Y-m-d", mktime (0,0,0,date("m"),date("d"),date("Y")));

$linkinfo = "&smo=$smo";
$linkinfo .= "&syr=$syr";
$linkinfo .= "&emo=$emo";
$linkinfo .= "&eyr=$eyr";
$linkinfo .= "&kw=$kw";
$linkinfo .= "&stat=$stat";
$linkinfo .= "&ofb=$ofb";
$linkinfo .= "&los=$los";
$linkinfo .= "&ws=$ws";

// Get currency
$varquery = "SELECT Currency FROM " .$DB_Prefix ."_vars";
$varresult = mysqli_query($dblink,$varquery) or die ("Unable to select your system variables. Try again later.");
if (mysqli_num_rows($varresult) == 1)
{
$varrow = mysqli_fetch_row($varresult);
$Currency="$varrow[0]";
}

// Update order
if ($submit == "Update Order" OR $itemmode == "Save" AND ($ordid OR $order_id))
{
// Can only add unique orders
$chkoquery = "SELECT ID FROM " .$DB_Prefix ."_orders ";
if ($ordid)
$chkoquery .= "WHERE OrderNumber='$ordid'";
else
$chkoquery .= "WHERE OrderNumber='$order_id'";
if ($dbid)
$chkoquery .= "AND ID<>'$dbid'";
$chkoresult = mysqli_query($dblink, $chkoquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($chkoresult) == 0)
{
if ($orderdate != 0)
{
$splitdate = explode("/",$orderdate);
$order_date = date("Y-m-d", mktime(0,0,0,$splitdate[0],$splitdate[1],$splitdate[2]));
}
else
$order_date = $todayphp;
if ($shipdate != 0)
{
$splitsdate = explode("/",$shipdate);
$ship_date = date("Y-m-d", mktime(0,0,0,$splitsdate[0],$splitsdate[1],$splitsdate[2]));
}
else if ($stat == "Shipped")
$ship_date = $todayphp;
$method = addslash_mq($method);
$discount = number_format($discount, 2, '.', '');
$subtotal = number_format($subtotal, 2, '.', '');
$shipping = number_format($shipping, 2, '.', '');
$tax = number_format($tax, 2, '.', '');
$total = number_format($total, 2, '.', '');
$voucher = addslash_mq($voucher);
$vval = number_format($vval, 2, '.', '');
$shippingzone = addslash_mq($shippingzone);
$invname = addslash_mq($invname);
$invcompany = addslash_mq($invcompany);
$invaddress = addslash_mq($invaddress);
$invcity = addslash_mq($invcity);
$invstate = addslash_mq($invstate);
$invcountry = addslash_mq($invcountry);
$shipname = addslash_mq($shipname);
$shipaddress = addslash_mq($shipaddress);
$shipcity = addslash_mq($shipcity);
$shipstate = addslash_mq($shipstate);
$shipcountry = addslash_mq($shipcountry);
$extra = addslash_mq($extra);
$message = addslash_mq($message);
// Edit record
if ($ordid)
{
$updquery = "UPDATE " .$DB_Prefix ."_orders SET Method='$method', Discount='$discount', Tax='$tax', ";
$updquery .= "ShippingZone='$shippingzone', Shipping='$shipping', InvName='$invname', ";
$updquery .= "InvCompany='$invcompany', InvAddress='$invaddress', InvCity='$invcity', ";
$updquery .= "InvState='$invstate', InvZip='$invzip', InvCountry='$invcountry', Email='$email', ";
$updquery .= "Phone='$phone', Fax='$fax', ShipName='$shipname', ShipCity='$shipcity', ";
$updquery .= "ShipAddress='$shipaddress', ShipState='$shipstate', ShipZip='$shipzip', Extra='$extra', ";
$updquery .= "ShipCountry='$shipcountry', ShipPhone='$shipphone', WholesaleID='$wholesaleid', ";
$updquery .= "Voucher='$voucher', VoucherVal='$vval', Message='$message', Status='$ordstatus', ";
$updquery .= "OrderDate='$order_date', ShipDate='$ship_date' WHERE OrderNumber='$ordid'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
// Add new record
else if ($order_id)
{
$insquery = "INSERT INTO " .$DB_Prefix ."_orders (ID, OrderNumber, IPAddress, OrderDate, Method, Discount, ";
$insquery .= "Shipping, Tax, ShippingZone, InvName, InvCompany, InvAddress, InvCity, InvState, ";
$insquery .= "InvZip, InvCountry, Email, Phone, Fax, ShipName, ShipAddress, ShipCity, ShipState, ShipZip, ";
$insquery .= "ShipCountry, ShipPhone, WholesaleID, Extra, Voucher, VoucherVal, Message, Status, ShipDate) ";
$insquery .= "VALUES ('$id', '$order_id', '$ipaddress', '$order_date', '$method', '$discount', '$shipping', ";
$insquery .= "'$tax', '$shippingzone', '$invname', '$invcompany', '$invaddress', '$invcity', ";
$insquery .= "'$invstate', '$invzip', '$invcountry', '$email', '$phone', '$fax', '$shipname', '$shipaddress', ";
$insquery .= "'$shipcity', '$shipstate', '$shipzip', '$shipcountry', '$shipphone', '$wholesaleid', '$extra', ";
$insquery .= "'$voucher', '$vval', '$message', '$ordstatus', '$ship_date')";
$insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
$ordid = $order_id;
}
// Update items
$subtotal = 0;
for ($i=1; $i <= $numofitems; ++$i)
{
if ($item[$i])
{
$item[$i] = addslash_mq($item[$i]);
if (!$qty[$i])
$qty[$i] = 1;
// Double check entry
if (!$saleid[$i])
{
$chkquery = "SELECT ID FROM " .$DB_Prefix ."_sales WHERE OrderNumber='$ordid' AND DateSold='$order_date' ";
$chkquery .= "AND Item='$item[$i]' AND Price='$price[$i]' AND Units='$units[$i]' AND Quantity='$qty[$i]'";
$chkresult = mysqli_query($dblink, $chkquery) or die ("Unable to select. Try again later.");
$chknum = mysqli_num_rows($chkresult);
}

// If sale id exists and delete is set, delete record
if ($saleid[$i] AND $del[$i] == "yes")
{
$delquery = "DELETE FROM " .$DB_Prefix ."_sales WHERE ID='$saleid[$i]'";
$delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
}
// Else if sale id exists, update record
else if ($saleid[$i])
{
$updquery = "UPDATE " .$DB_Prefix ."_sales SET Quantity='$qty[$i]', Item='$item[$i]', Price='$price[$i]', Units='$units[$i]', ";
$updquery .= "DateSold='$order_date', OrderNumber='$ordid' WHERE ID='$saleid[$i]'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
$subtotal = $subtotal + ($price[$i] * $qty[$i]);
}
// Else add record
else if ($chknum == 0)
{
$insquery = "INSERT INTO " .$DB_Prefix ."_sales (Quantity, Item, Price, Units, DateSold, OrderNumber) ";
$insquery .= "VALUES ('$qty[$i]', '$item[$i]', '$price[$i]', '$units[$i]', '$order_date', '$ordid')";
$insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
$subtotal = $subtotal + ($price[$i] * $qty[$i]);
}
}
}
// Now update totals
$subtotal = number_format($subtotal, 2, '.', '');
$total = $subtotal - $discount - $voucherval + $tax + $shipping;
$total = number_format($total, 2, '.', '');
$totquery = "UPDATE " .$DB_Prefix ."_orders SET Subtotal='$subtotal', Total='$total' WHERE OrderNumber='$ordid'";
$totresult = mysqli_query($dblink, $totquery) or die("Unable to update. Please try again later.");
}
}

// Delete record
if ($submit == "Yes" AND $ordid)
{
$delquery = "DELETE FROM " .$DB_Prefix ."_orders WHERE OrderNumber='$ordid'";
$delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
$delsquery = "DELETE FROM " .$DB_Prefix ."_sales WHERE OrderNumber='$ordid'";
$delsresult = mysqli_query($dblink, $delsquery) or die("Unable to delete. Please try again later.");
}

// Show check delete form
if ($mode == "delord" AND $ordid)
{
?>
<form method="POST" action="orders.php">
<?php
echo "<input type=\"hidden\" value=\"$smo\" name=\"smo\">";
echo "<input type=\"hidden\" value=\"$syr\" name=\"syr\">";
echo "<input type=\"hidden\" value=\"$emo\" name=\"emo\">";
echo "<input type=\"hidden\" value=\"$eyr\" name=\"eyr\">";
echo "<input type=\"hidden\" value=\"$kw\" name=\"kw\">";
echo "<input type=\"hidden\" value=\"$stat\" name=\"stat\">";
echo "<input type=\"hidden\" value=\"$ofb\" name=\"ofb\">";
echo "<input type=\"hidden\" value=\"$los\" name=\"los\">";
echo "<input type=\"hidden\" value=\"$ws\" name=\"ws\">";
echo "<input type=\"hidden\" value=\"$pg\" name=\"pg\">";
echo "<input type=\"hidden\" value=\"$ordid\" name=\"ordid\">";
?>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="largetable">
<tr>
<td align="center">Are you sure you want to delete this order and its product sales? 
They will be removed permanently.</td>
</tr>
<tr>
<td align="center">
<input type="submit" class="button" value="Yes" name="submit"> 
<input type="submit" class="button" value="No" name="submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\"><a href=\"orders.php?pg=$pg$linkinfo\">Back to Orders</a></p>";
}

// Show edit form
else if (($mode == "updord" AND $ordid) OR $mode == "Add New Order")
{
if ($ordid)
{
$ordquery = "SELECT * FROM " .$DB_Prefix ."_orders WHERE OrderNumber='$ordid'";
$ordresult = mysqli_query($dblink, $ordquery) or die ("Unable to view sales.");
$ordrow = mysqli_fetch_array($ordresult);
if ($ordrow[OrderDate] > 0)
{
$splitdate = explode("-","$ordrow[OrderDate]");
$orderdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
if ($ordrow[ShipDate] > 0)
{
$splitsdate = explode("-","$ordrow[ShipDate]");
$shipdate = date("n/j/y", mktime(0,0,0,$splitsdate[1],$splitsdate[2],$splitsdate[0]));
}
$method=str_replace('"', "&quot;", stripslashes($ordrow[Method]));
$discount=number_format($ordrow[Discount], 2, '.', '');
$subtotal=number_format($ordrow[Subtotal], 2, '.', '');
$shipping=number_format($ordrow[Shipping], 2, '.', '');
$tax=number_format($ordrow[Tax], 2, '.', '');
$total=number_format($ordrow[Total], 2, '.', '');
$shippingzone=str_replace('"', "&quot;", stripslashes($ordrow[ShippingZone]));
$invname=str_replace('"', "&quot;", stripslashes($ordrow[InvName]));
$invcompany=str_replace('"', "&quot;", stripslashes($ordrow[InvCompany]));
$invaddress=str_replace('"', "&quot;", stripslashes($ordrow[InvAddress]));
$invcity=str_replace('"', "&quot;", stripslashes($ordrow[InvCity]));
$invstate=str_replace('"', "&quot;", stripslashes($ordrow[InvState]));
$invzip=$ordrow[InvZip];
$invcountry=str_replace('"', "&quot;", stripslashes($ordrow[InvCountry]));
$email=$ordrow[Email];
$phone=$ordrow[Phone];
$fax=$ordrow[Fax];
$shipname=str_replace('"', "&quot;", stripslashes($ordrow[ShipName]));
$shipaddress=str_replace('"', "&quot;", stripslashes($ordrow[ShipAddress]));
$shipcity=str_replace('"', "&quot;", stripslashes($ordrow[ShipCity]));
$shipstate=str_replace('"', "&quot;", stripslashes($ordrow[ShipState]));
$shipzip=$ordrow[ShipZip];
$shipcountry=str_replace('"', "&quot;", stripslashes($ordrow[ShipCountry]));
$shipphone=$ordrow[ShipPhone];
$extra=str_replace('"', "&quot;", stripslashes($ordrow[Extra]));
$voucher=str_replace('"', "&quot;", stripslashes($ordrow[Voucher]));
$vval=number_format($ordrow[VoucherVal], 2, '.', '');
$message=str_replace('"', "&quot;", stripslashes($ordrow[Message]));
$ordstatus = $ordrow[Status];
}
if (!$orderdate)
$orderdate = $today;
if (!$ordstatus)
$ordstatus = "On Order";
?>

<form method="POST" action="orders.php#itemlist">
<?php
echo "<input type=\"hidden\" value=\"$smo\" name=\"smo\">";
echo "<input type=\"hidden\" value=\"$syr\" name=\"syr\">";
echo "<input type=\"hidden\" value=\"$emo\" name=\"emo\">";
echo "<input type=\"hidden\" value=\"$eyr\" name=\"eyr\">";
echo "<input type=\"hidden\" value=\"$kw\" name=\"kw\">";
echo "<input type=\"hidden\" value=\"$stat\" name=\"stat\">";
echo "<input type=\"hidden\" value=\"$ofb\" name=\"ofb\">";
echo "<input type=\"hidden\" value=\"$los\" name=\"los\">";
echo "<input type=\"hidden\" value=\"$ws\" name=\"ws\">";
echo "<input type=\"hidden\" value=\"$pg\" name=\"pg\">";
if ($ordid)
echo "<input type=\"hidden\" value=\"$ordid\" name=\"ordid\">";
echo "<input type=\"hidden\" value=\"$ordrow[ID]\" name=\"dbid\">";
echo "<input type=\"hidden\" value=\"updord\" name=\"mode\">";
?>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="largetable">
<tr>
<td align="right">Order Date:</td>
<td align="left"><input type="text" name="orderdate" value="<?php echo "$orderdate"; ?>" size="20"></td>
<td align="right"></td>
<td align="right">Order ID:</td>
<td align="left">
<?php
if ($mode == "Add New Order")
echo "<input type=\"text\" name=\"order_id\" size=\"14\">";
else
echo "$ordid";
?>
</td>
</tr>
<tr>
<td align="right">Pay Via:</td>
<td align="left"><input type="text" name="method" value="<?php echo "$method"; ?>" size="20"></td>
<td align="right"></td>
<td align="right">Status:</td>
<td align="left">
<?php
echo "<select size=\"1\" name=\"ordstatus\">";
echo "<option ";
if ($ordstatus == "On Order")
echo "selected ";
echo "value=\"On Order\">On Order</option>";
echo "<option ";
if ($ordstatus == "Shipped")
echo "selected ";
echo "value=\"Shipped\">Shipped</option>";
echo "<option ";
if ($ordstatus == "Back Ordered")
echo "selected ";
echo "value=\"Back Ordered\">Back Ordered</option>";
echo "<option ";
if ($ordstatus == "Cancelled")
echo "selected ";
echo "value=\"Cancelled\">Cancelled</option>";
echo "<option ";
if ($ordstatus == "Completed")
echo "selected ";
echo "value=\"Completed\">Completed</option>";
echo "</select>";
?>
</td>
</tr>
<tr>
<td align="center" colspan="5"><hr noshade size="1" color="#A06C70"></td>
</tr>
<tr>
<td colspan="2" align="center" class="accent">Billing Information</td>
<td align="center" rowspan="11" width="1" background="images/line.gif"><img alt=\"-\" border="0" src="images/spacer.gif" width="1" height="1"></td>
<td colspan="2" align="center" class="accent">Shipping Information</td>
</tr>
<tr>
<td align="right">Name:</td>
<td align="left"><input type="text" name="invname" value="<?php echo "$invname"; ?>" size="20"></td>
<td align="right">Shipped Date:</td>
<td align="left"><input type="text" name="shipdate" value="<?php echo "$shipdate"; ?>" size="8"> (MM/DD/YY)</td>
</tr>
<tr>
<td align="right">Company:</td>
<td align="left"><input type="text" name="invcompany" value="<?php echo "$invcompany"; ?>" size="20"></td>
<td align="right">Name:</td>
<td align="left"><input type="text" name="shipname" value="<?php echo "$shipname"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">Address:</td>
<td align="left"><input type="text" name="invaddress" value="<?php echo "$invaddress"; ?>" size="20"></td>
<td align="right">Address:</td>
<td align="left"><input type="text" name="shipaddress" value="<?php echo "$shipaddress"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">City:</td>
<td align="left"><input type="text" name="invcity" value="<?php echo "$invcity"; ?>" size="20"></td>
<td align="right">City:</td>
<td align="left"><input type="text" name="shipcity" value="<?php echo "$shipcity"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">State:</td>
<td align="left">
<input type="text" name="invstate" value="<?php echo "$invstate"; ?>" size="20">
</td>
<td align="right">State:</td>
<td align="left"><input type="text" name="shipstate" value="<?php echo "$shipstate"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">Zip Code:</td>
<td align="left"><input type="text" name="invzip" value="<?php echo "$invzip"; ?>" size="20"></td>
<td align="right">Zip Code:</td>
<td align="left"><input type="text" name="shipzip" value="<?php echo "$shipzip"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">Country:</td>
<td align="left"><input type="text" name="invcountry" value="<?php echo "$invcountry"; ?>" size="20"></td>
<td align="right">Country:</td>
<td align="left"><input type="text" name="shipcountry" value="<?php echo "$shipcountry"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">Phone:</td>
<td align="left"><input type="text" name="phone" value="<?php echo "$phone"; ?>" size="20"></td>
<td align="right">Phone:</td>
<td align="left"><input type="text" name="shipphone" value="<?php echo "$shipphone"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">Fax:</td>
<td align="left"><input type="text" name="fax" value="<?php echo "$fax"; ?>" size="20"></td>
<td align="right">Ship Zone:</td>
<td align="left"><input type="text" name="shippingzone" value="<?php echo "$shippingzone"; ?>" size="20"></td>
</tr>
<tr>
<td align="right">Email:</td>
<td align="left"><input type="text" name="email" value="<?php echo "$email"; ?>" size="20"></td>
<td align="right">Voucher #:</td>
<td align="left"><input type="text" name="voucher" value="<?php echo "$voucher"; ?>" size="20"></td>
</tr>
<tr>
<td align="center" colspan="5">
<?php
if ($itemmode == "Save")
echo "<a name=\"itemlist\"></a>";
?>
<hr noshade size="1" color="#A06C70"></td>
</tr>
<tr>
<td align="center" colspan="5">
<div align="center">
<table border="0" cellpadding="3" cellspacing="0">
<tr>
<td class="smalltext"><?php if ($ordid) echo "<span class=\"accent\">Del</span>"; else echo "&nbsp;"; ?></td>
<td class="accent"><u>Qty</u></td>
<td class="accent"><u>Item</u></td>
<td class="accent"></td>
<td align="center" class="accent"><u>Units</u></td>
<td class="accent"><u>Price</u></td>
</tr>
<?php
$salenum = 0;
if ($ordid)
{
$salequery = "SELECT * FROM " .$DB_Prefix ."_sales WHERE OrderNumber='$ordid'";
$saleresult = mysqli_query($dblink, $salequery) or die ("Unable to select. Try again later.");
$salenum = mysqli_num_rows($saleresult);
}

if ($salenum > 0)
{
for ($sr=1; $salerow = mysqli_fetch_array($saleresult); ++$sr)
{
echo "<tr>";
echo "<td>";
echo "<input type=\"checkbox\" name=\"del[$sr]\" value=\"yes\">";
echo "<input type=\"hidden\" name=\"saleid[$sr]\" value=\"$salerow[ID]\">";
echo "</td>";
$quantity = (float)$salerow[Quantity];
echo "<td><input type=\"text\" name=\"qty[$sr]\" value=\"$quantity\" size=\"3\"></td>";
$item = str_replace('"', "&quot;", stripslashes($salerow[Item]));
echo "<td colspan=\"2\"><input type=\"text\" name=\"item[$sr]\" value=\"$item\" size=\"32\"></td>";
$units = (float)$salerow[Units];
echo "<td align=\"center\"><input type=\"text\" name=\"units[$sr]\" value=\"$units\" size=\"4\"></td>";
$price = number_format($salerow[Price], 2, '.', '');
echo "<td>$Currency<input type=\"text\" name=\"price[$sr]\" value=\"$price\" size=\"10\"></td>";
echo "</tr>";
}
}
if (!$sr)
$sr = 1;

for ($nr=$sr; $nr < $sr+3; ++$nr)
{
echo "<tr>";
echo "<td>&nbsp;</td>";
echo "<td><input type=\"text\" name=\"qty[$nr]\" size=\"3\"></td>";
echo "<td colspan=\"2\"><input type=\"text\" name=\"item[$nr]\" size=\"32\"></td>";
echo "<td align=\"center\"><input type=\"text\" name=\"units[$nr]\" size=\"4\"></td>";
echo "<td>$Currency<input type=\"text\" name=\"price[$nr]\" size=\"10\"></td>";
echo "</tr>";
}
?>
<tr>
<td></td>
<td></td>
<td align="right"></td>
<td align="right" colspan="2">Subtotal:</td>
<td><?php echo "$Currency $subtotal"; ?></td>
</tr>
<tr>
<td></td>
<td align="center" colspan="2">
<?php
if ($ordid)
echo "<input type=\"submit\" class=\"button\" value=\"Save\" name=\"itemmode\">";
?>
</td>
<td align="right" colspan="2">Voucher:</td>
<td><?php echo "$Currency"; ?><input type="text" name="vval" value="<?php echo "$vval"; ?>" size="10"></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td align="right" colspan="2">Discount:</td>
<td><?php echo "$Currency"; ?><input type="text" name="discount" value="<?php echo "$discount"; ?>" size="10"></td>
</tr>
<tr>
<td align="center" colspan="3">
<?php
if ($ordid)
echo "<a href=\"includes/packlist.php?ordid=$ordid&pr=y\" target=\"_blank\">Packing List w/ Prices</a>";
?>
</td>
<td align="right" colspan="2">Shipping:</td>
<td><?php echo "$Currency"; ?><input type="text" name="shipping" value="<?php echo "$shipping"; ?>" size="10"></td>
</tr>
<tr>
<td align="center" colspan="3">
<?php
if ($ordid)
echo "<a href=\"includes/packlist.php?ordid=$ordid&pr=n\" target=\"_blank\">Gift Receipt (No Prices)</a>";
?>
</td>
<td align="right" colspan="2">Tax:</td>
<td><?php echo "$Currency"; ?><input type="text" name="tax" value="<?php echo "$tax"; ?>" size="10"></td>
</tr>
<tr>
<td align="center" colspan="3">
<?php
if ($ordid)
echo "<a href=\"includes/label.php?ordid=$ordid\" target=\"_blank\">Mailing Label</a>";
?>
</td>
<td align="right" colspan="2">Total:</td>
<td><?php echo "$Currency $total"; ?></td>
</tr>
</table>
</div>
</td>
</tr>
<tr>
<td align="center" colspan="5"><hr noshade size="1" color="#A06C70"></td>
</tr>
<tr>
<td align="right" valign="top">Extra:</td>
<td align="left" colspan="4"><textarea rows="2" name="extra" cols="46"><?php echo "$extra"; ?></textarea></td>
</tr>
<tr>
<td align="right" valign="top">Message:</td>
<td align="left" colspan="4"><textarea rows="2" name="message" cols="46"><?php echo "$message"; ?></textarea></td>
</tr>
<tr>
<td colspan="5" align="center">
<?php
$numofitems = $nr-1;
echo "<input type=\"hidden\" name=\"numofitems\" value=\"$numofitems\">";
?>
<input type="submit" class="button" value="Update Order" name="submit">
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
echo "<p align=\"center\"><a href=\"orders.php?pg=$pg$linkinfo\">Back to Orders</a></p>";
}

else if (($smo AND $syr AND $emo AND $eyr) OR $ws)
{
if (!$los)
$los = 20;
if (!$smo)
$smo = 1;
if (!$syr)
$syr = "00";
if (!$emo)
$emo = date("m");
if (!$eyr)
$eyr = date("y");
$startdate = $syr ."-" .$smo ."-01";
$endday = date("t", mktime(0,0,0,$emo,"01",$eyr));
$enddate = $eyr ."-" .$emo ."-" .$endday;

$salesquery = "SELECT " .$DB_Prefix ."_orders.*, SUM(" .$DB_Prefix ."_sales.Quantity) AS SumQuantity FROM " .$DB_Prefix ."_orders";
$salesquery .= " LEFT JOIN " .$DB_Prefix ."_sales ON " .$DB_Prefix ."_orders.OrderNumber=" .$DB_Prefix ."_sales.OrderNumber";
$salesquery .= " WHERE " .$DB_Prefix ."_orders.OrderDate >= '$startdate' AND " .$DB_Prefix ."_orders.OrderDate <= '$enddate'";
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
$salesquery .= $DB_Prefix ."_orders.OrderNumber LIKE '%$addlterm%' OR InvName LIKE '%$addlterm%' OR ShipName LIKE '%$addlterm%' OR Email LIKE '%$addlterm%'";
}
$salesquery .= ")";
}
if ($ws)
$salesquery .= " AND " .$DB_Prefix ."_orders.WholesaleID='$ws'";
// STATUS SELECTION
if ($stat)
{
$stat_set = str_replace("_", " ", $stat);
$stat_set = ucwords($stat_set);
$salesquery .= " AND Status = '$stat_set'";
}
$salesquery .= " GROUP BY " .$DB_Prefix ."_orders.OrderNumber";

if ($ofb == "date_")
$salesquery .= " ORDER BY " .$DB_Prefix ."_orders.OrderDate DESC";
else if ($ofb == "date")
$salesquery .= " ORDER BY " .$DB_Prefix ."_orders.OrderDate";
if ($ofb == "cost_")
$salesquery .= " ORDER BY " .$DB_Prefix ."_orders.Total DESC";
else if ($ofb == "cost")
$salesquery .= " ORDER BY " .$DB_Prefix ."_orders.Total";
if ($ofb == "itemcust_")
$salesquery .= " ORDER BY " .$DB_Prefix ."_orders.InvName DESC";
else if ($ofb == "itemcust")
$salesquery .= " ORDER BY " .$DB_Prefix ."_orders.InvName";
if ($ofb == "qty_")
$salesquery .= " ORDER BY SumQuantity DESC";
else if ($ofb == "qty")
$salesquery .= " ORDER BY SumQuantity";
// Set page numbers
if (!$pg)
{
$pg = 1;
$offset = 0;
}
else
$offset = (($los * $pg)-$los);

$totalsalesquery = $salesquery;

// Total number of records in this particular page
$salesquery .= " LIMIT $offset, $los";
$salesresult = mysqli_query($dblink, $salesquery) or die ("Unable to view orders.");
$salesnum = mysqli_num_rows($salesresult);

// Total records altogether
$totalsalesresult = mysqli_query($dblink, $totalsalesquery) or die ("Unable to view orders.");
$totalsalesnum = mysqli_num_rows($totalsalesresult);

// Display Page Numbers on page
$offset = ($pg-1)*$los;
if ($totalsalesnum % $los == 0)
$page_count = ($totalsalesnum-($totalsalesnum%$los)) / $los;
else
$page_count = ($totalsalesnum-($totalsalesnum%$los)) / $los + 1;

$previous = $pg - 1;
$next = $pg + 1;

if ($page_count != 1)
{
$i = 1;
$n = 3;
if ($pg < $n+1)
$pagestart = 1;
else if ($pg > ($page_count-$n))
$pagestart = $page_count-$n*2;
else
$pagestart = $pg-$n;

while ($i <= $page_count)
{
if (($i >= $pagestart) AND ($i <= $n*2+$pagestart))
{
if ($i != $pg)
$output_string .= "<a href=\"orders.php?pg=$i$linkinfo\">$i</a>";
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
if ($pg > 1)
echo "<a href=\"orders.php?pg=1$linkinfo\"><<</a> | ";
if ($pg-$n > 1)
{
if ($pg-$n*2+1 > 1)
$prevpage = $pg-$n*2+1;
else
$prevpage = 1;
echo "<a href=\"orders.php?pg=$prevpage$linkinfo\"><</a> | ";
}
echo "$output_string";
if ($pg+$n < $page_count)
{
if ($pg+$n*2+1 < $page_count)
$nextpage = $pg+$n*2+1;
else
$nextpage = $page_count;
echo " <a href=\"orders.php?pg=$nextpage$linkinfo\">></a>";
}
if ($pg < $page_count)
echo " | <a href=\"orders.php?pg=$page_count$linkinfo\">>></a>";
echo "</p>";
}
// End Page Numbers
?>
<form action="orders.php" method="POST">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="largetable">
<?php
if ($salesnum == 0)
{
?>
<tr>
<td align="center" class="fieldname">No orders found</td>
</tr>
<tr>
<td align="center">
No orders were found with the criteria you specified. Please <a href="orders.php">try again</a>.
</td>
</tr>
<?php
}
else
{
$stmonth = date("F", mktime(0,0,0,$smo,"01",$syr));
$enmonth = date("F", mktime(0,0,0,$emo,$endday,$eyr));
echo "<p align=\"center\" class=\"fieldname\">";
if ($ws)
{
$wsquery = "SELECT Company FROM " .$DB_Prefix ."_wholesale WHERE ID='$ws'";
$wsresult = mysqli_query($dblink, $wsquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($wsresult) == 1)
{
$wsrow = mysqli_fetch_row($wsresult);
echo "Wholesale Orders for " .stripslashes($wsrow[0]);
}
else
echo "Could Not Find Wholesale Vendor";
}
else
echo "Ordered Between $stmonth $syr and $enmonth $eyr";
echo "</p>";
?>
<tr>
<td align="left" class="fieldname">Order #</td>
<td align="center" class="fieldname">Order Date</td>
<td align="center" class="fieldname">Total</td>
<td align="center" class="fieldname">Items</td>
<td align="left" class="fieldname">Bill To</td>
<td align="center" class="fieldname">Status</td>
<td align="center" class="fieldname">&nbsp;</td>
</tr>

<?php
for ($i = 1; $salesrow = mysqli_fetch_array($salesresult); ++$i)
{
$splitdate = explode("-","$salesrow[OrderDate]");
$orderdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
?>
<tr>
<td align="left" valign="top">
<?php
echo "<a href=\"orders.php?mode=updord&ordid=$salesrow[OrderNumber]&pg=$pg$linkinfo\">";
echo stripslashes($salesrow[OrderNumber]) ."</a>";
?>
</td>
<td align="center" valign="top">
<?php echo $orderdate; ?>
</td>
<td align="center" valign="top">
<?php echo $Currency .number_format($salesrow[Total], 2, '.', ''); ?>
</td>
<td align="center" valign="top">
<?php echo (float)$salesrow[SumQuantity]; ?>
</td>
<td align="left" valign="top">
<?php echo stripslashes($salesrow[InvName]); ?>
</td>
<td align="center" valign="top">
<?php echo "$salesrow[Status]"; ?>
</td>
<td align="center" valign="top" nowrap>
<?php
echo "<a href=\"orders.php?mode=delord&ordid=$salesrow[OrderNumber]&pg=$pg$linkinfo\">Delete</a> | ";
echo "<a href=\"includes/packlist.php?ordid=$salesrow[OrderNumber]&pr=y\" target=\"_blank\">Pack Slip</a> | ";
echo "<a href=\"includes/label.php?ordid=$salesrow[OrderNumber]\" target=\"_blank\">Label</a>";
?>
</td>
</tr>
<?php
}
}
?>
<tr>
<td colspan="7" align="center">
<?php
echo "<input type=\"hidden\" value=\"$smo\" name=\"smo\">";
echo "<input type=\"hidden\" value=\"$syr\" name=\"syr\">";
echo "<input type=\"hidden\" value=\"$emo\" name=\"emo\">";
echo "<input type=\"hidden\" value=\"$eyr\" name=\"eyr\">";
echo "<input type=\"hidden\" value=\"$kw\" name=\"kw\">";
echo "<input type=\"hidden\" value=\"$stat\" name=\"stat\">";
echo "<input type=\"hidden\" value=\"$ofb\" name=\"ofb\">";
echo "<input type=\"hidden\" value=\"$los\" name=\"los\">";
echo "<input type=\"hidden\" value=\"$ws\" name=\"ws\">";
echo "<input type=\"hidden\" value=\"$pg\" name=\"pg\">";
?>
<input type="submit" class="button" value="Add New Order" name="mode">
</td>
</tr>
</table>
</center>
</div>
</form>
<p align="center"><a href="orders.php">Search Again</a></p>
<?php
}

// Show order details
else if ($mode == "Update Details")
{
$packquery = "SELECT InvLogo, Address, Phone, Fax, Payments FROM " .$DB_Prefix ."_vars WHERE ID='1'";
$packresult = mysqli_query($dblink, $packquery) or die ("Unable to select. Try again later.");
$packrow = mysqli_fetch_array($packresult);
$pack_logo = $packrow[InvLogo];
$pack_address = stripslashes($packrow[Address]);
$pack_phone = $packrow[Phone];
$pack_fax = $packrow[Fax];
$pack_payments = $packrow[Payments];
?>
<form method="POST" action="orders.php" name="logo">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname" colspan="4">Catalog / Packing Slip Details</td>
</tr>
<tr>
<td valign="top" align="right">Packing Slip Logo:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="packing_logo" value="<?php echo "$pack_logo"; ?>" size="36"> 
<a href="includes/imgload.php?formsname=logo&fieldsname=packing_logo" target="_blank" onClick="PopUp=window.open('includes/imgload.php?formsname=logo&fieldsname=packing_logo', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;">Upload</a>
</td>
</tr>
<tr>
<td valign="top" align="right">Address:</td>
<td valign="top" align="left" colspan="3">
<textarea rows="2" name="packing_address" cols="37"><?php echo "$pack_address"; ?></textarea>
</td>
</tr>
<tr>
<td valign="top" align="right">Phone:</td>
<td valign="top" align="left">
<input type="text" name="packing_phone" value="<?php echo "$pack_phone"; ?>" size="14">
</td>
<td valign="top" align="left">Fax:</td>
<td valign="top" align="left">
<input type="text" name="packing_fax" value="<?php echo "$pack_fax"; ?>" size="14">
</td>
</tr>
<?php
if ($ret == "catalog")
{
?>
<tr>
<td valign="top" align="right">Payments Accepted:</td>
<td valign="top" align="left" colspan="3">
<input type="text" name="payments" value="<?php echo "$pack_payments"; ?>" size="43">
</td>
</tr>
<?php
}
?>
<tr>
<td valign="middle" align="center" colspan="4">
<?php if (!$ret) $ret = "orders"; ?>
<input type="hidden" value="<?php echo "$ret"; ?>" name="ret"> 
<input type="hidden" value="update" name="mode"> 
<input type="submit" class="button" value="Update" name="submit"> 
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
echo "<p align=\"center\">";
echo "<a href=\"$ret.php\">Back to Orders</a></p>";
}

else
{
$show_act_screen = "yes";

$thisyear = date("Y", mktime (0,0,0,date("m"),date("d"),date("Y")));
$syr = $thisyear-4;

$setpg_lower = "orders";
$setpg_upper = ucfirst($setpg_lower);

if ($submit == "Activate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='Yes' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
if ($submit == "Deactivate $setpg_upper Page")
{
$updquery = "UPDATE " .$DB_Prefix ."_pages SET Active='No' WHERE PageName='$setpg_lower' AND PageType='optional'";
$updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
?>

<form method="POST" action="orders.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" colspan="2" class="fieldname">View Orders:</td>
</tr>
<tr>
<td valign="middle" align="right">
Ordered Between:
</td>
<td valign="middle" align="left">
<select size="1" name="smo">
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
<select size="1" name="syr">
<?php
for ($sy=$syr; $sy <= $thisyear; ++$sy)
{
echo "<option ";
if ($syr == $sy)
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
<select size="1" name="emo">
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
<select size="1" name="eyr">
<?php
for ($ey=$syr; $ey <= $thisyear; ++$ey)
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
Status:
</td>
<td valign="middle" align="left">
<select size="1" name="stat">
<option selected value="">All Orders</option>
<option value="on_order">On Order</option>
<option value="shipped">Shipped</option>
<option value="back_ordered">Back Ordered</option>
<option value="cancelled">Cancelled</option>
<option value="completed">Completed</option>
</select>
</td>
</tr>
<tr>
<td valign="middle" align="right">
Order By:
</td>
<td valign="middle" align="left">
<select size="1" name="ofb">
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
<select size="1" name="los">
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
<input type="submit" class="button" value="View Orders" name="mode"> 
</td>
</tr>
</table>
</center>
</div>
</form>

<form method="POST" action="orders.php">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center" class="fieldname">Packing Slip Details</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Update Details" name="mode"> 
</td>
</tr>
</table>
</center>
</div>
</form>

<?php
$getquery = "SELECT ID, Active FROM " .$DB_Prefix ."_pages WHERE PageName='$setpg_lower' AND PageType='optional'";
$getresult = mysqli_query($dblink, $getquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($getresult) == 1)
{
$getrow = mysqli_fetch_row($getresult);
$pgid = $getrow[0];
$setactive = $getrow[1];
}

if ($setactive == "Yes" AND $pgid)
$show_ordtrack = "<a href=\"pages.php?edit=yes&pgid=$pgid\">order tracking</a>";
else
$show_ordtrack = "order tracking";
}

if ($setactive == "No" AND $show_act_screen == "yes")
{
?>
<form method="POST" action="<?php echo "$setpg.php"; ?>">
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 class="generaltable">
<tr>
<td valign="middle" align="center">
The order tracking page is currently inactive. This page is optional and does not have to be active 
to use the orders administration area. If you would like to activate the page, please click the button below.
</td>
</tr>
<tr>
<td valign="middle" align="center">
<input type="submit" class="button" value="Activate Orders Page" name="submit">
</td>
</tr>
</table>
</center>
</div>
</form>
<?php
}
else if ($show_act_screen == "yes")
{
echo "<p align=\"center\" class=\"smalltext\">";
echo "<a href=\"pages.php?edit=yes&pgid=$pgid\">Contents</a> | ";
echo "<a href=\"orders.php?submit=Deactivate+Orders+Page\">Deactivate</a></p>";
}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>