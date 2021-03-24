<html>

<head>
    <title>Administration</title>
    <link rel="stylesheet" type="text/css" href="includes/style.css">
</head>

<body>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

$showquery = "SELECT SubGroup, GivePermission FROM " . $DB_Prefix . "_permissions WHERE SetPg='$setpg' AND SubGroup<>''";
$showresult = mysqli_query($dblink, $showquery) or die ("Unable to select. Try again later.");
while ($showrow = mysqli_fetch_row($showresult)) {
    if ($showrow[0] == "sale")
        $show_sale = $showrow[1];
    if ($showrow[0] == "upload")
        $show_uploads = $showrow[1];
    if ($showrow[0] == "delete")
        $show_delete = $showrow[1];
}

if ($show_sale == "No" AND $show_uploads == "No" AND $show_delete == "No" AND $set_master_key == "no") {
    ?>
    <table border="0" cellpadding="7" cellspacing="0" align="center" class="generaltable">
        <tr>
            <td align="center" class="fieldname" colspan="2">Global Administration</td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                Sorry, but the global administration area is not currently available.
                Please ask your system administrator for assistance.
            </td>
        </tr>
    </table>
    <?php
}

$curquery = "SELECT Currency FROM " . $DB_Prefix . "_vars WHERE ID='1'";
$curresult = mysqli_query($dblink, $curquery) or die ("Unable to select. Try again later.");
$currow = mysqli_fetch_row($curresult);
$currency = $currow[0];

// Delete Items
if ($continue == "Yes") {
    $delquery = "SELECT ID FROM " . $DB_Prefix . "_items WHERE ID > '0'";
    if ($catid != "") {
        $delquery .= " AND (Category1='$catid' OR Category2='$catid' OR Category3='$catid'";
        $delquery .= " OR Category4='$catid' OR Category5='$catid')";
    }
    if ($delterms == "OOS")
        $delquery .= " AND OutOfStock='Yes'";
    else if ($delterms == "Zero")
        $delquery .= " AND Inventory='0'";
    else if ($delterms == "Sale")
        $delquery .= " AND SalePrice > 0";
    else if ($delterms == "Inactive")
        $delquery .= " AND Active='No'";
    else if ($delterms == "Wholesale")
        $delquery .= " AND WSOnly='Yes'";
    $delresult = mysqli_query($dblink, $delquery) or die ("Unable to select. Try again later.");
    while ($delrow = mysqli_fetch_row($delresult)) {
        $itemid = $delrow[0];
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
}

// Update sale prices
if ($mode == "saleprice") {
    if (!$category) {
        $mode = "";
        $saleerror = "Please select a category.";
    } else {
        $diff = (100 - $amount) / 100;
        $catquer = "Category1='$category' OR Category2='$category' OR Category3='$category' ";
        $catquer .= "OR Category4='$category' OR Category5='$category'";
        if ($type == "exact" AND $amount > 0)
            $querinfo = "SalePrice = '$amount' WHERE $catquer";
        else if ($type == "amount" AND $amount > 0)
            $querinfo = "SalePrice = RegPrice-$amount WHERE RegPrice-$amount > 0 AND ($catquer)";
        else if ($type == "percent" AND $amount > 0 AND $amount <= 100)
            $querinfo = "SalePrice = RegPrice*$diff WHERE $catquer";
        else if ($type == "remove")
            $querinfo = "SalePrice = '' WHERE $catquer";
        if ($querinfo) {
            $catquery = "SELECT Category FROM " . $DB_Prefix . "_categories WHERE ID='$category'";
            $catresult = mysqli_query($dblink, $catquery) or die ("Unable to select. Try again later.");
            $catrow = mysqli_fetch_row($catresult);
            $stripcat = stripslashes($catrow[0]);
            $updquery = "UPDATE " . $DB_Prefix . "_items SET $querinfo";
            $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
            $catsquery = "SELECT ID FROM " . $DB_Prefix . "_items WHERE ($catquer)";
            if ($type == "amount" AND $amount > 0)
                $catsquery .= " AND RegPrice-$amount > 0";
            $catsresult = mysqli_query($dblink, $catsquery) or die ("Unable to select. Try again later.");
            $catsnum = mysqli_num_rows($catsresult);
            if ($catsnum == 0)
                $finishmsg = "No items were found for the category<br><span class=accent>$stripcat</span><br>with this criteria.";
            else
                $finishmsg = "Your sale prices have been updated for the category:<br><span class=accent>$stripcat ($catsnum items)</span>";
        } else
            $finishmsg = "Your sale prices could not be updated. Please try again later.";
        ?>

        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                    <tr>
                        <td valign="middle" align="center" class="fieldname">Files Updated</td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center">
                            <?php echo "$finishmsg"; ?>
                        </td>
                    </tr>
                </table>
            </center>
        </div>
        <p align="center"><a href="global.php">Back to Global Updates</a></p>

        <?php
    }
} // Delete items
else if ($mode == "delete") {
    ?>
    <form action="global.php" method="POST">
        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                    <?php
                    $itemsquery = "SELECT * FROM " . $DB_Prefix . "_items WHERE ID > '0'";
                    if ($catid != "") {
                        $itemsquery .= " AND (Category1='$catid' OR Category2='$catid' OR Category3='$catid'";
                        $itemsquery .= " OR Category4='$catid' OR Category5='$catid')";
                    }
                    if ($delterms == "OOS")
                        $itemsquery .= " AND OutOfStock='Yes'";
                    else if ($delterms == "Zero")
                        $itemsquery .= " AND Inventory='0'";
                    else if ($delterms == "Sale")
                        $itemsquery .= " AND SalePrice > 0";
                    else if ($delterms == "Inactive")
                        $itemsquery .= " AND Active='No'";
                    else if ($delterms == "Wholesale")
                        $itemsquery .= " AND WSOnly='Yes'";
                    $itemsresult = mysqli_query($dblink, $itemsquery) or die ("Unable to select. Try again later.");
                    $itemsnum = mysqli_num_rows($itemsresult);
                    if ($itemsnum == 0)
                        echo "<tr><td align=\"center\">There are no items that fit this criteria. Please try again.</td></tr>";
                    else {
                        ?>
                        <tr>
                            <td valign="top" align="left">
                                <?php
                                echo "You are about to delete $itemsnum items";
                                if ($catid != "") {
                                    $catquery = "SELECT Category FROM " . $DB_Prefix . "_categories WHERE ID='$catid'";
                                    $catresult = mysqli_query($dblink, $catquery) or die ("Unable to select. Try again later.");
                                    if (mysqli_num_rows($catresult) == 1) {
                                        $catrow = mysqli_fetch_row($catresult);
                                        $stripcat = stripslashes($catrow[0]);
                                        echo " in the category $stripcat";
                                    }
                                }
                                if ($delterms == "OOS")
                                    echo " that are out of stock";
                                else if ($delterms == "Zero")
                                    echo " that have zero items in inventory";
                                else if ($delterms == "Sale")
                                    echo " that are on sale";
                                else if ($delterms == "Inactive")
                                    echo " that are inactive";
                                else if ($delterms == "Wholesale")
                                    echo " that are wholesaled only";
                                echo ". You cannot reverse this procedure.<br>Do you want to continue?";
                                echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
                                echo "<input type=\"hidden\" name=\"delterms\" value=\"$delterms\">";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <input type="submit" value="Yes" name="continue" class="button">
                                <input type="submit" value="No" name="continue" class="button">
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </center>
        </div>
    </form>
    <p align="center"><a href="global.php">Back to Global Updates</a></p>
    <?php
} // Upload text file
else if ($mode == "upload") {
    ?>

    <table border=0 cellpadding=3 cellspacing=0 class="generaltable" align="center">
        <tr>
            <td valign="middle" align="center" class="fieldname">Text File Upload</td>
        </tr>
        <tr>
            <td valign="middle" align="center">

                <?php
                // Check to see if the file exists
                if (empty($_FILES['uploadfile']))
                    echo "Sorry, but this file could not be found. Please try again.";
                else {
                    $txttype = strtolower(substr($_FILES['uploadfile']['name'], -4));
                    if ($_FILES['uploadfile']['type'] != "text/plain" OR $txttype != ".txt")
                        echo "Sorry, but you can only upload a Tab-Delimited text file here. Please try again.";
                    else if ($_FILES['uploadfile']['size'] > "100000")
                        echo "Sorry, but files must be under 100 Kilobytes in size. Please try again.";
                    else {
// Get default id if copy options is selected
                        if ($copyoptions == "yes") {
                            $defquery = "SELECT DefaultProduct FROM " . $DB_Prefix . "_vars";
                            $defresult = mysqli_query($dblink, $defquery) or die ("Unable to select. Try again later.");
                            $defrow = mysqli_fetch_row($defresult);
                        }

// Read file into an array:
                        $tempfile = file($_FILES['uploadfile']['tmp_name']);
                        $insertfields = "";
                        foreach ($tempfile as $num => $line) {
                            $insertlines = "";
                            $updatelines = "";
                            $fields = explode("\t", $line);
                            for ($f = 0; $f < count($fields); ++$f) {
// SET FIELD NAMES
                                if ($num == 0) {
                                    ${"fn$f"} = $fields[$f];
                                    if ($f > 0)
                                        $insertfields .= ", ";
                                    $insertfields .= "${"fn$f"}";
                                } // LOOP THROUGH LINES
                                else {
// Format fields
                                    $fieldname = addslash_mq($fields[$f]);
                                    $fieldname = str_replace('\"\"', '&quot;', $fieldname);
                                    $fieldname = str_replace('\"', '', $fieldname);
                                    $fieldname = str_replace('&quot;', '\"', $fieldname);
                                    if ($f == 0 OR $f == 1) {
                                        $fieldname = addslash_mq($fieldname);
                                        $fieldname = str_replace(":", "", $fieldname);
                                        $fieldname = str_replace(". ", ".", $fieldname);
                                        $fieldname = str_replace("#", "", $fieldname);
                                    }
// Format categories
                                    if ($f >= 11 AND $f <= 15) {
                                        if (is_numeric($fields[$f]))
                                            $fieldname = $fields[$f];
                                        else {
                                            $catname = addslash_mq($fields[$f]);
                                            $catquery = "SELECT ID FROM " . $DB_Prefix . "_categories WHERE Category='$fields[$f]'";
                                            $catresult = mysqli_query($dblink, $catquery) or die ("Unable to select. Try again later.");
                                            if (mysqli_num_rows($catresult) == 1) {
                                                $catrow = mysqli_fetch_row($catresult);
                                                $fieldname = stripslashes($catrow[0]);
                                            } else
                                                $fieldname = 0;
                                        }
                                    }
// Format yes no fields
                                    if ($f >= 22 AND $f <= 24 AND !$fields[$f])
                                        $fieldname = "No";
                                    if ($f == 25 AND !$fields[25])
                                        $fieldname = "Yes";
// Format date
                                    if ($f == 26 AND $fields[$f] != 0) {
                                        $splitdate = explode("/", $fields[26]);
                                        $fieldname = date("Y-m-d", mktime(0, 0, 0, $splitdate[0], $splitdate[1], $splitdate[2]));
                                    }
                                    if ($f > 0)
                                        $insertlines .= ", ";
                                    $insertlines .= "'$fieldname'";
                                    if ($f > 0)
                                        $updatelines .= ", ";
                                    $updatelines .= "${"fn$f"}='$fieldname'";
                                }
                            }
                            if ($num > 0) {
                                $selquery = "SELECT ID FROM " . $DB_Prefix . "_items WHERE Catalog='$fields[0]'";
                                $selresult = mysqli_query($dblink, $selquery) or die ("Unable to select. Try again later.");
                                $selnum = mysqli_num_rows($selresult);
                                if ($selnum == 1 AND !empty($fields[0])) {
                                    $selrow = mysqli_fetch_row($selresult);
                                    $selid = $selrow[0];
                                    $setquery = "UPDATE " . $DB_Prefix . "_items SET $updatelines WHERE ID='$selid'";
                                    $setresult = mysqli_query($dblink, $setquery) or die ("Unable to upload. Try again later.");
                                } else {
                                    $setquery = "INSERT INTO " . $DB_Prefix . "_items ($insertfields) VALUES ($insertlines)";
                                    $setresult = mysqli_query($dblink, $setquery) or die ("Unable to upload. Try again later.");
                                    $selid = mysqli_insert_id($dblink);
                                }
// Update options if needed
                                if ($copyoptions == "yes" AND $defrow[0] > 0 AND $selid != $defrow[0]) {
// First delete existing options and inventory if they exist
                                    $deloquery = "DELETE FROM " . $DB_Prefix . "_options WHERE ItemID='$selid'";
                                    $deloresult = mysqli_query($dblink, $deloquery) or die("Unable to delete. Please try again later.");
                                    $deliquery = "DELETE FROM " . $DB_Prefix . "_inventory WHERE ProductID='$selid'";
                                    $deliresult = mysqli_query($dblink, $deliquery) or die("Unable to delete. Please try again later.");
                                    $updquery = "UPDATE " . $DB_Prefix . "_items SET Inventory=NULL WHERE ID='$selid'";
                                    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
// Get the options from the default item and transfer to current item
                                    $getquery = "SELECT * FROM " . $DB_Prefix . "_options WHERE ItemID='$defrow[0]'";
                                    $getresult = mysqli_query($dblink, $getquery) or die ("Unable to select. Try again later.");
                                    while ($getrow = mysqli_fetch_row($getresult)) {
                                        $insquery = "INSERT INTO " . $DB_Prefix . "_options (ItemID, OptionNum, Name, Type, Attributes, Active) ";
                                        $insquery .= "VALUES ('$selid', '$getrow[2]', '$getrow[3]', '$getrow[4]', '$getrow[5]', '$getrow[6]')";
                                        $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
                                    }
                                }
                            }
                        }
                        $upl_msg = "Your file was uploaded. Please double check entries to ensure all data was loaded properly.";
                    }
                }
                ?>

                <?php echo "$upl_msg"; ?>
            </td>
        </tr>
    </table>

    <?php
}

if (!$mode) {

    if ($set_master_key == "yes" OR $show_sale == "Yes") {
        ?>
        <form method="POST" action="global.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center" class="fieldname" colspan="2">Global Sale Price Updates
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" colspan="2">Update sale prices for all items in a
                                category:
                            </td>
                        </tr>
                        <?php if ($saleerror) echo "<tr><td align=\"center\" class=\"error\" colspan=\"2\">$saleerror</td></tr>"; ?>
                        <tr>
                            <td valign="middle" align="right">Select Category:</td>
                            <td valign="middle" align="left">
                                <?php
                                // Set up categories
                                echo "<select name=\"category\" size=\"1\">";
                                echo "<option selected value=\"\">Select a Category</option>";
                                $getcat_1query = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent='' ORDER BY CatOrder, Category";
                                $getcat_1result = mysqli_query($dblink, $getcat_1query) or die("Could not select categories");
                                for ($getcat_1count = 1; $getcat_1row = mysqli_fetch_row($getcat_1result); ++$getcat_1count) {
// Are there subcategories?
                                    $subcat_1query = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$getcat_1row[1]' ORDER BY CatOrder, Category";
                                    $subcat_1result = mysqli_query($dblink, $subcat_1query) or die ("Could not show categories. Try again later.");
                                    $subcat_1num = mysqli_num_rows($subcat_1result);
                                    $stripcategory_1 = stripslashes($getcat_1row[0]);
                                    $ss_stripcategory_1 = substr($stripcategory_1, 0, 20);
// No there are no subcats so display the parent only
                                    if ($subcat_1num == 0)
                                        echo "<option value=\"$getcat_1row[1]\">$stripcategory_1</option>";
// Yes there are subcats - go through loop and display them
                                    else {
                                        while ($subcat_1row = mysqli_fetch_row($subcat_1result)) {
                                            $stripsubcat_1 = stripslashes($subcat_1row[0]);
                                            $ss_stripsubcat_1 = substr($stripsubcat_1, 0, 20);
// Are there end categories?
                                            $endcat_1query = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$subcat_1row[1]' ORDER BY CatOrder, Category";
                                            $endcat_1result = mysqli_query($dblink, $endcat_1query) or die ("Could not show categories. Try again later.");
// No there are no endcats so display the parent and subcat only
                                            if (mysqli_num_rows($endcat_1result) == 0)
                                                echo "<option value=\"$subcat_1row[1]\">$ss_stripcategory_1 &gt; $stripsubcat_1</option>";
                                            else {
                                                while ($endcat_1row = mysqli_fetch_row($endcat_1result)) {
                                                    $stripendcat_1 = stripslashes($endcat_1row[0]);
                                                    echo "<option value=\"$endcat_1row[1]\">$ss_stripcategory_1 &gt; $ss_stripsubcat_1 &gt; $stripendcat_1</option>";
                                                }
                                            }
                                        }
                                    }
                                }
                                echo "</select>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">Set To:</td>
                            <td valign="middle" align="left">
                                <input type="text" name="amount" size="10">
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">&nbsp;</td>
                            <td valign="middle" align="left">
                                <input type="radio" value="percent" checked name="type"> percent OFF the regular
                                price<br>
                                <input type="radio" value="amount" name="type"> <?php echo "$currency"; ?> amount OFF
                                the regular price<br>
                                <input type="radio" value="exact" name="type"> exact <?php echo "$currency"; ?>
                                amount<br>
                                <input type="radio" value="remove" name="type"> remove sale prices
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" colspan="2">
                                <input type="hidden" value="saleprice" name="mode">
                                <input type="submit" class="button" value="Update Sale Price" name="Submit">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
    }

    if ($set_master_key == "yes" OR $show_delete == "Yes") {
        ?>
        <form method="POST" action="global.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center" class="fieldname" colspan="2">Global Deletes</td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" colspan="2">Delete all items in the following category:
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">Select Category:</td>
                            <td valign="middle" align="left">
                                <?php
                                // Set up categories
                                echo "<select name=\"catid\" size=\"1\">";
                                echo "<option selected value=\"\">Delete All Products</option>";
                                $getcat_pquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent='' ORDER BY CatOrder, Category";
                                $getcat_presult = mysqli_query($dblink, $getcat_pquery) or die("Could not select categories");
                                for ($getcat_pcount = 1; $getcat_prow = mysqli_fetch_row($getcat_presult); ++$getcat_pcount) {
// Are there subcategories?
                                    $subcat_pquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$getcat_prow[1]' ORDER BY CatOrder, Category";
                                    $subcat_presult = mysqli_query($dblink, $subcat_pquery) or die ("Could not show categories. Try again later.");
                                    $subcat_pnum = mysqli_num_rows($subcat_presult);
                                    $stripcategory_p = stripslashes($getcat_prow[0]);
                                    $ss_stripcategory_p = substr($stripcategory_p, 0, 20);
// No there are no subcats so display the parent only
                                    if ($subcat_pnum == 0)
                                        echo "<option value=\"$getcat_prow[1]\">$stripcategory_p</option>";
// Yes there are subcats - go through loop and display them
                                    else {
                                        while ($subcat_prow = mysqli_fetch_row($subcat_presult)) {
                                            $stripsubcat_p = stripslashes($subcat_prow[0]);
                                            $ss_stripsubcat_p = substr($stripsubcat_p, 0, 20);
// Are there end categories?
                                            $endcat_pquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$subcat_prow[1]' ORDER BY CatOrder, Category";
                                            $endcat_presult = mysqli_query($dblink, $endcat_pquery) or die ("Could not show categories. Try again later.");
// No there are no endcats so display the parent and subcat only
                                            if (mysqli_num_rows($endcat_presult) == 0)
                                                echo "<option value=\"$subcat_prow[1]\">$ss_stripcategory_p &gt; $stripsubcat_p</option>";
                                            else {
                                                while ($endcat_prow = mysqli_fetch_row($endcat_presult)) {
                                                    $stripendcat_p = stripslashes($endcat_prow[0]);
                                                    echo "<option value=\"$endcat_prow[1]\">$ss_stripcategory_p &gt; $ss_stripsubcat_p &gt; $stripendcat_p</option>";
                                                }
                                            }
                                        }
                                    }
                                }
                                echo "</select>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">Only Delete:</td>
                            <td valign="middle" align="left">
                                <select size="1" name="delterms">
                                    <option selected value="">All Items In This Category</option>
                                    <option value="OOS">Out of Stock Items</option>
                                    <option value="Zero">Zero Inventory Items</option>
                                    <option value="Sale">Sale Items</option>
                                    <option value="Inactive">Inactive Items</option>
                                    <option value="Wholesale">Wholesale Only Items</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" colspan="2">
                                <input type="hidden" value="delete" name="mode">
                                <input type="submit" class="button" value="Delete" name="Submit">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
    }

    if ($set_master_key == "yes" OR $show_uploads == "Yes") {
        ?>
        <form enctype="multipart/form-data" action="global.php" method="post">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center" class="fieldname">Upload Files</td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                Upload a tab delimited text file of items into your database. Use the <a
                                        href="files/sample.txt" target="_blank">sample sheet</a> as a guide to create
                                your own text
                                file, and keep all columns as-is. This process can completely modify your current
                                product listing, so create a system back up first!
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                <input type="file" name="uploadfile" size="20"></td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
<span class="error">
WARNING: Checking the box below will delete all<br>
options and set all products listed in your text file<br>
with the same options the default product uses.<br>
</span><input type="checkbox" name="copyoptions" value="yes"> Copy options from default product?<br>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                <input type="hidden" value="upload" name="mode">
                                <input type="submit" class="button" value="Upload Sheet" name="Submit">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
    }

}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>
</html>