<html>

<head>
<title>Administration</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php
include("open.php");

$varquery = "SELECT OrderOfProduct, Currency, SiteName, ImageWidth, ImageHeight, ";
$varquery .= "ThumbnailDir, LgImageDir FROM " .$DB_Prefix ."_vars WHERE ID=1";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($varresult) == 1)
{
$varrow = mysql_fetch_row($varresult);
$orderby = $varrow[0];
$currency = $varrow[1];
$sitename = stripslashes($varrow[2]);
$imgwidth = $varrow[3];
$imgheight = $varrow[4];
$thumbdir = $varrow[5];
$lgdir = $varrow[6];
}

// Print catalog report
echo "<p align=\"center\" class=\"largeboldtext\">$sitename Catalog</p>";

$itemquery = "SELECT * FROM " .$DB_Prefix ."_items WHERE Active = 'Yes' AND OutOfStock = 'No' AND WSOnly = 'No'";
$itemquery .= "AND (Inventory IS NULL OR Inventory > 0)";
if ($orderby)
$itemquery .= " ORDER BY $orderby";
$itemresult = mysql_query($itemquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($itemresult) == 0)
echo "<p align=\"center\">Sorry, no records are found.</p>";
else
{
echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">";
echo "<tr class=\"accent\">";
echo "<td>Qty</td>";
if ($showimages == "yes")
echo "<td>Image</td>";
echo "<td nowrap>Description</td>";
echo "<td nowrap>Each</td>";
echo "<td>Total</td>";
echo "</tr>";

while ($itemrow = mysql_fetch_array($itemresult))
{

// Get image
if ($itemrow[SmImage])
{
if (substr($itemrow[SmImage], 0, 7) == "http://")
$imgurl = "$itemrow[SmImage]";
else
$imgurl = "$urldir/$itemrow[SmImage]";
$showimage = "<img src=\"$imgurl\" ";
if ($imgwidth > 0)
$showimage .= "width=\"$imgwidth\" ";
if ($imgheight > 0)
$showimage .= "height=\"$imgheight\" ";
$showimage .= "border=\"0\">";
}
else
$showimage = "&nbsp;";

// Get Item
$showitem = "";
if ($itemrow[Catalog])
$showitem .= "#" .stripslashes($itemrow[Catalog]) .". ";
$showitem .= stripslashes($itemrow[Item]);
$showdescription = strip_tags(stripslashes($itemrow[Description]));

// Get discounts
if ($itemrow[DiscountPr] > 0)
{
$discountinfo = explode("~", $itemrow[DiscountPr]);
for ($dc=0; $dc < count($discountinfo); ++$dc)
{
$discinfo = explode(",", $discountinfo[$dc]);
$showdisc = "<br>Buy $discinfo[0] or more receive ";
if ($discinfo[2] == "A")
$showdisc .= "$currency" .number_format($discinfo[1], 2) ." off";
else
$showdisc .= "a $discinfo[1]% discount";
}
}

// Get prices
if ($itemrow[SalePrice] > 0)
$showprice = $itemrow[SalePrice];
else
$showprice = $itemrow[RegPrice];

// Get options
$showopts = "";
if ($splitoptions == "yes")
{
$optquery = "SELECT * FROM " .$DB_Prefix ."_options WHERE ItemID='$itemrow[ID]' AND Active='Yes' ";
$optquery .= "AND Type != 'Memo Field' AND Type != 'Text Box' AND (Attributes NOT LIKE '%:%' ";
$optquery .= "OR (Attributes LIKE '%:%' AND OptionNum > 1)) ORDER BY OptionNum, Name";
}
else
{
$optquery = "SELECT * FROM " .$DB_Prefix ."_options WHERE ItemID='$itemrow[ID]' AND Active='Yes' ";
$optquery .= "AND Type != 'Memo Field' AND Type != 'Text Box' ORDER BY OptionNum, Name";
}
$optresult = mysql_query($optquery, $dblink) or die ("Unable to select options. Try again later.");
$optnum = mysql_num_rows($optresult);
for ($or=1; $optrow = mysql_fetch_array($optresult); ++$or)
{
$attlist = explode("~", $optrow[Attributes]);
$showopts .= "<br>$optrow[Name]: ";
for ($t=0; $t < count($attlist); ++$t)
{
$thisatt = explode(":", $attlist[$t]);
$showopts .= " &nbsp; [&nbsp;&nbsp;&nbsp;]&nbsp;";
if ($optrow[Type] == "Check Box" AND $thisatt[0] > 0)
$showopts .= $currency .number_format($thisatt[0], 2);
else
{
$showopts .= "$thisatt[0]";
if ($thisatt[1] > 0)
$showopts .= " add " .$currency .number_format($thisatt[1], 2);
}
}
}

// Show options
$chkatts = "";
$chkquery = "SELECT Attributes FROM " .$DB_Prefix ."_options WHERE ItemID='$itemrow[ID]' AND Active='Yes' ";
$chkquery .= "AND Attributes LIKE '%:%' AND (Type='Drop Down' OR Type='Radio Button') ";
$chkquery .= "AND OptionNum = '1'";
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to select options. Try again later.");
if (mysql_num_rows($chkresult) == 1 AND $splitoptions == "yes")
{
$chkrow = mysql_fetch_array($chkresult);
$chkatts = explode("~", $chkrow[0]);
$loopend = count($chkatts);
}
else
$loopend = 1;

for ($s=1; $s <= $loopend; ++$s)
{
echo "<tr>";
echo "<td valign=\"top\">____</td>";
if ($showimages == "yes")
echo "<td valign=\"top\">$showimage</td>";
echo "<td valign=\"top\">";
$sless = $s-1;
if ($chkatts[$sless] AND $splitoptions == "yes")
{
$firstopt = explode(":", $chkatts[$sless]);
$showproduct = " $firstopt[0]";
$newprice = $showprice +$firstopt[1];
}
else
{
$showproduct = "";
$newprice = $showprice;
}
$newprice = number_format($newprice, 2);
echo "<b>$showitem$showproduct</b> ";
echo "$showdescription";
echo "$showdisc";
echo "$showopts";
echo "</td>";
echo "<td valign=\"top\" nowrap>$currency$newprice</td>";
echo "<td valign=\"top\" nowrap>$currency ________</td>";
echo "</tr>";
}
}
echo "</table>";

// Create Order Form
$varquery = "SELECT SiteName, URL, AdminEmail, InvLogo, Address, Phone, ";
$varquery .= "Fax, Payments FROM " .$DB_Prefix ."_vars WHERE ID='1'";
$varresult = mysql_query($varquery, $dblink) or die ("Unable to select. Try again later.");
$varrow = mysql_fetch_array($varresult);
if (substr($varrow[InvLogo], 0, 7) == "http://")
$mylogo = $varrow[InvLogo];
else
$mylogo = $urldir ."/" .$varrow[InvLogo];
$mycompany = stripslashes($varrow[SiteName]);
$myaddress = str_replace("\n", "<br>", stripslashes($varrow[Address]));
$myemail = $varrow[AdminEmail];
$myphone = $varrow[Phone];
$myfax = $varrow[Fax];
$payments = $varrow[Payments];
if ($myaddress)
{
?>
<hr noshade size="1" color="#C0C0C0">
<table border="0" cellpadding="5" cellspacing="0" align="center" width="100%">
<tr>
<td valign="middle" align="center" rowspan="6">&nbsp;&nbsp;&nbsp;</td>
<td valign="middle" align="center" rowspan="6">
<?php
if ($mylogo)
echo "<img src=\"$mylogo\" border=\"0\">";
?>
</td>
<td valign="middle" align="center" rowspan="6">
<?php
echo "<b>$mycompany</b><br>";
if ($myaddress)
echo "$myaddress<br>";
if ($myphone)
echo "Phone: $myphone<br>";
if ($myfax)
echo "FAX: $myfax<br>";
echo "$urldir<br>";
if ($myemail)
echo "$myemail";
?>
</td>
<td valign="middle" align="center" rowspan="6" width="100%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td valign="top" align="center" colspan="4">
<?php
if ($payments)
echo "<i>Payments Accepted:</i><br>$payments";
?>
</td>
<td valign="middle" align="center" rowspan="5">&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr class="smfont">
<td valign="top" align="right" nowrap>Bill To:</td>
<td valign="top" align="left">________________</td>
<td valign="top" align="right" nowrap>Ship To:</td>
<td valign="top" align="left">________________</td>
</tr>
<tr class="smfont">
<td valign="top" align="right" nowrap>Street Address:</td>
<td valign="top" align="left">________________</td>
<td valign="top" align="right" nowrap>Street Address:</td>
<td valign="top" align="left">________________</td>
</tr>
<tr class="smfont">
<td valign="top" align="right" nowrap>City/State/Zip:</td>
<td valign="top" align="left">________________</td>
<td valign="top" align="right" nowrap>City/State/Zip:</td>
<td valign="top" align="left">________________</td>
</tr>
<tr class="smfont">
<td valign="top" align="right" nowrap>Phone:</td>
<td valign="top" align="left">________________</td>
<td valign="top" align="right" nowrap>Email:</td>
<td valign="top" align="left">________________</td>
</tr>
</table>
<?php
}

}
?>
</body>