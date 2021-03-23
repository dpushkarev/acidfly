<script language="php">
require_once("../stconfig.php");
include("../$Inc_Dir/openinfo.php");
$wsdisc = 0;
$memdisc = 0;
$product = "{b}" .$product ."{/b}";
$catalog = "{b}" .$catalog ."{/b}";

// IF GIFT CERTIFICATE
if ($giftcert == "GC")
{
// If no issued to info
if (!$issued OR !$from OR !$sendto OR !$price)
{
$errmsg .= "http://$return&err=y";
$errsize = strlen($errmsg);
@header("Content-type: text/plain");
@header("Content-Length: $errsize");
@header("location: $errmsg");
@header("Connection: close");
die("<a href=\"$errmsg\">Please enter a value for all fields.</a>");
}
else
{
$splitprice = explode("~", $price);
$gotolink = "http://" .$Mals_Server .".aitsafe.com/cf/add.cfm";
$gotolink .= "?userid=$Mals_Cart_ID";
$prodlink = urlencode(stripslashes($product));
$prodlink .= "{br}To+-+" .urlencode(stripslashes($issued));
$prodlink .= "{br}From+-+" .urlencode(stripslashes($from));
$prodlink .= "{br}Send+To+-+" .urlencode(stripslashes($sendto));
$gotolink .= "&product=$prodlink&scode=$giftcert-$expmo-$splitprice[1]";
$gotolink .= "&price=$splitprice[0]&qty=1";
}
}

// IF PRODUCT OR VIEW CART
else
{

// CHECK FOR WHOLESALE
if ($wsemail AND $_COOKIE[wspass] AND !$regid)
{
$wsquery = "SELECT ID, Discount FROM " .$DB_Prefix ."_wholesale WHERE Email='$wsemail' AND Password='$wspass' AND Active='Yes'";
$wsresult = mysql_query($wsquery, $dblink) or die ("Unable to select. Try again later.");
$wsnum = mysql_num_rows($wsresult);
if ($wsnum == 1)
{
$wsrow = mysql_fetch_row($wsresult);
$wsid = $wsrow[0];
if ($wsrow[1] > 0)
$wsdisc = (float)$wsrow[1];
else if ($wsdiscount > 0)
$wsdisc = (float)$wsdiscount;
$tax = 0;
}
}

else if ($_COOKIE[membercode])
{
$thisday = date("Y-m-d");
$memquery = "SELECT GroupName, Discount FROM " .$DB_Prefix ."_coupons WHERE CodeNumber='$_COOKIE[membercode]' AND ExpireDate > '$thisday'";
$memresult = mysql_query($memquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($memresult) == 1)
{
$memrow = mysql_fetch_array($memresult);
$membername = stripslashes($memrow[GroupName]);
$memdisc = (float)$memrow[Discount];
}
}

// FORMAT NUMBERS
$price = str_replace(",", "", $price);
$units = str_replace(",", "", $units);

// DO INVENTORY OPTIONS EXIST?
if ($inventorycheck != "No")
{
if ($opttype1 == "Drop Down" OR $opttype1 == "Radio Button")
{
$splitatt = explode(":", $optval1);
$attribute1 = $splitatt[0];
$chkquery = "SELECT Quantity FROM " .$DB_Prefix ."_inventory WHERE ProductID='$productid' AND Attribute='$attribute1' AND Quantity<>''";
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to select. Try again later.");
$chknum = mysql_num_rows($chkresult);
if ($chknum == 1)
{
$chkrow = mysql_fetch_row($chkresult);
if ($qtty > $chkrow[0])
{
if ($regid AND $rguser)
$errmsg .= "http://$return&err=y&itm=$productid&inv=$chkrow[0]&opt=$attribute1&regid=$regid&rguser=$rguser#$rgnum";
else
$errmsg = "http://$return&err=y&itm=$productid&inv=$chkrow[0]&opt=$attribute1#$productid";
}
}
}
// Else check inventory for general products
else
{
$chkquery = "SELECT Inventory FROM " .$DB_Prefix ."_items WHERE ID='$productid' AND Inventory > 0";
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to select. Try again later.");
$chknum = mysql_num_rows($chkresult);
if ($chknum == 1)
{
$chkrow = mysql_fetch_row($chkresult);
if ($qtty > $chkrow[0])
{
if ($regid AND $rguser)
$errmsg .= "http://$return&err=y&itm=$productid&inv=$chkrow[0]&regid=$regid&rguser=$rguser#$rgnum";
else
$errmsg .= "http://$return&err=y&itm=$productid&inv=$chkrow[0]#$productid";
}
}
}
}

// IF ERROR MESSAGE EXISTS, GO TO ERROR
if ($errmsg)
{
$errsize = strlen($errmsg);
@header("Content-type: text/plain");
@header("Content-Length: $errsize");
@header("location: $gotolink");
@header("location: $errmsg");
@header("Connection: close");
die("<a href=\"$errmsg\">Sorry, there are not enough of this item. Please try again.</a>");
}

// IF REGISTRY IS SELECTED
else if (isset($_POST[Registry_x]) OR isset($_POST[Registry_y]) OR isset($_POST[Registry]))
{
$registrylink = "$URL/registry.$pageext";
$registrylink .= "?pid=$productid";
$registrylink .= "&q=$qtty";
$registrylink .= "&on=$optnum";
$registrylink .= "&md=a";
if ($optnum > 0)
{
for ($o = 1; $o <= $optnum; ++$o)
{
$optiontype = ${"opttype$o"};
$optionname = ${"optname$o"};
$optionval = ${"optval$o"};
$splitoptionval = explode(":", $optionval);
$getoptionval = $splitoptionval[0];
if ($optiontype == "Drop Down" OR $optiontype == "Radio Button")
{
$roquery = "SELECT Attributes FROM " .$DB_Prefix ."_options WHERE ItemID='$productid' AND Name='$optionname'";
$roresult = mysql_query($roquery, $dblink) or die ("Unable to select. Try again later.");
$rorow = mysql_fetch_row($roresult);
$roattributes = explode("~", $rorow[0]);
for ($ca=0; $ca < count($roattributes); ++$ca)
{
$ca_ov = $ca+1;
$splitroatts = explode(":", $roattributes[$ca]);
$getroatts = $splitroatts[0];
if ($getoptionval == $getroatts)
$registrylink .= "&ov$o=$ca_ov";
}
}
else if ($optiontype == "Check Box" AND $optionval)
$registrylink .= "&ov$o=y";
}
}
$registrysize = strlen($registrylink);
@header("Content-type: text/plain");
@header("Content-Length: $registrysize");
@header("location: $registrylink");
@header("Connection: close");
die("<a href=\"$registrylink\">Add to Wish List</a>");
}

// ELSE PLACE ORDER
else
{
// Create product list
$prodlist = "";
if ($optnum > 0)
{
for ($o = 1; $o <= $optnum; ++$o)
{
$optionname = ${"optname$o"};
$optionval = ${"optval$o"};
$optiontype = ${"opttype$o"};
if ($optiontype == "Drop Down" OR $optiontype == "Radio Button")
{
$attributes = explode(":", $optionval);
$prodlist .= "{br}$optionname - $attributes[0]";
if ($attributes[1])
$price = $price + $attributes[1];
if ($attributes[2])
$units = $attributes[2];
}
else if ($optiontype == "Check Box")
{
if ($optionval > 0)
$price = $price+$optionval;
if ($optionval)
$prodlist .= "{br}$optionname - yes";
else
$prodlist .= "{br}$optionname - no";
}
else
$prodlist .= "{br}$optionname - $optionval";
}
}

// Format product
if ($catalog)
$product = "$product $catalog";
$product = "$product$prodlist";
$product = str_replace("&frac14", "¼", stripslashes($product));
$product = str_replace("&frac12", "½", stripslashes($product));
$product = str_replace("&frac34", "¾", stripslashes($product));

$gotolink = "http://" .$Mals_Server .".aitsafe.com/cf/add.cfm";
$gotolink .= "?userid=$Mals_Cart_ID";

// Check for product discounts
if ($discountpr)
{
$discpr = "";
$discinfo = explode("~", $discountpr);
for ($dc=0; $dc <= count($discinfo); ++$dc)
{
$discval = explode(",", $discinfo[$dc]);
if ($dc == 0)
{
$dprqty = $discval[0]-1;
$newprice = $price;
}
else
{
$olddc = $dc-1;
$olddiscval = explode(",", $discinfo[$olddc]);
if ($dc == count($discinfo))
$dprqty = 0;
else
$dprqty = $discval[0]-$olddiscval[0];
if ($olddiscval[2] == "A")
$newprice = $price - $olddiscval[1];
else
$newprice = $price * (100-$olddiscval[1]) / 100;
}
// If Wholesale
if ($wsdisc > 0)
$newprice = $newprice*(100-$wsdisc)/100;
// If Member
else if ($memdisc > 0)
$newprice = $newprice*(100-$memdisc)/100;
if ($dc > 0)
$discpr .= ":";
$discpr .= "$dprqty,$newprice";
}
$gotolink .= "&discountpr=$discpr";
}

// Else if wholesale discount exists but no product discounts
else if ($wsdisc > 0)
$price= $price*((100-$wsdisc)/100);

// Else if member price exists but no product discounts
else if ($memdisc > 0)
$price= $price*((100-$memdisc)/100);

// If wholesale or member prices exist add to product
if ($wsdisc > 0)
$product .= "{br}WS Discount $wsdisc% Applied";
else if ($memdisc > 0)
$product .= "{br}Member Discount $memdisc% Applied";

// Set hidden stock code - product id
$scode = $productid;
// Show registrants id
$scode .= "-$rgnum";
// Show registry product id
$scode .= "-$regid";
// Show wholesaler id
$scode .= "-$wsid";
for ($v = 1; $v <= $optnum; ++$v)
{
if (isset(${"optval$v"}))
$scode .= "-" .strtolower(substr(${"optval$v"}, 0, 3));
}

// No wholesale or price changes
if (!$discountpr)
$gotolink .= "&price=$price";

// Urlencode product and return link
$productval = urlencode($product);
$gotolink .= "&product=$productval";
$gotolink .= "&scode=$scode";
if ($tax > 0)
$gotolink .= "&tax=$tax";
$gotolink .= "&units=$units";
$gotolink .= "&qty=$qtty";
}
}

if ($OrderProcess == "iframe")
{
if ($vc == "y")
$gotolink = "$View_Page?userid=$Mals_Cart_ID";
include("../template/$themename");
}
else
{
$return = urlencode($return);
$gotolink .= "&return=$return";
$gotosize = strlen($gotolink);
@header("location: $gotolink");
@header("Content-type: text/plain");
@header("Content-Length: $gotosize");
@header("Connection: close");
die("<a href=\"$gotolink\">Add to Cart</a>");
}
</script>