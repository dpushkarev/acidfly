<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Display one item
$searchrow = mysqli_fetch_array($searchresult);
$priceset = "yes";
$stripitem = stripslashes($searchrow[2]);
$stripitem = str_replace("\"", "&quot;", $stripitem);
$stripdescription = stripslashes($searchrow[3]);

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

// START OPTION SETUP
$show_item_inv = "yes";
$optquery = "SELECT * FROM " .$DB_Prefix ."_options WHERE ItemID = '$searchrow[0]' AND Active <> 'No' ORDER BY OptionNum";
$optresult = mysqli_query($dblink, $optquery) or die ("Unable to access database.");
$optnum = mysqli_num_rows($optresult);
if ($optnum != 0)
{
$display_options = "";
for ($optcount = 1; $optrow = mysqli_fetch_array($optresult); ++$optcount)
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
// END OPTION SETUP

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
if ($wspass AND $wsemail AND $wsnum == 1 AND ($wsoverride > 0 OR $searchrow[19] > 0))
echo "<input type=\"hidden\" name=\"wsdiscount\" value=\"$searchrow[19]\">";

echo "<a name=\"$searchrow[0]\"></a>";
echo "<div align=\"center\">";
echo "<center>";
echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";

// If there is a larger image, create image
if ($searchrow[LgImage])
$imgname = $searchrow[LgImage];
else if ($searchrow[SmImage])
$imgname = $searchrow[SmImage];
if ($imgname)
{
if (substr($imgname, 0, 7) != "http://")
$imgname = "$URL/$imgname";
echo "<tr><td align=\"center\" colspan=\"2\"><img border=\"0\" src=\"$imgname\" alt=\"$stripitem\"></td></tr>";
}

// If there is a second larger image, create image
if ($searchrow[LgImage2])
$imgname2 = $searchrow[LgImage2];
else if ($searchrow[SmImage2])
$imgname2 = $searchrow[SmImage2];
if ($imgname2)
{
if (substr($imgname2, 0, 7) != "http://")
$imgname2 = "$URL/$imgname2";
echo "<tr><td align=\"center\" colspan=\"2\"><img border=\"0\" src=\"$imgname2\" alt=\"$stripitem\"></td></tr>";
}

// If there is a third larger image, create image
if ($searchrow[LgImage3])
$imgname3 = $searchrow[LgImage3];
else if ($searchrow[SmImage3])
$imgname3 = $searchrow[SmImage3];
if ($imgname3)
{
if (substr($imgname3, 0, 7) != "http://")
$imgname3 = "$URL/$imgname3";
echo "<tr><td align=\"center\" colspan=\"2\"><img border=\"0\" src=\"$imgname3\" alt=\"$stripitem\"></td></tr>";
}

// Display title information 
echo "<tr><td align=\"center\" colspan=\"2\" class=\"title\">";
// If there is a catalog number, display it
if ($searchrow[1])
echo "#$searchrow[1]. ";
echo "$stripitem</td></tr>";

if ($searchrow[3])
{
echo "<tr><td align=\"left\" colspan=\"2\">";
if (strstr($searchrow[3], '<') == FALSE AND strstr($searchrow[3], '>') == FALSE)
{
$stripdescription = str_replace ("\r", "<br>", $stripdescription);
echo "$stripdescription";
}
else
echo "$stripdescription";
echo "</td></tr>";
}

// Show pop up if it exists
if ($searchrow[24])
{
$popquery = "SELECT PageTitle FROM " .$DB_Prefix ."_popups WHERE ID='$searchrow[24]'";
$popresult = mysqli_query($dblink, $popquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($popresult) == 1)
{
$poprow = mysqli_fetch_array($popresult);
echo "<tr><td align=\"left\" colspan=\"2\">";
echo "<a href=\"go/popup.php?pgid=$searchrow[24]\" target=\"_blank\" onClick=\"PopUp=window.open('go/popup.php?pgid=$searchrow[24]', 'NewWin', 'resizable=yes,width=425,height=500,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\" class=\"popupcolor\">";
echo stripslashes($poprow[0]) ."</a>";
echo "</td></tr>";
}
}

if ($searchrow[14] > 0 AND $searchrow[12] == "No")
{
echo "<tr><td align=\"center\" colspan=\"2\" class=\"accent\">";
if ($Limited_Qty != "")
echo "$Limited_Qty";
if ($show_item_inv == "yes" AND $searchrow[14] <= $inventorylimit)
echo "$searchrow[14] in stock";
echo "</td></tr>";
}

// Inventory Check Start
if ($err == "y" AND $itm == $searchrow[0])
{
echo "<tr><td align=\"center\" colspan=\"2\" class=\"salecolor\">";
if ($inv == 1)
echo "Sorry, there is ";
else
echo "Sorry, there are ";
echo "only $inv ";
if ($opt)
echo "$opt ";
echo "available.";
echo "</td>";
echo "</tr>";
}
// Inventory Check End

// Display discounting
if ($discprset != "")
{
echo "<tr>";
echo "<td valign=\"top\" align=\"left\" colspan=\"3\">";
echo "$discprset</td></tr>";
}

// If item is on sale, display sale price
if ($searchrow[11] != 0 AND $priceset == "yes")
{
echo "<tr><td valign=\"top\" align=\"right\" class=\"accent\">Price:</td>";
echo "<td valign=\"top\" align=\"left\">";
if ($searchrow[10] != 0)
echo "Was&nbsp;$Currency$searchrow[10] ";
echo "<span class=\"salecolor\">Sale!&nbsp;$Currency$unitprice</span>";
echo "</td></tr>";
}
// Display regular price if item is not on sale and no options selected price already
else if ($searchrow[10] > 0 AND $priceset == "yes")
{
echo "<tr><td valign=\"top\" align=\"right\" class=\"accent\">Price:</td>";
echo "<td valign=\"top\" align=\"left\">";
echo "$Currency$unitprice";
echo "</td></tr>";
}

// If item is out of stock, display out of stock message
if ($searchrow[12] == "Yes" OR ($searchrow[14] <= 0 AND $searchrow[14] != "" AND $inventorycheck == "Yes"))
{
echo "<tr><td valign=\"top\" align=\"center\" colspan=\"2\" class=\"accent\">";
echo "Sorry, item is temporarily out of stock";
echo "</td></tr>";
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
echo "<tr><td valign=\"top\" align=\"right\"><span class=\"accent\">WS Disc:</span></td>";
echo "<td valign=\"top\" align=\"left\">";
if ($wsoverride > 0)
echo (float)$wsoverride ."%";
else
echo (float)$searchrow[19] ."%";
if ($searchrow[WSOnly] == "Yes")
echo " wholesale vendors only";
echo "</td></tr>";
}
// Else show member discount if it exists
else if ($membercode AND $memdisc > 0)
{
echo "<tr><td valign=\"top\" align=\"right\"><span class=\"accent\">Member Disc:</span></td>";
echo "<td valign=\"top\" align=\"left\">";
echo (float)$memdisc ."%";
echo "</td></tr>";
}

// START ORDER DETAILS W/ BUTTON
if ($Show_Ord_Button == "Yes")
{
// Quantity
echo "<tr><td valign=\"top\" align=\"right\"><span class=\"accent\">Qty:</span></td>";
echo "<td valign=\"top\" align=\"left\">";
echo "<input type=\"hidden\" name=\"optnum\" value=\"$optnum\">";
echo "<input type=\"text\" name=\"qtty\" size=\"3\" value=\"1\">&nbsp;&nbsp;&nbsp;";
// Display custom order button if available
if ($Order_Button)
echo "<input type=\"image\" alt=\"Order\" src=\"$Order_Button\" name=\"Order\" border=\"0\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Order\" name=\"Order\" class=\"formbutton\">";
// Display custom wish List button if available
if ($Registry AND $Registry != "none")
{
if ($Registry_Button)
echo " <input type=\"image\" alt=\"Add to Registry\" src=\"$Registry_Button\" name=\"Registry\" border=\"0\" align=\"middle\">";
else if ($Registry AND $Registry != "none")
echo " <input type=\"submit\" value=\"Add to Registry\" name=\"Registry\" class=\"formbutton\">";
}
echo "</td></tr>";
}
// END ORDER DETAILS W/ BUTTON
}

echo "</table>";
echo "</center>";
echo "</div>";
echo "</form>";

// Email to a friend link
if ($Email_Link != "" AND (($wspass AND $wsemail AND $wsnum == 1 AND $searchrow[19] == 0) OR (!$wspass AND !$wsemail AND $wsnum != 1)))
{
echo "<p align=\"center\">";
echo "<a href=\"go/email.php?item=$searchrow[0]\" target=\"_blank\" onClick=\"PopUp=window.open('go/email.php?item=$searchrow[0]', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\">";
echo "<img border=\"0\" src=\"$URL/$Inc_Dir/envelope.gif\" alt=\"" .stripslashes($Email_Link) ."\" width=\"20\" height=\"12\"></a>";
echo "&nbsp;<a href=\"go/email.php?item=$searchrow[0]\" target=\"_blank\" onClick=\"PopUp=window.open('go/email.php?item=$searchrow[0]', 'NewWin', 'resizable=yes,width=400,height=330,left=25,top=25,screenX=25,screenY=25'); PopUp.focus(); return false;\" class=\"emailcolor\">";
echo stripslashes($Email_Link) ."</a></p>";
}

$relquery = "SELECT " .$DB_Prefix ."_related.RelatedID, " .$DB_Prefix ."_items.Item, " .$DB_Prefix ."_items.SmImage FROM " .$DB_Prefix ."_related, " .$DB_Prefix ."_items WHERE ";
$relquery .= $DB_Prefix ."_related.RelatedID = " .$DB_Prefix ."_items.ID AND " .$DB_Prefix ."_related.ProductID = '$searchrow[0]'";
$relresult = mysqli_query($dblink, $relquery) or die ("Unable to access database.");
$relnum = mysqli_num_rows($relresult);

if ($relnum != 0)
{
echo "<p class=\"accent\" align=\"center\">$relatedmsg</p>";
echo "<div align=\"center\">";
echo "<table border=\"0\">";
echo "<tr>";
if ($relatedimages == "Yes")
{
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
}
else
{
echo "<td valign=\"top\">";
echo "<ul style=\"margin-top: 0; margin-bottom: 0\">";
while ($relrow = mysqli_fetch_array($relresult))
{
$relitem = stripslashes($relrow[1]);
echo "<li><a href=\"$Catalog_Page?item=$relrow[0]\" class=\"relatedcolor\">$relitem</a></li>";
}
echo "</ul>";
echo "</td>";
}
echo "</tr>";
echo "</table>";
echo "</div>";
}

if ($ret)
{
$ret_pg = "$URL/$ret";
echo "<p align=\"center\">";
echo "<a href=\"$ret_pg\" class=\"itemcolor\">";
if (strpos($ret, "regid") == true AND strpos($ret, "rguser") == true)
echo "Return to Registry";
else
echo "<~ Return to Catalog";
echo "</a></p>";
}
?>