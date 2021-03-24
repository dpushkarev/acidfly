<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

$priceset = "yes";
// If user is logged in
if ($regnum == 1)
    $registry_id = $regrow[0];
else if ($usrnum == 1 AND $rgid)
    $registry_id = $rgid;
// Show list
if ($registry_id) {
// Get wholesale info if needed
    if ($wsmsg AND $wspass AND $wsemail AND $wsrow[1] > 0)
        echo "$wsmsg";
// Get member code info if needed and no wholesale
    else if ($memmsg AND $membercode AND $memdisc > 0)
        echo "$memmsg";

// SHOW THE REGISTRANT INFORMATION
    if ($usrnum == 1 AND $rgid) {
        $usrrow = mysqli_fetch_array($usrresult);
        if ($usrrow[EventDate] != 0) {
            $splitdate = explode("-", $usrrow[EventDate]);
            $event_date = date("n/j/y", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
        }
        $name1 = stripslashes($usrrow[RegName1]);
        echo "<p align=\"center\" class=\"boldtext\">";
        echo ucfirst($Registry);
        echo " Registry For ";
        if ($usrrow[RegName2] AND $Registry == "bridal")
            echo "$name1 & " . stripslashes($usrrow[RegName2]);
        else if ($usrrow[RegName2] AND $Registry == "baby")
            echo stripslashes($usrrow[RegName2]) . " ($name1)";
        else
            echo "$name1";
        if ($Registry == "bridal" AND $event_date)
            echo "<br>Wedding Date: $event_date";
        else if ($Registry == "baby" AND $event_date)
            echo "<br>Due Date: $event_date";
        else if ($event_date)
            echo "<br>Event Date: $event_date";
        echo "</p>";
        if ($usrrow[ShipToCity] AND ($usrrow[ShipToState] OR $usrrow[ShipToCountry])) {
            $shipto = stripslashes($usrrow[ShipToName]);
            $address = stripslashes($usrrow[ShipToAddress]);
            $city = stripslashes($usrrow[ShipToCity]);
            $state = stripslashes($usrrow[ShipToState]);
            if ($usrrow[ShipToCountry] != "United States")
                $country = stripslashes($usrrow[ShipToCountry]);
            $zip = $usrrow[ShipToZip];
            echo "<p align=\"center\">";
            echo "<span class=\"accent\">To ship items directly, use the address:</span>";
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

    $limit = $Item_Rows;
    if (!$page) {
        $page = 1;
        $offset = 0;
    } else
        $offset = (($limit * $page) - $limit);
    $offset = ($page - 1) * $limit;

    $showquery = "SELECT * FROM " . $DB_Prefix . "_reglist, " . $DB_Prefix . "_items WHERE " . $DB_Prefix . "_reglist.ProductID=" . $DB_Prefix . "_items.ID ";
    $showquery .= "AND " . $DB_Prefix . "_reglist.RegistryID='$registry_id'";
    $totalquery = $showquery;
    $showquery .= " LIMIT $offset, $limit";
    $showresult = mysqli_query($dblink, $showquery) or die ("Unable to access this page of records.");
    $totalresult = mysqli_query($dblink, $totalquery) or die ("Unable to access total records.");
    $shownum = mysqli_num_rows($totalresult);

    if ($shownum == 0) {
        if ($regnum == 1) {
            echo "<p>There are no items currently in your registry. Search through our catalog to find items ";
            echo "you would like to add to your product list. Select the options desired, as well as the quantity ";
            echo "you would like to receive, then click the \"Add to Registry\" button.</p>";
        } else
            echo "<p>There are no items currently in this registry. Please try back again later.</p>";
    } else {
        if ($shownum % $limit == 0)
            $page_count = ($shownum - ($shownum % $limit)) / $limit;
        else
            $page_count = ($shownum - ($shownum % $limit)) / $limit + 1;
        if (!$regnum)
            $addl = "&rgid=$rgid&rguser=$rguser";
// Display pages
        if ($page_count > 1) {
            $prev = $page - 1;
            $next = $page + 1;
            echo "<p align=\"center\">";
            if ($page > 1)
                echo "<a href=\"registry.$pageext?page=1$addl\"><<</a> | <a href=\"registry.$pageext?page=$prev$addl\"><</a> | ";
            else
                echo "<< | < | ";
            echo "Page <b>$page</b> of <b>$page_count</b>";
            if ($page < $page_count)
                echo " | <a href=\"registry.$pageext?page=$next$addl\">></a> | <a href=\"registry.$pageext?page=$page_count$addl\">>></a>";
            else
                echo " | > | >>";
            echo "</p>";
        }

        echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">";
        echo "<tr class=\"accent\">";
        echo "<td></td>";
        echo "<td width=\"100%\"><u>Item</u></td>";
        echo "<td align=\"center\"><u>Wants</u></td>";
        echo "<td align=\"center\"><u>Rec'd</u></td>";
        echo "<td>&nbsp;</td>";
        echo "</tr>";
// <td>
        for ($sl = 1; $showrow = mysqli_fetch_array($showresult); ++$sl) {
            echo "<form action=go/order.php method=\"POST\">";
            echo "<input type=\"hidden\" name=\"pgnm_noext\" value=\"$pgnm_noext\">";
            echo "<input type=\"hidden\" name=\"pageext\" value=\"php\">";
            echo "<input type=\"hidden\" name=\"return\" value=\"$Return_URL\">";
            echo "<input type=\"hidden\" name=\"numofitems\" value=\"$shownum\">";
            echo "<input type=\"hidden\" name=\"rgnum\" value=\"$registry_id\">";
            echo "<tr>";
            echo "<td valign=\"top\" align=\"center\">";
            echo "<a name=\"$showrow[ID]\"></a>";
            if ($showrow[SmImage]) {
                if (substr($showrow[SmImage], 0, 7) == "http://")
                    $imgname = "$showrow[SmImage]";
                else
                    $imgname = "$URL/$showrow[SmImage]";
                echo "<img src=\"$imgname\" alt=\"" . stripslashes($showrow[Item]) . "\" ";
                if ($imgwidth > 0)
                    echo "width=\"$imgwidth\" ";
                if ($imgheight > 0)
                    echo "height=\"$imgheight\" ";
                echo "border=\"0\">";
            }
            echo "</td>";
            echo "<td width=\"100%\" valign=\"top\">";
// Set return link
            $w_ret = $setpg;
            if ($_SERVER['QUERY_STRING'])
                $w_ret .= "?" . $_SERVER['QUERY_STRING'];
            $w_ret = urlencode($w_ret);
            echo "<a href=\"$Catalog_Page?item=$showrow[ProductID]&ret=$w_ret\" class=\"itemcolor\">";
            if ($showrow[Catalog])
                echo "#$showrow[Catalog]. ";
            echo stripslashes($showrow[Item]);
            echo "</a>";
// Show Options
            $optlist = "";
            if ($showrow[Options])
                $expopts = explode(", ", $showrow[Options]);
// Set main item price
            if ($showrow[SalePrice] != 0)
                $unitprice = $showrow[SalePrice];
            else
                $unitprice = $showrow[RegPrice];

            if ($showrow[Catalog])
                $unitinfo = " #$showrow[Catalog]";
            else
                $unitinfo = "";

// Show product discount message
            if ($showrow[DiscountPr]) {
                $discprset = "";
                $discountinfo = explode("~", $showrow[DiscountPr]);
                for ($dc = 0; $dc < count($discountinfo); ++$dc) {
                    $discinfo = explode(",", $discountinfo[$dc]);
                    if ($dc > 0)
                        $discprset .= "<br>";
                    $discprset .= "Buy $discinfo[0] or more and receive ";
                    if ($discinfo[2] == "A")
                        $discprset .= $Currency . number_format($discinfo[1], 2, '.', '') . " off";
                    else
                        $discprset .= "a $discinfo[1]% discount";
                }
                $discprset .= "<input type=\"hidden\" name=\"discountpr\" value=\"$showrow[DiscountPr]\">";
            } else
                $discprset = "";
            $stripitem = stripslashes($showrow[Item]);
            echo "<input type=\"hidden\" name=\"productid\" value=\"$showrow[ProductID]\">";
            echo "<input type=\"hidden\" name=\"product\" value=\"$stripitem\">";
            echo "<input type=\"hidden\" name=\"catalog\" value=\"$unitinfo\">";
            echo "<input type=\"hidden\" name=\"price\" value=\"$unitprice\">";
            echo "<input type=\"hidden\" name=\"units\" value=\"$showrow[Units]\">";
            echo "<input type=\"hidden\" name=\"tax\" value=\"$showrow[TaxPercent]\">";
            echo "<input type=\"hidden\" name=\"regid\" value=\"$showrow[0]\">";
            echo "<input type=\"hidden\" name=\"rgid\" value=\"$rgid\">";
            echo "<input type=\"hidden\" name=\"rguser\" value=\"$rguser\">";

// START OPTION DISPLAY
            $display_options = "";
// Display options if they exist
            $optquery = "SELECT * FROM " . $DB_Prefix . "_options WHERE ItemID = '$showrow[ProductID]' ";
            $optquery .= "AND Active <> 'No' ORDER BY OptionNum";
            $optresult = mysqli_query($dblink, $optquery) or die ("Unable to access database.");
            $optnum = mysqli_num_rows($optresult);
            echo "<input type=\"hidden\" name=\"optnum\" value=\"$optnum\">";
            if ($optnum != 0) {
                for ($optcount = 1; $optrow = mysqli_fetch_row($optresult); ++$optcount) {
                    $optionname = stripslashes($optrow[3]);
                    $attributes = stripslashes($optrow[5]);
                    $attributes = str_replace("\"", "&quot;", $attributes);
                    $display_options .= "<br>$optionname: ";
                    $display_options .= "<input type=\"hidden\" name=\"opttype$optcount\" value=\"$optrow[4]\">";
                    $display_options .= "<input type=\"hidden\" name=\"optname$optcount\" value=\"$optionname\">";
                    $product_id = $showrow[ProductID];
                    include("$Inc_Dir/products_options.php");
                }
            }
// END OPTION DISPLAY

// Display sale price if item is on sale and no options selected price already
            if ($showrow[SalePrice] != 0 AND $priceset == "yes") {
                echo "<br>";
                if ($showrow[RegPrice] != 0)
                    echo "Was $Currency$showrow[RegPrice] ";
                echo "<span class=\"salecolor\"> Sale! $Currency$unitprice</span>";
                if ($discprset != "")
                    echo "<br>$discprset";
            } // Display regular price if item is not on sale and no options selected price already
            else if ($showrow[RegPrice] > 0 AND $priceset == "yes") {
                echo "<br>$Currency$unitprice";
                if ($discprset != "")
                    echo "<br>$discprset";
            }

            if ($display_options != "")
                echo "$display_options";

// If item is out of stock, display out of stock message
            if ($showrow[OutOfStock] == "Yes" OR ($showrow[Inventory] <= 0 AND $showrow[Inventory] != "" AND $inventorycheck == "Yes"))
                $show_qty = "no";
            else
                $show_qty = "yes";
// Show edit/delete buttons if owner
            if ($regnum == 1) {
                echo "<br>(<a href=\"registry.$pageext?pid=$showrow[0]&md=iw&page=$page\">Increase</a> | ";
                echo "<a href=\"registry.$pageext?pid=$showrow[0]&md=dw&page=$page\">Decrease</a> | ";
                echo "<a href=\"registry.$pageext?pid=$showrow[0]&md=d\">Remove</a>)";
            }
            echo "<br>&nbsp;";
            echo "</td>";
            echo "<td valign=\"top\" align=\"center\" class=\"boldtext\">";
            echo (float)$showrow[QtyWanted];
            echo "</td>";
            echo "<td valign=\"top\" align=\"center\" class=\"boldtext\">";
            echo (float)$showrow[QtyReceived];
            echo "</td>";
            echo "<td valign=\"top\" align=\"center\" nowrap>";
            if ($showrow[QtyReceived] < $showrow[QtyWanted] AND $show_qty == "yes") {
                echo "<input type=\"text\" name=\"qtty\" value=\"1\" size=\"2\"> ";
                echo "<input type=\"submit\" value=\"Order\" name=\"submit\" class=\"formbutton\">";
            } else if ($showrow[QtyReceived] >= $showrow[QtyWanted])
                echo "<span class=\"accent\">Fulfilled</span>";
            else if ($show_qty == "no")
                echo "<span class=\"accent\">Out of Stock</span>";
            echo "</td>";
            echo "</tr>";

            echo "</form>";
        }
        echo "</table>";
    }
} // INFO DOES NOT MATCH
else
    echo "<p align=\"center\">Sorry, but this registrant could not be found. Please try again later.</p>";
if ($regnum == 1) {
    echo "<p align=\"center\">";
    echo "<a href=\"registry.$pageext?mode=update\">Contact Info</a> | ";
    echo "<a href=\"registry.$pageext?mode=viewlink\">View Link</a> | ";
    echo "<a href=\"registry.$pageext?mode=delreg\">Delete Registry</a> | ";
    echo "<a href=\"registry.$pageext?logout=yes\">Log Out</a></p>";
}
?>