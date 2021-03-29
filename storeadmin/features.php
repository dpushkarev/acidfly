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

$limquery = "SELECT ShowItemLimit FROM " . $DB_Prefix . "_vars";
$limresult = mysqli_query($dblink, $limquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($limresult) == 1)
    $limrow = mysqli_fetch_row($limresult);

// Update item as a featured item (drop down box)
if ($mode == "Set" AND $itemid) {
    $updquery = "UPDATE " . $DB_Prefix . "_items SET Featured='Yes' WHERE ID='$itemid'";
    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
} // Remove featured status from item
else if ($mode == "Unset" AND $itemid) {
    $updquery = "UPDATE " . $DB_Prefix . "_items SET Featured='No' WHERE ID='$itemid'";
    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
} // Update featured items (check boxes)
else if ($mode == "Update" AND $relct >= 0) {
    $delquery = "UPDATE " . $DB_Prefix . "_items SET Featured='No' WHERE Featured='Yes'";
    $delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
    if ($addfeat) {
        $addfeat = addslash_mq($addfeat);
        $updquery = "UPDATE " . $DB_Prefix . "_items SET Featured='Yes' WHERE Catalog='$addfeat'";
        $updresult = mysqli_query($dblink, $updquery) or die ("Unable to update. Try again later.");
    }
    for ($r = 1; $r <= $relct; ++$r) {
        $updquery = "UPDATE " . $DB_Prefix . "_items SET Featured='Yes' WHERE ID='$editfeat[$r]'";
        $updresult = mysqli_query($dblink, $updquery) or die ("Unable to update. Try again later.");
    }
}

$featquery = "SELECT ID FROM " . $DB_Prefix . "_items WHERE Featured='Yes'";
$featresult = mysqli_query($dblink, $featquery) or die ("Unable to select. Try again later.");
$featnum = mysqli_num_rows($featresult);
?>

<form method="POST" action="features.php">
    <div align="center">
        <center>
            <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                <tr>
                    <td valign="middle" align="center" class="fieldname" colspan="2">Update Items as Features</td>
                </tr>
                <tr>
                    <td valign="middle" align="center" colspan="2">
                        Choose an item from the list below and select <i>Set</i> to instantly set it as a featured item.
                        Or select <i>Unset</i> to remove the feature status from that item.
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right">Select Product:</td>
                    <td valign="top" align="left">
                        <?php
                        $getitemquery = "SELECT ID, Catalog, Item, Featured FROM " . $DB_Prefix . "_items ORDER BY Featured, Item";
                        $getitemresult = mysqli_query($dblink, $getitemquery) or die ("Could not show items. Try again later.");
                        $getitemnum = mysqli_num_rows($getitemresult);

                        if ($getitemnum == 0)
                            echo "No Items Listed.";
                        else {
// Show drop down box for items
                            if ($limrow[0] == "Yes") {
                                echo "<select size=\"1\" name=\"itemid\">";
                                for ($getitemcount = 1; $getitemrow = mysqli_fetch_row($getitemresult); ++$getitemcount) {
                                    echo "<option ";
                                    if ((IsSet($itemid) AND $getitemrow[0] == $itemid) OR (!$itemid AND $getitemcount == 1))
                                        echo "selected ";
                                    echo "value=\"$getitemrow[0]\">";
                                    echo stripslashes($getitemrow[2]);
                                    if ($getitemrow[1])
                                        echo " # $getitemrow[1]";
                                    if ($getitemrow[3] == "Yes")
                                        echo " - FEATURED";
                                    echo "</option>";
                                }
                                echo "</select>";
                            } // As for catalog number for items
                            else {
                                $relct = 0;
                                for ($getitemcount = 1; $getitemrow = mysqli_fetch_row($getitemresult); ++$getitemcount) {
                                    if ($getitemrow[3] == "Yes") {
                                        ++$relct;
                                        echo "<input type=\"checkbox\" checked name=\"editfeat[$relct]\" value=\"$getitemrow[0]\"> ";
                                        if ($getitemrow[1])
                                            echo "#" . stripslashes($getitemrow[1]) . " ";
                                        echo stripslashes($getitemrow[2]) . "<br>";
                                    }
                                }
                                echo "Add: <input type=\"text\" size=\"12\" name=\"addfeat\" value=\"\">";
                                echo " <span class=\"accent\">(Enter Catalog #)</span>";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td valign="middle" align="center" colspan="2">
                        <?php
                        if ($limrow[0] == "Yes") {
                            echo "<input type=\"submit\" class=\"button\" value=\"Set\" name=\"mode\"> ";
                            echo "<input type=\"submit\" class=\"button\" value=\"Unset\" name=\"mode\">";
                        } else {
                            echo "<input type=\"hidden\" value=\"$relct\" name=\"relct\">";
                            echo "<input type=\"submit\" class=\"button\" value=\"Update\" name=\"mode\">";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </center>
    </div>
</form>

<?php
include("includes/links2.php");
include("includes/footer.htm"); ?>
</body>

</html>
