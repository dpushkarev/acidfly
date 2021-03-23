<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($mode == "send")
{
if ($orderemail)
{
$ordquery = "SELECT OrderNumber, Status, TrackingNumber, ShipDate FROM " .$DB_Prefix ."_orders WHERE ";
$ordquery .= "Email='$orderemail' ORDER BY OrderDate DESC, OrderNumber DESC LIMIT 1";
$ordresult = mysql_query($ordquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($ordresult) == 1)
{
$ordrow = mysql_fetch_row($ordresult);
$ordnum = $ordrow[0];
$ordlink = "$urldir/orders.$pageext";
if ($ordrow[3] != 0)
{
$splitdate = explode("-",$ordrow[3]);
$orddate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
if ($ordrow[1] == "Shipped" AND $ordrow[2] AND $orddate)
$status = "We show that order #$ordnum was shipped on $orddate. The tracking number for this order is $ordrow[2].";
else if ($ordrow[1] == "Shipped" AND $ordrow[2])
$status = "We show that order #$ordnum was shipped. The tracking number for this order is $ordrow[2].";
else if ($ordrow[1] == "Shipped" AND $orddate)
$status = "We show that order #$ordnum was shipped on $orddate.";
else if ($ordrow[1] == "Back Ordered")
$status = "We show that order #$ordnum is on back order at this time.";
else if ($ordrow[1] == "Completed")
$status = "We show that order #$ordnum has been processed.";
else if ($ordrow[1] == "Cancelled")
$status = "We show that order #$ordnum has been cancelled.";
else
$status = "We show that order #$ordnum is currently in progress.";
$ordmsg = "Thank you for your inquiry. $status To track this order, log in to our order tracking page ";
$ordmsg .= "at $ordlink with your email address $orderemail and the order number $ordnum. Here ";
$ordmsg .= "you can view your order status for any current order you have with our company.\r\n\r\n";
$ordmsg .= "If you have any questions, please contact us at $Admin_Email.\r\n\r\n";
$ordmsg .= "$Site_Name\r\n$URL";
@mail("$orderemail", "Forgotten Password", "$ordmsg", "From: $Admin_Email\r\nReply-To: $Admin_Email");
echo "<p>Thank you. Please check your email for your order information.</p>";
}
else
{
$mode = "forgot";
echo "<p align=\"center\" class=\"salecolor\">";
echo "Sorry, but we could not find your order information. ";
echo "Please contact us for assistance.</p>";
}
}
else
{
$mode = "forgot";
echo "<p align=\"center\" class=\"salecolor\">Please enter your email address.</p>";
}
}

if ($mode == "forgot")
{
?>
<p>Enter the email address used when you purchased your products and submit. Your last order number will be emailed to you shortly.</p>
<form action="<?php echo "orders.$pageext"; ?>" method="POST">
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td align="right">Email Address:</td>
<td align="left">
<input type="text" name="orderemail" size="40">
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="hidden" name="mode" value="send">
<input type="submit" value="Send Password" name="submit" class="formbutton">
</td>
</tr>
</table>
</form>
<?php
}

if ($mode == "show")
{
if (!$orderemail OR !$ordernumber)
{
$mode = "error";
echo "<p align=\"center\" class=\"salecolor\">Please enter your email address and order number.</p>";
}
else
{
$chkquery = "SELECT ID, Status FROM " .$DB_Prefix ."_orders WHERE Email='$orderemail' AND OrderNumber='$ordernumber'";
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to select. Try again later.");
$chknum = mysql_num_rows($chkresult);
if ($chknum == 0)
{
$mode = "error";
echo "<p align=\"center\" class=\"salecolor\">Sorry, but we could not find this order. Please try again.</p>";
}
else
{
$ordquery = "SELECT OrderNumber, OrderDate, Total, InvState, InvCountry, ";
$ordquery .= "ShipState, ShipCountry, Status, TrackingNumber, ShipDate FROM ";
$ordquery .= "" .$DB_Prefix ."_orders WHERE Email='$orderemail' AND Status != 'Completed'";
$ordresult = mysql_query($ordquery, $dblink) or die ("Unable to select. Try again later.");
$ordnum = mysql_num_rows($ordresult);
if ($ordnum == 0)
{
echo "<p>All of your orders have already been processed. If you have any questions ";
echo "about any order, please contact us for assistance.</p>";
}
else if ($ordnum == 1)
{
$mode = "ord";
$ordrow = mysql_fetch_row($ordresult);
$ordid = $ordrow[0];
$showback = "no";
}
else
{
echo "<p>Select an order number below to view more information</p>";
echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">";
echo "<tr class=\"boldtext\">";
echo "<td align=\"center\" class=\"accent\">Order Number</td>";
echo "<td align=\"center\" class=\"accent\">Order Date</td>";
echo "<td align=\"center\" class=\"accent\">Total Cost</td>";
echo "<td align=\"center\" class=\"accent\">Order Status</td>";
echo "</tr>";
while ($ordrow = mysql_fetch_array($ordresult))
{
if ($ordrow[ShipState])
$state = stripslashes($ordrow[ShipState]);
else
$state = stripslashes($ordrow[InvState]);
if ($ordrow[ShipCountry])
$country = stripslashes($ordrow[ShipCountry]);
else
$country = stripslashes($ordrow[InvCountry]);
if (strtolower($country) == "usa" OR strtolower($country) == "united states" OR strtolower($country) == "us" OR strtolower($country) == "america")
$location = $state;
else if (!$state)
$location = $country;
else
$location = $state .", " .$country;
$total = $Currency .number_format($ordrow[Total], 2);
if ($ordrow[OrderDate] != 0)
{
$splitdate = explode("-",$ordrow[OrderDate]);
$orderdate = date("n/j/y", mktime(0,0,0,$splitdate[1],$splitdate[2],$splitdate[0]));
}
if ($ordrow[ShipDate] != 0)
{
$splitsdate = explode("-",$ordrow[ShipDate]);
$shipdate = date("n/j/y", mktime(0,0,0,$splitsdate[1],$splitsdate[2],$splitsdate[0]));
}
$status = $ordrow[Status];
if ($ordrow[Status] == "Shipped" AND $ordrow[ShipDate] != 0) 
$status .= " " .$shipdate;
if ($ordrow[Status] == "Shipped" AND $ordrow[TrackingNumber])
$status .= "&nbsp;(#&nbsp;$ordrow[TrackingNumber])";

echo "<tr>";
echo "<td align=\"left\">";
echo "<a href=\"orders.$pageext?mode=ord&ordid=$ordrow[OrderNumber]";
echo "&orderemail=$orderemail&ordernumber=$ordernumber\">";
echo "$ordrow[OrderNumber]</a></td>";
echo "<td align=\"left\">$orderdate</td>";
echo "<td align=\"left\">$total</td>";
echo "<td align=\"left\">$status</td>";
echo "</tr>";
}
echo "</table>";
}
}
}
}

if ($mode == "ord")
{
$ordquery = "SELECT * FROM " .$DB_Prefix ."_orders WHERE OrderNumber='$ordid'";
$ordresult = mysql_query($ordquery, $dblink) or die ("Unable to view sales.");
$ordrow = mysql_fetch_array($ordresult);
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
else
$shipdate = "N/A";
$method=stripslashes($ordrow[Method]);
$discount=number_format($ordrow[Discount], 2);
$subtotal=number_format($ordrow[Subtotal], 2);
$shipping=number_format($ordrow[Shipping], 2);
$tax=number_format($ordrow[Tax], 2);
$total=number_format($ordrow[Total], 2);
$shippingzone=stripslashes($ordrow[ShippingZone]);
$invname=stripslashes($ordrow[InvName]);
$invcompany=stripslashes($ordrow[InvCompany]);
$invaddress=stripslashes($ordrow[InvAddress]);
$invcity=stripslashes($ordrow[InvCity]);
$invstate=stripslashes($ordrow[InvState]);
$invzip=$ordrow[InvZip];
$invcountry=stripslashes($ordrow[InvCountry]);
$email=$ordrow[Email];
$phone=$ordrow[Phone];
$fax=$ordrow[Fax];
if ($ordrow[ShipName] AND $ordrow[ShipAddress])
{
$shipname=stripslashes($ordrow[ShipName]);
$shipaddress=stripslashes($ordrow[ShipAddress]);
$shipcity=stripslashes($ordrow[ShipCity]);
$shipstate=stripslashes($ordrow[ShipState]);
$shipzip=$ordrow[ShipZip];
$shipcountry=stripslashes($ordrow[ShipCountry]);
$shipphone=$ordrow[ShipPhone];
}
else
{
$shipname=$invname;
$shipaddress=$invaddress;
$shipcity=$invcity;
$shipstate=$invstate;
$shipzip=$invzip;
$shipcountry=$invcountry;
$shipphone=$invphone;
}
$extra=stripslashes($ordrow[Extra]);
$voucher=stripslashes($ordrow[Voucher]);
$vval=number_format($ordrow[VoucherVal], 2);
$message=stripslashes($ordrow[Message]);
$status = $ordrow[Status];
?>

<p>View your order details below:</p>
<div align="center">
<center>
<table border=0 cellpadding=3 cellspacing=0 width="90%">
<tr>
<td align="right" nowrap>Order ID:</td>
<td align="left" nowrap><?php echo "$ordid"; ?></td>
<td align="right" nowrap>Order Date:</td>
<td align="left" nowrap><?php echo "$orderdate"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Paid Via:</td>
<td align="left" nowrap><?php echo "$method"; ?></td>
<td align="right" nowrap>Status:</td>
<td align="left" nowrap><?php echo "$status"; ?>
</td>
</tr>
<tr>
<td align="center" colspan="4" nowrap>
<?php 
if ($Product_Line)
echo "<hr class=\"linecolor\">";
else
echo "<hr class=\"#000000\">";
?>
</td>
</tr>
<tr>
<td colspan="2" align="center" class="accent" nowrap>Billing Information</td>
<td colspan="2" align="center" class="accent" nowrap>Shipping Information</td>
</tr>
<tr>
<td align="right" nowrap>Name:</td>
<td align="left" nowrap><?php echo "$invname"; ?></td>
<td align="right" nowrap>Shipped On:</td>
<td align="left" nowrap><?php echo "$shipdate"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Company:</td>
<td align="left" nowrap><?php echo "$invcompany"; ?></td>
<td align="right" nowrap>Name:</td>
<td align="left" nowrap><?php echo "$shipname"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Address:</td>
<td align="left" nowrap><?php echo "$invaddress"; ?></td>
<td align="right" nowrap>Address:</td>
<td align="left" nowrap><?php echo "$shipaddress"; ?></td>
</tr>
<tr>
<td align="right" nowrap>City:</td>
<td align="left" nowrap><?php echo "$invcity"; ?></td>
<td align="right" nowrap>City:</td>
<td align="left" nowrap><?php echo "$shipcity"; ?></td>
</tr>
<tr>
<td align="right" nowrap>State:</td>
<td align="left" nowrap><?php echo "$invstate"; ?></td>
<td align="right" nowrap>State:</td>
<td align="left" nowrap><?php echo "$shipstate"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Zip Code:</td>
<td align="left" nowrap><?php echo "$invzip"; ?></td>
<td align="right" nowrap>Zip Code:</td>
<td align="left" nowrap><?php echo "$shipzip"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Country:</td>
<td align="left" nowrap><?php echo "$invcountry"; ?></td>
<td align="right" nowrap>Country:</td>
<td align="left" nowrap><?php echo "$shipcountry"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Phone:</td>
<td align="left" nowrap><?php echo "$phone"; ?></td>
<td align="right" nowrap>Phone:</td>
<td align="left" nowrap><?php echo "$shipphone"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Fax:</td>
<td align="left" nowrap><?php echo "$fax"; ?></td>
<td align="right" nowrap>Ship Zone:</td>
<td align="left" nowrap><?php echo "$shippingzone"; ?></td>
</tr>
<tr>
<td align="right" nowrap>Email:</td>
<td align="left" nowrap><?php echo "$email"; ?></td>
<td align="right" nowrap>Voucher #:</td>
<td align="left" nowrap><?php echo "$voucher"; ?></td>
</tr>
<tr>
<td align="center" colspan="4">
<?php 
if ($Product_Line)
echo "<hr class=\"linecolor\">";
else
echo "<hr class=\"#000000\">";
?>
</td>
</tr>
</table>
</center>
</div>
<div align="center">
<center>
<table border="0" cellpadding="3" cellspacing="0" width="90%">
<tr class="accent">
<td><u>Qty</u></td>
<td><u>Item</u></td>
<td><u>Price</u></td>
<td><u>Ext</u></td>
</tr>
<?php
$salequery = "SELECT * FROM " .$DB_Prefix ."_sales WHERE OrderNumber='$ordid'";
$saleresult = mysql_query($salequery, $dblink) or die ("Unable to select. Try again later.");
for ($sr=1; $salerow = mysql_fetch_array($saleresult); ++$sr)
{
$quantity = (float)$salerow[Quantity];
$item = str_replace('"', "&quot;", stripslashes($salerow[Item]));
$price = number_format($salerow[Price], 2);
$ext = number_format($salerow[Price]*$quantity, 2);
echo "<tr>";
echo "<td valign=\"top\" nowrap>$quantity</td>";
echo "<td valign=\"top\">$item</td>";
echo "<td valign=\"top\" align=\"right\" nowrap>$Currency$price</td>";
echo "<td valign=\"top\" align=\"right\" nowrap>$Currency$ext</td>";
echo "</tr>";
}
?>
<tr>
<td colspan="2">&nbsp;</td>
<td align="right">Subtotal:</td>
<td align="right" nowrap><?php echo "$Currency$subtotal"; ?></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td align="right">Voucher:</td>
<td align="right" nowrap><?php echo "$Currency$vval"; ?></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td align="right">Discount:</td>
<td align="right" nowrap><?php echo "$Currency$discount"; ?></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td align="right">Shipping:</td>
<td align="right" nowrap><?php echo "$Currency$shipping"; ?></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td align="right">Tax:</td>
<td align="right" nowrap><?php echo "$Currency$tax"; ?></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td align="right">Total:</td>
<td align="right" nowrap><?php echo "$Currency$total"; ?></td>
</tr>
</table>
</center>
</div>
<?php
if ($showback != "no")
{
echo "<p align=\"center\">";
echo "<a href=\"orders.$pageext?mode=show&orderemail=$orderemail&ordernumber=$ordernumber\">";
echo "Back to Orders</a></p>";
}
}

if (!$mode OR $mode == "error")
{
?>
<p>Enter the email address used when you purchased your products, along with your order number, to view your order details.</p>
<form action="<?php echo "orders.$pageext"; ?>" method="POST">
<table border="0" cellpadding="3" cellspacing="0" align="center">
<tr>
<td align="right">Email Address:</td>
<td align="left">
<input type="text" name="orderemail" size="40">
</td>
</tr>
<tr>
<td align="right">Order Number:</td>
<td align="left">
<input type="text" name="ordernumber" size="20">
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="hidden" name="mode" value="show">
<input type="submit" value="Get Order Details" name="submit" class="formbutton">
</td>
</tr>
<tr>
<td colspan="2" align="right">
<?php echo "<a href=\"orders.$pageext?mode=forgot\">Forgot your order number?</a>"; ?>
</td>
</tr>
</table>
</form>
<?php
}
?>