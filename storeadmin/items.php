<script language="php">
include("includes/open.php");
if ($admin_submit AND $itemid)
{
$itemquery = "SELECT Item FROM " .$DB_Prefix ."_items WHERE ID='$itemid'";
$itemresult = mysqli_query($dblink, $itemquery) or die ("Unable to view item.");
$itemrow = mysqli_fetch_row($itemresult);
$itemname = stripslashes($itemrow[0]);
$itemname = str_replace(" ", "&nbsp;", $itemname);
if ($admin_submit == "Options")
$gotopage = "options.php?itemid=$itemid&itemname=$itemname";
else if ($admin_submit == "Inventory")
$gotopage = "related.php?itemid=$itemid&itemname=$itemname";
header ("location: $gotopage");
}

</script>
<html>

<head>
    <title>Administration</title>
    <link rel="stylesheet" type="text/css" href="includes/style.css">
    <script language="php">include("includes/htmlarea.php");</script>
</head>

<body
<script language="php">$java = "description"; include("includes/htmlareabody.php");</script>
>
<?php
include("includes/header.htm");
include("includes/links.php");

if ($Submit == "Yes, Delete Item") {
    $delitemquery = "DELETE FROM " . $DB_Prefix . "_items WHERE ID = '$itemid'";
    $delitemresult = mysqli_query($dblink, $delitemquery) or die("Unable to delete this item. Please try again later.");
    $deloptquery = "DELETE FROM " . $DB_Prefix . "_options WHERE ItemID = '$itemid'";
    $deloptresult = mysqli_query($dblink, $deloptquery) or die("Unable to delete this item. Please try again later.");
    $delinvquery = "DELETE FROM " . $DB_Prefix . "_inventory WHERE ProductID = '$itemid'";
    $delinvresult = mysqli_query($dblink, $delinvquery) or die("Unable to delete this item. Please try again later.");
    $delrelquery = "DELETE FROM " . $DB_Prefix . "_related WHERE ProductID = '$itemid' OR RelatedID = '$itemid'";
    $delrelresult = mysqli_query($dblink, $delrelquery) or die("Unable to delete this item. Please try again later.");
    $delregquery = "DELETE FROM " . $DB_Prefix . "_reglist WHERE ProductID = '$itemid'";
    $delregresult = mysqli_query($dblink, $delregquery) or die("Unable to delete this item. Please try again later.");
}

if ($action == "continue") {
// Delete old related and add new ones
    $delrelquery = "DELETE FROM " . $DB_Prefix . "_related WHERE ProductID='$itemid'";
    $delrelresult = mysqli_query($dblink, $delrelquery) or die("Unable to edit your options. Please try again later.");
    for ($count = 1; $count <= 5; ++$count) {
        if ($item[$count] != 0) {
            $addrelquery = "INSERT INTO " . $DB_Prefix . "_related (ProductID, RelatedID) VALUES ('$itemid', '$item[$count]')";
            $addrelresult = mysqli_query($dblink, $addrelquery) or die("Unable to edit your options. Please try again later.");
        }
    }

// Delete all inventory records in preparation of entering new ones
    $delattquery = "DELETE FROM " . $DB_Prefix . "_inventory WHERE ProductID='$itemid'";
    $delattresult = mysqli_query($dblink, $delattquery) or die("Unable to delete. Please try again later.");

// If no option inventories
    if ($optcount > 0) {
        $iteminventory = "";
        for ($i = 0; $i < $optcount; ++$i) {
            if ($inventory[$i] >= 0 AND $inventory[$i] != "") {
                $iteminventory = $iteminventory + $inventory[$i];
                $addinvquery = "INSERT INTO " . $DB_Prefix . "_inventory (ProductID, Attribute, Quantity) ";
                $addinvquery .= "VALUES ('$itemid', '$attribute[$i]', '$inventory[$i]')";
                $addinvresult = mysqli_query($dblink, $addinvquery) or die("Unable to edit your options. Please try again later.");
            }
        }
    }

// Update inventory for all records
    $updattquery = "UPDATE " . $DB_Prefix . "_items SET ";
    if ($iteminventory == "" AND $iteminventory != "0")
        $updattquery .= "Inventory=NULL ";
    else
        $updattquery .= "Inventory='$iteminventory' ";
    $updattquery .= "WHERE ID='$itemid'";
    $updattresult = mysqli_query($dblink, $updattquery) or die("Unable to update item inventory. Please try again later.");
}

// Start item display
$varquery = "SELECT URL, CatalogPage, Currency, ShowDiscountPr, ItemImages, ShowCosts, DefaultProduct FROM " . $DB_Prefix . "_vars WHERE ID=1";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select your system variables. Try again later.");
if (mysqli_num_rows($varresult) == 1) {
    $varrow = mysqli_fetch_row($varresult);
    $urllink = "http://" . $varrow[0];
    $CatalogURL = $urllink . "/" . $varrow[1];
    $currency = $varrow[2];
    $showdiscountpr = $varrow[3];
    $itemimages = $varrow[4];
    $showcosts = $varrow[5];
    $defprodid = $varrow[6];
}

if ($catsearch OR $kwsearch OR $limitview) {
    if ($catsearch) {
        $catsearch = stripslashes($catsearch);
        $catlink = stripslashes($catsearch);
        $catlink = urlencode($catlink);
    }
    if ($kwsearch) {
        $kwlink = stripslashes($kwsearch);
        $kwlink = urlencode($kwlink);
    }
}

if (($Submit == "Add") OR ($Submit == "Edit")) {
    if ($Submit == "Add") {
        $getitemquery = "SELECT * FROM " . $DB_Prefix . "_items WHERE ID = '$defprodid'";
        $getitemresult = mysqli_query($dblink, $getitemquery) or die ("Unable to select. Try again later.");
        if (mysqli_num_rows($getitemresult) == 1) {
            if ($Set == "Default") {
                $getitemrow = mysqli_fetch_array($getitemresult);
                $prodid = $getitemrow[ID];
                $catnum = $getitemrow[Catalog];
                $stripitem = str_replace('"', '&quot;', stripslashes($getitemrow[Item]));
                $stripmetatitle = str_replace('"', '&quot;', stripslashes($getitemrow[MetaTitle]));
                $stripdescrip = stripslashes($getitemrow[Description]);
                $stripkeywords = stripslashes($getitemrow[Keywords]);
                $smallimage = $getitemrow[SmImage];
                $largeimage = $getitemrow[LgImage];
                $smallimage2 = $getitemrow[SmImage2];
                $largeimage2 = $getitemrow[LgImage2];
                $smallimage3 = $getitemrow[SmImage3];
                $largeimage3 = $getitemrow[LgImage3];
                $cat1 = $getitemrow[Category1];
                $cat2 = $getitemrow[Category2];
                $cat3 = $getitemrow[Category3];
                $cat4 = $getitemrow[Category4];
                $cat5 = $getitemrow[Category5];
                $units = (float)$getitemrow[Units];
                $regularprice = $getitemrow[RegPrice];
                $saleprice = $getitemrow[SalePrice];
                $outofstock = $getitemrow[OutOfStock];
                $active = $getitemrow[Active];
                $discountpr = $getitemrow[DiscountPr];
                $wholesale = $getitemrow[Wholesale];
                $wsonly = $getitemrow[WSOnly];
                $featured = $getitemrow[Featured];
                $itemcost = $getitemrow[ItemCost];
                $taxpercent = $getitemrow[TaxPercent];
                $popuppg = $getitemrow[PopUpPg];
                echo "<p align=\"center\">";
                echo "<a href=\"items.php?Submit=Add";
                if ($catsearch OR $kwsearch OR $limitview)
                    echo "&itemid=$itemid&catsearch=$catsearch&kwsearch=$kwsearch&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc";
                echo "\">Remove Default</a></p>";
            } else {
                echo "<p align=\"center\">";
                echo "<a href=\"items.php?Submit=Add&Set=Default";
                if ($catsearch OR $kwsearch OR $limitview)
                    echo "&itemid=$itemid&catsearch=$catsearch&kwsearch=$kwsearch&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc";
                echo "\">Set Default</a></p>";
            }
        }
    } else if ($Submit == "Edit") {
        $getitemquery = "SELECT * FROM " . $DB_Prefix . "_items WHERE ID = '$itemid'";
        $getitemresult = mysqli_query($dblink, $getitemquery) or die("Could not select items");
        $getitemrow = mysqli_fetch_array($getitemresult);
        $prodid = $getitemrow[ID];
        $catnum = $getitemrow[Catalog];
        $stripitem = str_replace('"', '&quot;', stripslashes($getitemrow[Item]));
        $stripmetatitle = str_replace('"', '&quot;', stripslashes($getitemrow[MetaTitle]));
        $stripdescrip = stripslashes($getitemrow[Description]);
        $stripkeywords = stripslashes($getitemrow[Keywords]);
        $smallimage = $getitemrow[SmImage];
        $largeimage = $getitemrow[LgImage];
        $smallimage2 = $getitemrow[SmImage2];
        $largeimage2 = $getitemrow[LgImage2];
        $smallimage3 = $getitemrow[SmImage3];
        $largeimage3 = $getitemrow[LgImage3];
        $cat1 = $getitemrow[Category1];
        $cat2 = $getitemrow[Category2];
        $cat3 = $getitemrow[Category3];
        $cat4 = $getitemrow[Category4];
        $cat5 = $getitemrow[Category5];
        $units = (float)$getitemrow[Units];
        $regularprice = $getitemrow[RegPrice];
        $saleprice = $getitemrow[SalePrice];
        $outofstock = $getitemrow[OutOfStock];
        $active = $getitemrow[Active];
        $discountpr = $getitemrow[DiscountPr];
        $wholesale = $getitemrow[Wholesale];
        $wsonly = $getitemrow[WSOnly];
        $featured = $getitemrow[Featured];
        if ($getitemrow[DateEdited] != 0) {
            $dateedit = explode("-", $getitemrow[DateEdited]);
            $editdate = date("n/j/y", mktime(0, 0, 0, $dateedit[1], $dateedit[2], $dateedit[0]));
        } else
            $editdate = date("n/j/y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $itemcost = $getitemrow[ItemCost];
        $taxpercent = $getitemrow[TaxPercent];
        $popuppg = $getitemrow[PopUpPg];
    }

// Display Product Name
    echo "<p align=\"center\" class=\"fieldname\">";
    if ($Submit == "Edit")
        echo "Editing: $stripitem";
    else if ($Submit == "Add")
        echo "Add New Product";
    echo "</p>";

// Display link back to item list
    if ($catsearch OR $kwsearch OR $limitview) {
        echo "<p align=\"center\">";
        echo "<a href=\"itemlist.php?catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
        echo "<i>(Back to Item List)</i></a></p>";
    }
    ?>

    <form method="POST" name="EditForm" action="options.php">
        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                    <tr>
                        <td valign="top" align="right">Item Name:</td>
                        <td colspan="3" valign="top" align="left">
                            <input type="text" name="item" value="<?php echo "$stripitem"; ?>" size="47">
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">
                            <div>Description:</div>
                            <?php
                            if ($Submit == "Edit")
                                echo "<div class=\"smalltext\"><a href=\"$CatalogURL?item=$prodid\" target=\"_blank\">Item Link</a></div>";
                            ?>
                        </td>
                        <td colspan="3" valign="top" align="left">
                            <?php
                            echo "<textarea rows=\"12\" name=\"description\" id=\"description\" cols=\"40\">$stripdescrip</textarea>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Keywords:</td>
                        <td colspan="3" valign="top" align="left">
                            <?php
                            echo "<textarea rows=\"3\" name=\"keywords\" cols=\"40\">$stripkeywords</textarea>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Meta Title:</td>
                        <td colspan="3" valign="top" align="left">
                            <input type="text" name="metatitle" value="<?php echo "$stripmetatitle"; ?>" size="47">
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Thumbnail URL:</td>
                        <td colspan="3" valign="top" align="left">
                            <input type="text" name="smimage" value="<?php echo "$smallimage"; ?>" size="40">
                            <?php
                            echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=smimage\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=smimage', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Large&nbsp;Image&nbsp;URL:</td>
                        <td colspan="3" valign="top" align="left">
                            <input type="text" name="lgimage" value="<?php echo "$largeimage"; ?>" size="40">
                            <?php
                            echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=lgimage\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=lgimage', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
                            ?>
                        </td>
                    </tr>
                    <?php
                    if ($itemimages > 1) {
                        ?>
                        <tr>
                            <td valign="top" align="right">Thumbnail 2:</td>
                            <td colspan="3" valign="top" align="left">
                                <input type="text" name="smimage2" value="<?php echo "$smallimage2"; ?>" size="40">
                                <?php
                                echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=smimage2\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=smimage2', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="right">Large&nbsp;Image&nbsp;2:</td>
                            <td colspan="3" valign="top" align="left">
                                <input type="text" name="lgimage2" value="<?php echo "$largeimage2"; ?>" size="40">
                                <?php
                                echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=lgimage2\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=lgimage2', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    if ($itemimages == 3) {
                        ?>
                        <tr>
                            <td valign="top" align="right">Thumbnail 3:</td>
                            <td colspan="3" valign="top" align="left">
                                <input type="text" name="smimage3" value="<?php echo "$smallimage3"; ?>" size="40">
                                <?php
                                echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=smimage3\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=smimage3', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="right">Large&nbsp;Image&nbsp;3:</td>
                            <td colspan="3" valign="top" align="left">
                                <input type="text" name="lgimage3" value="<?php echo "$largeimage3"; ?>" size="40">
                                <?php
                                echo "<a href=\"includes/imgload.php?formsname=EditForm&fieldsname=lgimage3\" target=\"_blank\" onClick=\"PopUp=window.open('includes/imgload.php?formsname=EditForm&fieldsname=lgimage3', 'NewWin', 'resizable=yes,scrollbars=no,status=yes,width=400,height=250,left=0,top=0,screenX=0,screenY=0'); PopUp.focus(); return false;\">Upload</a>";
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    $popupquery = "SELECT ID, PageTitle FROM " . $DB_Prefix . "_popups ORDER BY PageTitle";
                    $popupresult = mysqli_query($dblink, $popupquery) or die ("Unable to select. Try again later.");
                    $popupnum = mysqli_num_rows($popupresult);
                    if ($popupnum > 0) {
                        echo "<tr>";
                        echo "<td valign=\"top\" align=\"right\">Pop-Up&nbsp;Page:</td>";
                        echo "<td colspan=\"3\" valign=\"top\" align=\"left\">";
                        echo "<select size=\"1\" name=\"popuppg\">";
                        echo "<option ";
                        if ($popuppg == "")
                            echo "selected ";
                        echo "value=\"\">N/A</option>";
                        for ($p = 1; $popuprow = mysqli_fetch_row($popupresult); ++$p) {
                            $pop_up = stripslashes($popuprow[1]);
                            echo "<option ";
                            if ($popuppg == $popuprow[0])
                                echo "selected ";
                            echo "value=\"$popuprow[0]\">$pop_up</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td valign="top" align="right">Catalog&nbsp;#:</td>
                        <td valign="top" align="left">
                            <input type="text" name="catalog" value="<?php echo "$catnum"; ?>" size="9">
                        </td>
                        <td valign="top" align="right">Shipping Units:</td>
                        <td valign="top" align="left">
                            <input type="text" name="units" value="<?php echo "$units"; ?>" size="9">
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Regular Price:</td>
                        <td valign="top" align="left">
                            <?php echo "$currency"; ?>
                            <input type="text" name="regprice" value="<?php echo "$regularprice"; ?>" size="9">
                        </td>
                        <td valign="top" align="right">On Sale Price:</td>
                        <td valign="top" align="left">
                            <?php echo "$currency"; ?>
                            <input type="text" name="saleprice" value="<?php echo "$saleprice"; ?>" size="9">
                        </td>
                    </tr>
                    <?php
                    if ($showcosts == "Yes") {
                        ?>
                        <tr>
                            <td valign="top" align="right">Tax %:</td>
                            <td valign="top" align="left">
                                <input type="text" name="taxpercent" value="<?php echo "$taxpercent"; ?>" size="8">%
                            </td>
                            <td valign="top" align="right">Your Costs:</td>
                            <td valign="top" align="left">
                                <?php echo "$currency"; ?>
                                <input type="text" name="itemcost" value="<?php echo "$itemcost"; ?>" size="9">
                            </td>
                        </tr>
                        <?php
                    }
                    $wsquery = "SELECT ID FROM " . $DB_Prefix . "_pages WHERE PageName='wholesale' AND PageType='optional' AND Active='Yes'";
                    $wsresult = mysqli_query($dblink, $wsquery) or die ("Unable to select. Try again later.");
                    if (mysqli_num_rows($wsresult) == 1) {
                        ?>
                        <tr>
                            <td valign="top" align="right">Wholesale %:</td>
                            <td valign="top" align="left">
                                <input type="text" name="wholesale" value="<?php echo "$wholesale"; ?>" size="8">%
                            </td>
                            <td valign="top" align="right">Display:</td>
                            <td valign="top" align="left">
                                <select size="1" name="wsonly">
                                    <?php
                                    if ($wsonly == "Yes")
                                        echo "<option selected value=\"Yes\">WS Only</option><option value=\"No\">All</option>";
                                    else
                                        echo "<option value=\"Yes\">WS Only</option><option selected value=\"No\">All</option>";
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td valign="top" align="right">Active Item:</td>
                        <td valign="top" align="left">
                            <select size="1" name="active">
                                <?php
                                if ($active == "No")
                                    echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
                                else
                                    echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
                                ?>
                            </select>
                        </td>
                        <td valign="top" align="right">Out of Stock?</td>
                        <td valign="top" align="left">
                            <select size="1" name="outofstock">
                                <?php
                                if ($outofstock == "Yes")
                                    echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
                                else
                                    echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">Featured:</td>
                        <td valign="top" align="left" colspan="3">
                            <select size="1" name="featured">
                                <?php
                                if ($featured == "Yes")
                                    echo "<option selected value=\"Yes\">Yes</option><option value=\"No\">No</option>";
                                else
                                    echo "<option value=\"Yes\">Yes</option><option selected value=\"No\">No</option>";
                                ?>
                            </select>
                        </td>
                    </tr>

                    <?php
                    if ($showdiscountpr == "Yes") {
                        ?>
                        <tr>
                            <td valign="middle" align="center" colspan="4">
                                <hr noshade size="1">
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" colspan="4">Use the following to set price discounts for
                                your products.
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="right">Price Discounts:</td>
                            <td valign="top" align="left" colspan="3">
                                <?php
                                echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">";
                                $disccount = explode("~", $discountpr);
                                for ($i = 1; $i <= 5; ++$i) {
                                    $dc = $i - 1;
                                    $qtyval = "";
                                    $amtval = "";
                                    $discval = "";
                                    if ($disccount[$dc]) {
                                        $discvals = explode(",", $disccount[$dc]);
                                        $qtyval = $discvals[0];
                                        $amtval = $discvals[1];
                                    }
                                    echo "<tr>";
                                    echo "<td valign=\"middle\" nowrap>";
                                    echo "<input type=\"text\" name=\"qty$i\" value=\"$qtyval\" size=\"3\"> or more ";
                                    echo "</td>";
                                    echo "<td valign=\"middle\" nowrap>- ";
                                    echo "<input type=\"text\" name=\"amt$i\" value=\"$amtval\" size=\"6\"> ";
                                    echo "<input type=\"radio\" name=\"disctype$i\" ";
                                    if ($discvals[2] == "D" OR !$discvals[2])
                                        echo "checked ";
                                    echo "value=\"D\">% off &nbsp;";
                                    echo "<input type=\"radio\" name=\"disctype$i\" ";
                                    if ($discvals[2] == "A")
                                        echo "checked ";
                                    echo "value=\"A\">$currency off";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                    <tr>
                        <td valign="middle" align="center" colspan="4">
                            <hr noshade size="1">
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center" colspan="4">
                            Assign up to 5 categories to this item.
                        </td>
                    </tr>

                    <?php
                    // Display categories
                    $sclimit = 30;
                    for ($ci = 1; $ci <= 5; ++$ci) {
                        if ($Submit == "Add" AND $catsearch > 0 AND $ci == 1)
                            $thiscat = $catsearch;
                        else
                            $thiscat = ${"cat$ci"};
                        echo "<tr>";
                        echo "<td valign=\"top\" align=\"right\">Category #$ci:</td>";
                        echo "<td valign=\"top\" align=\"left\" colspan=\"3\">";
                        echo "<select size=\"1\" name=\"category$ci\">";
                        echo "<option ";
                        if ($thiscat == 0)
                            echo "selected ";
                        echo "value=\"\">None</option>";
                        $getcatquery = "SELECT ID, Category FROM " . $DB_Prefix . "_categories WHERE Parent='0' ORDER BY Category";
                        $getcatresult = mysqli_query($dblink, $getcatquery) or die("Could not select categories");
                        for ($getcatcount = 1; $getcatrow = mysqli_fetch_row($getcatresult); ++$getcatcount) {
                            $smallgetcat = substr($getcatrow[1], 0, $sclimit);
// Are there subcategories?
                            $subcatquery = "SELECT ID, Category FROM " . $DB_Prefix . "_categories WHERE Parent = '$getcatrow[0]' ORDER BY Category";
                            $subcatresult = mysqli_query($dblink, $subcatquery) or die ("Could not show categories. Try again later.");
                            $subcatnum = mysqli_num_rows($subcatresult);
// No there are no subcats so display the parent only
                            if ($subcatnum == 0) {
                                echo "<option ";
                                if ($thiscat == $getcatrow[0])
                                    echo "selected ";
                                echo "value=\"$getcatrow[0]\">$smallgetcat</option>";
                            } // Yes there are subcats - go through loop and display them
                            else {
                                while ($subcatrow = mysqli_fetch_row($subcatresult)) {
                                    $smallsubcat = substr($subcatrow[1], 0, $sclimit);
// Are there end categories?
                                    $endcatquery = "SELECT ID, Category FROM " . $DB_Prefix . "_categories WHERE Parent = '$subcatrow[0]' ORDER BY Category";
                                    $endcatresult = mysqli_query($dblink, $endcatquery) or die ("Could not show categories. Try again later.");
// No there are no endcats so display the parent and subcat only
                                    if (mysqli_num_rows($endcatresult) == 0) {
                                        echo "<option ";
                                        if ($thiscat == $subcatrow[0])
                                            echo "selected ";
                                        echo "value=\"$subcatrow[0]\">$smallgetcat > $smallsubcat</option>";
                                    } else {
                                        while ($endcatrow = mysqli_fetch_row($endcatresult)) {
                                            $smallendcat = substr($endcatrow[1], 0, $sclimit);
                                            echo "<option ";
                                            if ($thiscat == $endcatrow[0])
                                                echo "selected ";
                                            echo "value=\"$endcatrow[0]\">$smallgetcat > $smallsubcat > $smallendcat</option>";
                                        }
                                    }
                                }
                            }
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "</tr>";
                    }

                    if ($Submit == "Edit") {
                        ?>
                        <tr>
                            <td valign="middle" align="center" colspan="4">
                                <hr noshade size="1">
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="right">Date Entered:</td>
                            <td valign="top" align="left">
                                <input type="text" name="editdate" value="<?php echo "$editdate"; ?>" size="10">
                            <td valign="top" align="left" colspan="2">
                                (Format MM/DD/YY)
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td valign="middle" align="center" colspan="4">
                            <?php
                            echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
                            echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
                            echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
                            echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
                            echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
                            echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
                            echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
                            if ($Submit == "Edit") {
                                echo "<input type=\"hidden\" value=\"$itemid\" name=\"itemid\">";
                                echo "<input type=\"hidden\" value=\"EditItem\" name=\"itemmode\">";
                            } else
                                echo "<input type=\"hidden\" value=\"AddItem\" name=\"itemmode\">";
                            ?>
                            <input type="hidden" value="items" name="mode">
                            <input type="hidden" value="continue" name="action">
                            <input type="submit" class="button" value="Continue" name="Submit">
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>
    <?php
} else if ($Submit == "Delete") {
    $getitemquery = "SELECT ID, Item FROM " . $DB_Prefix . "_items WHERE ID = '$itemid'";
    $getitemresult = mysqli_query($dblink, $getitemquery) or die("Could not select items");
    $getitemrow = mysqli_fetch_row($getitemresult);
    $stripitem = stripslashes($getitemrow[1]);

// Sets output page
    if ($catsearch OR $kwsearch OR $limitview)
        $outputpage = "itemlist.php";
    else
        $outputpage = "items.php";
    ?>
    <form method="POST" action="<?php echo "$outputpage"; ?>">
        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                    <tr>
                        <td align="center">
                            You are about to delete the following item:
                            <p align="center" class="fieldname">
                                <?php
                                echo "$stripitem";
                                ?>
                            </p>
                            <p>Do you want to continue?</p>
                            <?php
                            echo "<input type=\"hidden\" value=\"$itemid\" name=\"itemid\">";
                            echo "<input type=\"hidden\" value=\"$catsearch\" name=\"catsearch\">";
                            echo "<input type=\"hidden\" value=\"$kwsearch\" name=\"kwsearch\">";
                            echo "<input type=\"hidden\" value=\"$limitview\" name=\"limitview\">";
                            echo "<input type=\"hidden\" value=\"$limitofitems\" name=\"limitofitems\">";
                            echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
                            echo "<input type=\"hidden\" value=\"$adminsrc\" name=\"adminsrc\">";
                            echo "<input type=\"hidden\" value=\"$page\" name=\"page\">";
                            echo "<input type=\"hidden\" value=\"$urllink\" name=\"urllink\">";
                            ?>
                            <input type="hidden" value="items" name="mode">
                            <input type="submit" class="button" value="Yes, Delete Item" name="Submit">&nbsp; <input
                                    type="submit" class="button" value="No, Don't Delete" name="Submit">
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>
    <?php
} else {
    $varquery = "SELECT ID, ShowItemLimit FROM " . $DB_Prefix . "_vars WHERE ID=1";
    $varresult = mysqli_query($dblink, $varquery) or die ("Unable to select your system variables. Try again later.");
    $thisyear = date("Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
    $varrow = mysqli_fetch_row($varresult);
    $showitemlimit = $varrow[1];
    $getitemquery = "SELECT ID, Catalog, Item FROM " . $DB_Prefix . "_items ORDER BY Item";
    $getitemresult = mysqli_query($dblink, $getitemquery) or die ("Could not show items. Try again later.");
    $getitemnum = mysqli_num_rows($getitemresult);
    if ($showitemlimit == "Yes") {
        ?>
        <form method="POST" action="items.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                        <?php
                        if ($getitemnum == 0)
                            echo "<tr><td align=\"center\">No Items Listed.</td></tr>";
                        else {
                            ?>
                            <tr>
                                <td valign="middle" align="center">
                                    <div class="fieldname">View Items:</div>
                                    <?php
                                    $itmlimit = 60;
                                    echo "<select size=\"1\" name=\"itemid\">";
                                    for ($getitemcount = 1; $getitemrow = mysqli_fetch_row($getitemresult); ++$getitemcount) {
                                        $display = stripslashes($getitemrow[2]);
                                        if ($getitemrow[1])
                                            $display .= " (# $getitemrow[1])";
                                        echo "<option value=\"$getitemrow[0]\">" . substr($display, 0, $itmlimit) . "</option>";
                                    }
                                    echo "</select>";
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td valign="middle" align="center">
                                <input type="submit" class="button" value="Add" name="Submit">
                                <?php
                                if ($getitemnum != 0) {
                                    echo "&nbsp;|&nbsp;<input type=\"submit\" class=\"button\" value=\"Edit\" name=\"Submit\">";
                                    echo "&nbsp;|&nbsp;<input type=\"submit\" class=\"button\" value=\"Delete\" name=\"Submit\">";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
    }
    ?>

    <form action="itemlist.php" method="POST">
        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                    <tr>
                        <td vAlign="center" align="center" colspan="2" class="fieldname">Advanced Item Search:</td>
                    </tr>
                    <tr>
                        <td vAlign="top" align="right">
                            Category:
                        </td>
                        <td vAlign="top" align="left">
                            <?php
                            $getcatquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '0' ORDER BY Category";
                            $getcatresult = mysqli_query($dblink, $getcatquery) or die ("Could not show categories. Try again later.");
                            $getcatnum = mysqli_num_rows($getcatresult);
                            if ($getcatnum == 0)
                                echo "No Categories Listed.";
                            else {
                                echo "<select size=\"1\" name=\"catsearch\">";
                                echo "<option value=\"\"></option>";
                                for ($getcatcount = 1; $getcatrow = mysqli_fetch_row($getcatresult); ++$getcatcount) {
                                    $display = stripslashes($getcatrow[0]);
                                    echo "<option value=\"$getcatrow[1]\">$display</option>";
                                    $subcatquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$getcatrow[1]' ORDER BY Category";
                                    $subcatresult = mysqli_query($dblink, $subcatquery) or die ("Could not show categories. Try again later.");
                                    $subcatnum = mysqli_num_rows($subcatresult);
                                    for ($subcatcount = 1; $subcatrow = mysqli_fetch_row($subcatresult); ++$subcatcount) {
                                        $subdisplay = stripslashes($subcatrow[0]);
                                        echo "<option value=\"$subcatrow[1]\">&nbsp;-&nbsp;$subdisplay</option>";
// START MULTI CATEGORY
                                        $endcatquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$subcatrow[1]' ORDER BY Category";
                                        $endcatresult = mysqli_query($dblink, $endcatquery) or die ("Could not show categories. Try again later.");
                                        $endcatnum = mysqli_num_rows($endcatresult);
                                        for ($endcatcount = 1; $endcatrow = mysqli_fetch_row($endcatresult); ++$endcatcount) {
                                            $subdisplay = stripslashes($endcatrow[0]);
                                            echo "<option value=\"$endcatrow[1]\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;$subdisplay</option>";
                                        }
// END MULTI CATEGORY
                                    }
                                }
                                echo "</select>";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td vAlign="top" align="right">
                            Keywords:
                        </td>
                        <td vAlign="top" align="left">
                            <input type="text" name="kwsearch" size="20">
                        </td>
                    </tr>
                    <tr>
                        <td vAlign="top" align="right">
                            View:
                        </td>
                        <td vAlign="top" align="left">
                            <select size="1" name="limitview">
                                <option selected value="all">All Products</option>
                                <option value="inventory">Items w/ Quantities</option>
                                <option value="outofstock">Out of Stock Items</option>
                                <option value="soldout">Sold Out Items</option>
                                <option value="inactive">Inactive Items</option>
                                <option value="sale">On Sale Items</option>
                                <option value="featured">Featured Items</option>
                            </select>
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td vAlign="top" align="right">
                            Order By:
                        </td>
                        <td vAlign="top" align="left">
                            <select size="1" name="orderby">
                                <option selected value="Item">Item A-Z</option>
                                <option value="Item DESC">Item Z-A</option>
                                <option value="DateEdited DESC">Newest to Oldest</option>
                                <option value="DateEdited">Oldest to Newest</option>
                                <option value="Catalog">Catalog Number</option>
                                <option value="Inventory DESC">Most In Stock Items</option>
                                <option value="Inventory">Least In Stock Items</option>
                                <option value="Featured">Featured First</option>
                                <option value="RegPrice">Regular Price</option>
                                <option value="SalePrice">Sale Price</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td vAlign="top" align="right">
                            Display Per Page:
                        </td>
                        <td vAlign="top" align="left">
                            <select size="1" name="limitofitems">
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
                        <td vAlign="top" align="right">Edit Mode:</td>
                        <td vAlign="top" align="left">
                            <select size="1" name="adminsrc">
                                <option selected value="standard">Edit Each Item Individually</option>
                                <option value="multi">Edit Items in Groups (10 Max)</option>
                                <option value="inventory">Edit Inventory Only</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td vAlign="center" align="center" colspan="2">
                            <input type="submit" class="button" value="View Items" name="Submit">
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <?php
// Show add button if no drop down
    if ($showitemlimit == "No") {
        ?>
        <form method="POST" action="items.php">
            <p align="center">
                Add a New Item:
                <input type="submit" class="button" value="Add" name="Submit"></p></form>
        <?php
    }

}

if ($catsearch OR $kwsearch OR $limitview) {
    echo "<p align=\"center\">";
    echo "<a href=\"itemlist.php?catsearch=$catlink&kwsearch=$kwlink&limitview=$limitview&limitofitems=$limitofitems&orderby=$orderby&page=$page&adminsrc=$adminsrc\">";
    echo "<i>(Back to Item List)</i></a></p>";
}
?>

<?php
include("includes/links2.php");
include("includes/footer.htm");
?>

</html>