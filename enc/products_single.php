<?php
$srcount = 1;
// Start table
echo "<table border=\"0\" cellpadding=\"3\" align=\"center\" cellspacing=\"0\" width=\"100%\">";
// Display line above products if selected
if ($Product_Line)
echo "<tr><td colspan=\"3\" align=\"center\" width=\"100%\"><hr class=\"linecolor\"></td></tr>";
while ($searchrow = mysqli_fetch_array($searchresult))
{
$priceset = "yes";
$stripitem = stripslashes($searchrow[2]);
$stripitem = str_replace("\"", "&quot;", $stripitem);
$stripdescription = stripslashes($searchrow[3]);

$relquery = "SELECT " .$DB_Prefix ."_related.relatedID, " .$DB_Prefix ."_items.Item, " .$DB_Prefix ."_items.SmImage FROM " .$DB_Prefix ."_related, " .$DB_Prefix ."_items WHERE ";
$relquery .= $DB_Prefix ."_related.relatedID = " .$DB_Prefix ."_items.ID AND " .$DB_Prefix ."_related.ProductID = '$searchrow[0]'";
$relresult = mysqli_query($dblink, $relquery) or die ("Unable to access database.");
$relnum = mysqli_num_rows($relresult);

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
$optresult = mysqli_query($dblink, $optquery) or die ("Unable to access database.");
$optnum = mysqli_num_rows($optresult);
$chkresult = mysqli_query($dblink, $chkquery) or die ("Unable to access database.");
$chknum = mysqli_num_rows($chkresult);
$display_options = "";
if ($optnum != 0)
{
for ($optcount = 1; $optrow = mysqli_fetch_array($optresult); ++$optcount)
{
$optionname = stripslashes($optrow[3]);
$attributes = stripslashes($optrow[5]);
$attributes = str_replace("\"", "&quot;", $attributes);
$display_options .= "<tr>";
$display_options .= "<td valign=\"top\" align=\"right\" class=\"accent\">";
$display_options .= "$optionname:</td>";
$display_options .= "<td valign=\"top\" align=\"left\" width=\"100%\">";
$display_options .= "<input type=\"hidden\" name=\"opttype$optcount\" value=\"$optrow[4]\">";
$display_options .= "<input type=\"hidden\" name=\"optname$optcount\" value=\"$optionname\">";
$product_id = $searchrow[0];
include ("$Inc_Dir/products_options.php");
$display_options .= "</td>";
$display_options .= "</tr>";
}
}
// END OPTION DISPLAY

// Set row span for image cell
$rowspan = 1;
if ($discprset != "")
++ $rowspan;
if ($searchrow[11] != 0 AND $priceset == "yes")
++ $rowspan;
else if ($searchrow[10] > 0 AND $priceset == "yes")
++ $rowspan;
if ($searchrow[12] == "Yes" OR ($searchrow[14] <= 0 AND $searchrow[14] != "" AND $inventorycheck == "Yes"))
++ $rowspan;
else
{
if ($display_options AND $Show_Ord_Button == "Yes")
$rowspan = $rowspan + $optnum;
if ($wspass AND $wsemail AND $wsnum == 1 AND ($wsoverride > 0 OR $searchrow[19] > 0))
++ $rowspan;
else if ($membercode AND $memdisc > 0)
++ $rowspan;
if ($Show_Ord_Button == "Yes")
++ $rowspan;
}
if ($Email_Link != "" AND (($wspass AND $wsemail AND $wsnum == 1 AND $searchrow[19] == 0) OR (!$wspass AND !$wsemail AND $wsnum != 1)))
++ $rowspan;
if ($relnum != 0)
++ $rowspan;

// Set cart variables
echo "<form method=\"POST\" action=\"$URL/go/order.php\">";
echo "<input type=\"hidden\" name=\"pgnm_noext\" value=\"$pgnm_noext\">";
echo "<input type=\"hidden\" name=\"pageext\" value=\"php\">";
echo "<input type=\"hidden\" name=\"productid\" value=\"$searchrow[0]\">";
echo "<input type=\"hidden\" name=\"Clickurn\" value=\"$Return_URL\">";
echo "<input type=\"hidden\" name=\"product\" value=\"$stripitem\">";
echo "<input type=\"hidden\" name=\"catalog\" value=\"$unitinfo\">";
echo "<input type=\"hidden\" name=\"price\" value=\"$unitprice\">";
echo "<input type=\"hidden\" name=\"units\" value=\"$searchrow[9]\">";
echo "<input type=\"hidden\" name=\"tax\" value=\"$searchrow[23]\">";
// Start row
echo "<tr>";
echo "<td width=\"40%\" valign=\"top\" align=\"center\" rowspan=\"$rowspan\">";
echo "<a name=\"$searchrow[0]\"></a>";


// If there is a small image, display it
if ($searchrow[SmImage])
{
echo "<a href=\"$Catalog_Page?item=$searchrow[0]";
if ($category)
echo "&catid=$category";
if ($new == "yes")
echo "&new=yes";
if ($all == "yes")
echo "&all=yes";
if ($sale == "yes")
echo "&sale=yes";
if ($category)
echo "&ret=$catpg%3Fcategory%3D$category";
echo "\">";
if (substr($searchrow[4], 0, 7) == "http://")
$imgname = "$searchrow[4]";
else
$imgname = "$URL/$searchrow[4]";
echo "<img src=\"$imgname\" alt=\"$stripitem\" ";
if ($imgwidth > 0)
echo "width=\"$imgwidth\" ";
if ($imgheight > 0)
echo "height=\"$imgheight\" ";
echo "border=\"0\" ></a>";
}
// If there is a second small image, display it
if ($searchrow[SmImage2])
{
echo "<br>";
if (substr($searchrow[SmImage2], 0, 7) == "http://")
$imgname2 = "$searchrow[SmImage2]";
else
$imgname2 = "$URL/$searchrow[SmImage2]";
$smimg2 = "<img src=\"$imgname2\" alt=\"$stripitem\" ";
if ($imgwidth > 0)
$smimg2 .= "width=\"$imgwidth\" ";
if ($imgheight > 0)
$smimg2 .= "height=\"$imgheight\" ";
$smimg2 .= "border=\"1\">";
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
}

// If there is a third small image, display it
if ($searchrow[SmImage3])
{
echo "<br>";
if (substr($searchrow[SmImage3], 0, 7) == "http://")
$imgname3 = "$searchrow[SmImage3]";
else
$imgname3 = "$URL/$searchrow[SmImage3]";
$smimg3 = "<img src=\"$imgname3\" alt=\"$stripitem\" ";
if ($imgwidth > 0)
$smimg3 .= "width=\"$imgwidth\" ";
if ($imgheight > 0)
$smimg3 .= "height=\"$imgheight\" ";
$smimg3 .= "border=\"1\">";
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
}

if (($searchrow[SmImage] AND $searchrow[LgImage]) OR ($searchrow[SmImage2] AND $searchrow[LgImage2]) OR ($searchrow[SmImage3] AND $searchrow[LgImage3]))
echo "<br><span class=\"bold\">Click for Detail</span>";

echo "</td>";
echo "<td valign=\"top\" align=\"left\" colspan=\"2\" width=\"100%\">";

echo "<span class=\"title\">";
// If there is a catalog number, display it
if ($searchrow[1])
echo "#$searchrow[1]. ";
echo "$stripitem";
echo "</span>";

// Show pop up if it exists
if ($searchrow[24])
{
$popquery = "SELECT PageTitle FROM " .$DB_Prefix ."_popups WHERE ID='$searchrow[24]'";
$popresult = mysqli_query($dblink, $popquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($popresult) == 1)
{
$poprow = mysqli_fetch_array($popresult);
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
echo "<span class=\"sale\">";
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
echo "<td valign=\"top\" align=\"left\" colspan=\"3\" width=\"100%\">";
echo "$discprset</td></tr>";
}

// Display sale price if item is on sale and no options selected price already
if ($searchrow[11] != 0 AND $priceset == "yes")
{
echo "<tr>";
echo "<td class=\"accent\" align=\"right\" valign=\"top\">Price:</td>";
echo "<td align=\"left\" valign=\"top\" width=\"100%\">";
if ($searchrow[10] != 0)
echo "<span class=\"sale\">On Sale! Was $Currency$searchrow[10]</span> ";
echo "<span class=\"sale\"> Now $Currency$unitprice</span>";
echo "</td>";
echo "</tr>";
}

// Display regular price if item is not on sale and no options selected price already
else if ($searchrow[10] > 0 AND $priceset == "yes")
{
echo "<tr>";
echo "<td class=\"accent\" align=\"right\" valign=\"top\">Price:</td>";
echo "<td align=\"left\" valign=\"top\" width=\"100%\">";
echo "$Currency$unitprice";
echo "</td>";
echo "</tr>";
}

// If item is out of stock, display out of stock message
if ($searchrow[12] == "Yes" OR ($searchrow[14] <= 0 AND $searchrow[14] != "" AND $inventorycheck == "Yes"))
{
echo "<tr>";
echo "<td class=\"sale\" align=\"left\" colspan=\"2\" nowrap width=\"100%\">";
echo "Sorry, this item is sold out</td>";
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
echo "<tr><td valign=\"top\" align=\"right\" nowrap class=\"ws\">WS Discount:</td>";
echo "<td valign=\"top\" align=\"left\" width=\"100%\" class=\"ws\">";
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
echo "<td valign=\"top\" align=\"left\" width=\"100%\">";
echo (float)$memdisc ."%";
echo "</td></tr>";
}

// START ORDER DETAILS W/ BUTTON
if ($Show_Ord_Button == "Yes")
{
echo "<tr>";
echo "<td class=\"accent\" align=\"right\">Qty:</td>";
echo "<td align=\"left\" width=\"100%\">";
echo "<input type=\"hidden\" name=\"optnum\" value=\"$optnum\">";
echo "<input type=\"text\" name=\"qtty\" size=\"2\" value=\"1\">";
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
else if ($Registry AND $Registry != "yes")
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
echo "<td class=\"accent\" align=\"left\" colspan=\"2\" width=\"100%\">";
echo "<a href=\"go/email.php?item=$searchrow[0]\" target=\"_blank\" onClick=\"PopUp=window.open('go/email.php?item=$searchrow[0]', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\" class=\"emailcolor\">";
echo "<img border=\"0\" src=\"$URL/$Inc_Dir/envelope.gif\" alt=\"" .stripslashes($Email_Link) ."\" width=\"20\" height=\"12\"></a>";
echo "&nbsp;<a href=\"go/email.php?item=$searchrow[0]\" target=\"_blank\" onClick=\"PopUp=window.open('go/email.php?item=$searchrow[0]', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\">";
echo stripslashes($Email_Link) ."</a>";
echo "</td>";
echo "</tr>";
}

if ($relnum != 0)
{
if ($relatedimages == "Yes")
$relcolspan = 3;
else
$relcolspan = 2;
echo "<tr>";
echo "<td colspan=\"$relcolspan\" align=\"left\" valign=\"top\" class=\"accent\" width=\"100%\">";
echo "$relatedmsg";
if ($relatedimages == "Yes")
{
echo "<div align=\"center\">";
echo "<table border=\"0\">";
echo "<tr>";
while ($relrow = mysqli_fetch_array($relresult))
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
while ($relrow = mysqli_fetch_array($relresult))
{
$relitem = stripslashes($relrow[1]);
echo "<li><a href=\"$Catalog_Page?item=$relrow[0]\" class=\"relatedcolor\">$relitem</a></li>";
}
echo "</ul>";
}
echo "</td>";
echo "</tr>";
}

echo "<tr><td colspan=\"3\" width=\"100%\" align=\"center\">";
// Display line above products if selected
if ($Product_Line)
echo "<hr class=\"linecolor\">";
else
echo "&nbsp;";
if ($srcount < $Item_Rows)
echo "<img src=\"/bar.gif\">";
++$srcount;
echo "</td></tr>";
echo "</form>";
}

// End Table
echo "</table>";
?>