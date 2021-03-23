<?php
if ($Product_Line)
$frmbord = 1;
else
$frmbord = 0;

// First check to see if the return link is a catalog link
if (substr($return, 0, 7) == "http://")
$catret = $return;
else
$catret = "http://" .$return;
if (substr($catret, 0, strlen($URL)) == $URL)
$retlink = $catret;
else
$retlink = $Catalog_Page;
echo "<p align=\"center\" style=\"margin: 0\"><a href=\"$retlink\">Continue Shopping</a></p>";

// Ship to address exist for gift registry?
if (isset($rgid) AND isset($rguser) AND !isset($rgpass))
{
$usrquery = "SELECT * FROM " .$DB_Prefix ."_registry WHERE RegUser='$rguser' AND ID='$rgid' AND Type='Public'";
$usrresult = mysql_query($usrquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($usrresult) == 1)
{
$usrrow = mysql_fetch_array($usrresult);
$name1 = stripslashes($usrrow[RegName1]);
if ($usrrow[ShipToCity] AND ($usrrow[ShipToState] OR $usrrow[ShipToCountry]))
{
$shipto = stripslashes($usrrow[ShipToName]);
$address = stripslashes($usrrow[ShipToAddress]);
$city = stripslashes($usrrow[ShipToCity]);
$state = stripslashes($usrrow[ShipToState]);
if ($usrrow[ShipToCountry] != "United States")
$country = stripslashes($usrrow[ShipToCountry]);
$zip = $usrrow[ShipToZip];
echo "<p align=\"center\">";
echo "<span class=\"accent\">Use Shipping Address:</span>";
if ($shipto)
echo "<br>$shipto";
if ($address)
echo "<br>$address";
if ($city AND $state AND $zip)
echo "<br>$city, $state $zip $country";
else
echo "<br>$state $zip $country";
echo "</p>";
}
}
}

echo "<iframe src=\"$gotolink\" width=\"100%\" height=\"1200\" frameborder=\"$frmbord\" scrolling=\"auto\">";
$product = str_replace("{", "<", $product);
$product = str_replace("}", ">", $product);
$product = str_replace(" - ", ": ", $product);
echo "<p align=\"center\">";
echo "Please review the information below:</p>";
echo "<p align=\"center\">";
echo "$product";
echo "<br>Price: $Currency$price";
echo "<br>Quantity: $qtty</p>";
echo "<p align=\"center\">";
echo "<a href=\"$gotolink\" target=\"_blank\" class=\"orderlink\" ";
echo "onClick=\"PopUp=window.open('$gotolink', 'NewWin', 'scrollbars=yes,resizable=yes,width=400,height=400,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">";
echo "<b>Order</b></a></p>";
echo "</iframe>";

echo "<p align=\"center\"><a href=\"$retlink\">Continue Shopping</a><br>&nbsp;</p>";
?>
