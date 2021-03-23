<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Get total table count
if ($Item_Columns == 0)
$totscount = $searchnum;
else if (($searchnum%$Item_Columns == 0) OR ($searchnum < $Item_Columns))
$totscount = $searchnum;
else
$totscount = $searchnum+($Item_Columns-($searchnum%$Item_Columns));
echo "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" ";
if ($Product_Line)
echo "class=\"linetable\" ";
echo "width=\"100%\">";
// Display one item
for ($scount = 1; $searchrow = mysql_fetch_array($searchresult), $scount <= $totscount; ++$scount)
{
if ($Item_Columns == 0)
{
$toptr = 0;
$bottr = 0;
$tcwidth = 100;
}
else
{
$toptr = ($scount + ($Item_Columns-1)) % $Item_Columns;
$bottr = $scount % $Item_Columns;
if ($searchnum < $Item_Columns)
$tcwidth = floor(100/$searchnum);
else
$tcwidth = floor(100/$Item_Columns);
}
if ($toptr == 0)
echo "<tr>";
echo "<td align=\"center\" valign=\"top\" ";
if ($Product_Line)
echo "class=\"linecell\" ";
echo "width=\"$tcwidth%\">";
// Display one item if it exists
if ($searchrow[0] > 0)
{
$priceset = "yes";
$stripitem = stripslashes($searchrow[2]);
$stripitem = str_replace("\"", "&quot;", $stripitem);
$stripdescription = stripslashes($searchrow[3]);

$relquery = "SELECT " .$DB_Prefix ."_related.RelatedID, " .$DB_Prefix ."_items.Item, " .$DB_Prefix ."_items.SmImage FROM " .$DB_Prefix ."_related, " .$DB_Prefix ."_items WHERE ";
$relquery .= $DB_Prefix ."_related.RelatedID = " .$DB_Prefix ."_items.ID AND " .$DB_Prefix ."_related.ProductID = '$searchrow[0]'";
$relresult = mysql_query($relquery, $dblink) or die ("Unable to access database.");
$relnum = mysql_num_rows($relresult);

// Set main item price
if ($searchrow[11] != 0)
$unitprice = $searchrow[11];
else
$unitprice = $searchrow[10];

// Show order button?
if (($Set_Ord_Button == "Prices" AND $unitprice == 0) OR $Set_Ord_Button == "No")
$Show_Ord_Button = "No";
else
$Show_Ord_Button = "Yes";

if ($searchrow[1])
$unitinfo = " #$searchrow[1]";
else
$unitinfo = "";

// Show product discount message
if ($searchrow[18])
{
$discprset = "";
$discountinfo = explode("~", $searchrow[18]);
for ($dc=0; $dc < count($discountinfo); ++$dc)
{
$discinfo = explode(",", $discountinfo[$dc]);
if ($dc > 0)
$discprset .= "<br>";
$discprset .= "Buy $discinfo[0] or more and receive ";
if ($discinfo[2] == "A")
$discprset .= $Currency .number_format($discinfo[1], 2, '.', '') ." off";
else
$discprset .= "a $discinfo[1]% discount";
}
$discprset .= "<input type=\"hidden\" name=\"discountpr\" value=\"$searchrow[18]\">";
}
else
$discprset = "";

// START OPTION DISPLAY
$show_item_inv = "yes";
$optquery = "SELECT * FROM " .$DB_Prefix ."_options WHERE ItemID = '$searchrow[0]' AND Active <> 'No'";
$chkquery = $optquery ." AND OptionNum='1' AND Attributes LIKE '%:%'";
$optquery .= " ORDER BY OptionNum";
$optresult = mysql_query($optquery, $dblink) or die ("Unable to access database.");
$optnum = mysql_num_rows($optresult);
$chkresult = mysql_query($chkquery, $dblink) or die ("Unable to access database.");
$chknum = mysql_num_rows($chkresult);
$display_options = "";
if ($optnum != 0)
{
for ($optcount = 1; $optrow = mysql_fetch_array($optresult); ++$optcount)
{
$optionname = stripslashes($optrow[3]);
$attributes = stripslashes($optrow[5]);
$attributes = str_replace("\"", "&quot;", $attributes);
$display_options .= "<tr><td valign=\"top\" align=\"right\" class=\"accent\">";
$display_options .= "$optionname:</td>";
$display_options .= "<td valign=\"top\" align=\"left\">";
$display_options .= "<input type=\"hidden\" name=\"opttype$optcount\" value=\"$optrow[4]\">";
$display_options .= "<input type=\"hidden\" name=\"optname$optcount\" value=\"$optionname\">";
$product_id = $searchrow[0];
include ("$Inc_Dir/products_options.php");
$display_options .= "</td></tr>";
}
}
// END OPTION DISPLAY

// Set cart variables
echo "<form method=\"POST\" action=\"$URL/go/order.php\">";
echo "<input type=\"hidden\" name=\"pgnm_noext\" value=\"$pgnm_noext\">";
echo "<input type=\"hidden\" name=\"pageext\" value=\"php\">";
echo "<input type=\"hidden\" name=\"productid\" value=\"$searchrow[0]\">";
echo "<input type=\"hidden\" name=\"return\" value=\"$Return_URL\">";
echo "<input type=\"hidden\" name=\"product\" value=\"$stripitem\">";
echo "<input type=\"hidden\" name=\"catalog\" value=\"$unitinfo\">";
echo "<input type=\"hidden\" name=\"price\" value=\"$unitprice\">";
echo "<input type=\"hidden\" name=\"units\" value=\"$searchrow[9]\">";
echo "<input type=\"hidden\" name=\"tax\" value=\"$searchrow[23]\">";
// Start row
echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">";
echo "<tr>";
echo "<td valign=\"top\" align=\"center\" colspan=\"2\">";
echo "<a name=\"$searchrow[0]\"></a>";

// If there is a small image, display it
if ($searchrow[SmImage])
{
if (substr($searchrow[SmImage], 0, 7) == "http://")
$imgname1 = "$searchrow[SmImage]";
else
$imgname1 = "$URL/$searchrow[SmImage]";
$smimg1 = "<img src=\"$imgname1\" alt=\"$stripitem\" ";
if ($imgwidth > 0)
$smimg1 .= "width=\"$imgwidth\" ";
if ($imgheight > 0)
$smimg1 .= "height=\"$imgheight\" ";
$smimg1 .= "border=\"0\">";
// If there is a larger image, create clickable thumbnail
if ($searchrow[LgImage])
{
$imgsize1=@getimagesize($searchrow[LgImage]);
$lgwidth1=$imgsize1[0]+20;
$lgheight1=$imgsize1[1]+60;
echo "<a href=\"go/view.php?image=$searchrow[LgImage]\" target=\"_blank\" onClick=\"PopUp=window.open('go/view.php?image=$searchrow[LgImage]', 'NewWin', 'resizable=yes,width=$lgwidth1,height=$lgheight1,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">";
echo "$smimg1</a>";
}
// Otherwise, just display small image
else
echo "$smimg1";
echo "<br>";
}

// If there is a second small image, display it
if ($searchrow[SmImage2])
{
if (substr($searchrow[SmImage2], 0, 7) == "http://")
$imgname2 = "$searchrow[SmImage2]";
else
$imgname2 = "$URL/$searchrow[SmImage2]";
$smimg2 = "<img src=\"$imgname2\" alt=\"$stripitem\" ";
if ($imgwidth > 0)
$smimg2 .= "width=\"$imgwidth\" ";
if ($imgheight > 0)
$smimg2 .= "height=\"$imgheight\" ";
$smimg2 .= "border=\"0\">";
// If there is a larger image, create clickable thumbnail
if ($searchrow[LgImage2])
{
$imgsize2=@getimagesize($searchrow[LgImage2]);
$lgwidth2=$imgsize2[0]+20;
$lgheight2=$imgsize2[1]+60;
echo "<a href=\"go/view.php?image=$searchrow[LgImage2]\" target=\"_blank\" onClick=\"PopUp=window.open('go/view.php?image=$searchrow[LgImage2]', 'NewWin', 'resizable=yes,width=$lgwidth2,height=$lgheight2,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">";
echo "$smimg2</a>";
}
// Otherwise, just display small image
else
echo "$smimg2";
echo "<br>";
}

// If there is a third small image, display it
if ($searchrow[SmImage3])
{
if (substr($searchrow[SmImage3], 0, 7) == "http://")
$imgname3 = "$searchrow[SmImage3]";
else
$imgname3 = "$URL/$searchrow[SmImage3]";
$smimg3 = "<img src=\"$imgname3\" alt=\"$stripitem\" ";
if ($imgwidth > 0)
$smimg3 .= "width=\"$imgwidth\" ";
if ($imgheight > 0)
$smimg3 .= "height=\"$imgheight\" ";
$smimg3 .= "border=\"0\">";
// If there is a larger image, create clickable thumbnail
if ($searchrow[LgImage3])
{
$imgsize3=@getimagesize($searchrow[LgImage3]);
$lgwidth3=$imgsize3[0]+20;
$lgheight3=$imgsize3[1]+60;
echo "<a href=\"go/view.php?image=$searchrow[LgImage3]\" target=\"_blank\" onClick=\"PopUp=window.open('go/view.php?image=$searchrow[LgImage3]', 'NewWin', 'resizable=yes,width=$lgwidth3,height=$lgheight3,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">";
echo "$smimg3</a>";
}
// Otherwise, just display small image
else
echo "$smimg3";
echo "<br>";
}

echo "<span class=\"boldtext\">";
// If there is a catalog number, display it
if ($searchrow[1])
echo "#$searchrow[1]. ";
echo "$stripitem";
echo "</span>";

if ($searchrow[3])
{
if (strstr($searchrow[3], '<') == FALSE AND strstr($searchrow[3], '>') == FALSE)
{
$stripdescription = str_replace ("\r", "<br>", $stripdescription);
echo "<p class=\"p_layout\">$stripdescription</p>";
}
else
echo "<div class=\"p_layout\">$stripdescription</div>";
}

// Show pop up if it exists
if ($searchrow[24])
{
$popquery = "SELECT PageTitle FROM " .$DB_Prefix ."_popups WHERE ID='$searchrow[24]'";
$popresult = mysql_query($popquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($popresult) == 1)
{
$poprow = mysql_fetch_array($popresult);
echo "<p class=\"p_layout\">";
echo "<a href=\"go/popup.php?pgid=$searchrow[24]\" target=\"_blank\" onClick=\"PopUp=window.open('go/popup.php?pgid=$searchrow[24]', 'NewWin', 'resizable=yes,width=425,height=500,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\" class=\"popupcolor\">";
echo stripslashes($poprow[0]) ."</a></p>";
}
}

if ($searchrow[14] > 0 AND $searchrow[12] == "No")
{
echo "<p class=\"p_layout\">";
if ($Limited_Qty != "")
echo "$Limited_Qty";
if ($show_item_inv == "yes" AND $searchrow[14] <= $inventorylimit)
echo "$searchrow[14] in stock";
echo "</p>";
}

// Inventory Check Start
if ($err == "y" AND $itm == $searchrow[0])
{
echo "<p class=\"p_layout\">";
echo "<span class=\"salecolor\">";
if ($inv == 1)
echo "Sorry, there is ";
else
echo "Sorry, there are ";
echo "<span class=\"boldtext\">only $inv</span> ";
if ($opt)
echo "<span class=\"boldtext\">$opt</span> ";
echo "available.</span></p>";
}
// Inventory Check End

echo "</td>";
echo "</tr>";

// Display discounting
if ($discprset != "")
{
echo "<tr>";
echo "<td valign=\"top\" align=\"left\" colspan=\"3\">";
echo "$discprset</td></tr>";
}

// Display sale price if item is on sale and no options selected price already
if ($searchrow[11] != 0 AND $priceset == "yes")
{
echo "<tr>";
echo "<td class=\"accent\" align=\"right\" valign=\"top\">Price:</td>";
echo "<td align=\"left\" valign=\"top\">";
if ($searchrow[10] != 0)
echo "Was $Currency$searchrow[10] ";
echo "<span class=\"salecolor\"> Sale! $Currency$unitprice</span>";
echo "</td>";
echo "</tr>";
}

// Display regular price if item is not on sale and no options selected price already
else if ($searchrow[10] > 0 AND $priceset == "yes")
{
echo "<tr>";
echo "<td class=\"accent\" align=\"right\" valign=\"top\">Price:</td>";
echo "<td align=\"left\" valign=\"top\">";
echo "$Currency$unitprice";
echo "</td>";
echo "</tr>";
}

// If item is out of stock, display out of stock message
if ($searchrow[12] == "Yes" OR ($searchrow[14] <= 0 AND $searchrow[14] != "" AND $inventorycheck == "Yes"))
{
echo "<tr>";
echo "<td class=\"accent\" align=\"center\" colspan=\"2\" nowrap>";
echo "Sorry, item is temporarily out of stock</td>";
echo "</tr>";
}

// Show quantity and ordering information
else
{
// Show options if they exist
if ($display_options AND $Show_Ord_Button == "Yes")
echo "$display_options";
// Show wholesale discounts if they exist
if ($wspass AND $wsemail AND $wsnum == 1 AND ($wsoverride > 0 OR $searchrow[19] > 0))
{
echo "<tr><td valign=\"top\" align=\"right\" nowrap class=\"accent\">WS Disc:</td>";
echo "<td valign=\"top\" align=\"left\">";
if ($wsoverride > 0)
echo (float)$wsoverride ."%";
else
echo (float)$searchrow[19] ."%";
if ($searchrow[WSOnly] == "Yes")
echo " wholesalers only";
if ($wsoverride == 0 AND $searchrow[19] > 0)
echo "<input type=\"hidden\" name=\"wsdiscount\" value=\"$searchrow[19]\">";
echo "</td></tr>";
}
// Else show member discount if it exists
else if ($membercode AND $memdisc > 0)
{
echo "<tr><td valign=\"top\" align=\"right\" nowrap class=\"accent\">Member Disc:</td>";
echo "<td valign=\"top\" align=\"left\">";
echo (float)$memdisc ."%";
echo "</td></tr>";
}

// START ORDER DETAILS W/ BUTTON
if ($Show_Ord_Button == "Yes")
{
echo "<tr>";
echo "<td class=\"accent\" align=\"right\">Qty:</td>";
echo "<td align=\"left\">";
echo "<input type=\"hidden\" name=\"optnum\" value=\"$optnum\">";
echo "<input type=\"text\" name=\"qtty\" size=\"3\" value=\"1\">";
// Display custom order button if available
if ($Order_Button)
echo " <input type=\"image\" alt=\"Order\" src=\"$Order_Button\" name=\"Order\" border=\"0\" align=\"middle\">";
else
echo " <input type=\"submit\" value=\"Order\" name=\"Order\" class=\"formbutton\">";
// Display custom wish List button if available
if ($Registry AND $Registry != "none")
{
if ($Registry_Button)
echo " <input type=\"image\" alt=\"Add to Wish List\" src=\"$Registry_Button\" name=\"Registry\" border=\"0\" align=\"middle\">";
else if ($Registry AND $Registry != "none")
echo " <input type=\"submit\" value=\"Add to Wish List\" name=\"Registry\" class=\"formbutton\">";
}
echo "</td>";
echo "</tr>";
}
// END ORDER DETAILS W/ BUTTON
}

// Email to a friend link
if ($Email_Link != "" AND (($wspass AND $wsemail AND $wsnum == 1 AND $searchrow[19] == 0) OR (!$wspass AND !$wsemail AND $wsnum != 1)))
{
echo "<tr>";
echo "<td class=\"accent\" align=\"center\" colspan=\"2\">";
echo "<a href=\"go/email.php?item=$searchrow[0]\" target=\"_blank\" onClick=\"PopUp=window.open('go/email.php?item=$searchrow[0]', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\" class=\"emailcolor\">";
echo "<img border=\"0\" src=\"$URL/$Inc_Dir/envelope.gif\" alt=\"" .stripslashes($Email_Link) ."\" width=\"20\" height=\"12\"></a>";
echo "&nbsp;<a href=\"go/email.php?item=$searchrow[0]\" target=\"_blank\" onClick=\"PopUp=window.open('go/email.php?item=$searchrow[0]', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\">";
echo stripslashes($Email_Link) ."</a>";
echo "</td>";
echo "</tr>";
}

if ($relnum != 0)
{
echo "<tr>";
echo "<td colspan=\"2\" align=\"center\" valign=\"top\" class=\"accent\" width=\"100%\">";
echo "$relatedmsg";
if ($relatedimages == "Yes")
{
echo "<div align=\"center\">";
echo "<table border=\"0\">";
echo "<tr>";
while ($relrow = mysql_fetch_array($relresult))
{
$relitem = stripslashes($relrow[1]);
if (substr($relrow[2], 0, 7) == "http://")
$relimg = "$relrow[2]";
else
$relimg = "$URL/$relrow[2]";
echo "<td align=\"center\" valign=\"top\">";
echo "<a href=\"$Catalog_Page?item=$relrow[0]\" class=\"relatedcolor\">";
echo "<img src=\"$relimg\" alt=\"$relitem\" ";
if ($imgwidth > 0)
echo "width=\"$imgwidth\" ";
if ($imgheight > 0)
echo "height=\"$imgheight\" ";
echo "border=\"0\"><br>";
echo "$relitem</a>";
echo "</td>";
}
echo "</tr>";
echo "</table>";
echo "</div>";
}
else
{
echo "<ul style=\"margin-top: 0; margin-bottom: 0\">";
while ($relrow = mysql_fetch_array($relresult))
{
$relitem = stripslashes($relrow[1]);
echo "<li><a href=\"$Catalog_Page?item=$relrow[0]\" class=\"relatedcolor\">$relitem</a></li>";
}
echo "</ul>";
}
echo "</td>";
echo "</tr>";
}

echo "</table>";
echo "</form>";
}
echo "";
if ($bottr == 0)
echo "";
}

// Finish off table if needed
if ($Item_Columns > 0)
{
$remaining = $Item_Columns - (($scount-1) % $Item_Columns);
if ($remaining > 0 AND $remaining < $Item_Columns)
{
if ($scount-1 < $Item_Columns)
echo "";
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