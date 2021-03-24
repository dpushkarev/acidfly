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

// UPDATE DEFAULT ITEMS
if ($Submit == "Update Defaults") {
    if ($product_id) {
        $product_id =
        $idquery = "SELECT ID FROM " . $DB_Prefix . "_items WHERE Catalog='$product_id'";
        $idresult = mysqli_query($dblink, $idquery) or die ("Unable to select. Try again later.");
        if (mysqli_num_rows($idresult) == 1) {
            $idrow = mysqli_fetch_row($idresult);
            $productid = $idrow[0];
        }
    }
    if ($productid) {
        $updquery = "UPDATE " . $DB_Prefix . "_vars SET DefaultProduct='$productid' WHERE ID='1'";
        $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
    }
}

$defaultcheckquery = "SELECT DefaultProduct, ShowItemLimit FROM " . $DB_Prefix . "_vars";
$defaultcheckresult = mysqli_query($dblink, $defaultcheckquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($defaultcheckresult) == 1)
    $defaultcheckrow = mysqli_fetch_row($defaultcheckresult);
?>

<form method="POST" action="defaults.php">
    <div align="center">
        <center>
            <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                <tr>
                    <td valign="middle" align="center" class="fieldname" colspan="2">Product Default</td>
                </tr>
                <tr>
                    <td valign="middle" align="center" colspan="2">Select one item to be set as your default for new
                        product additions.
                    </td>
                </tr>
                <tr>
                    <td valign="middle" align="right">Default:</td>
                    <td valign="middle" align="left">
                        <?php
                        $getitemquery = "SELECT ID, Catalog, Item FROM " . $DB_Prefix . "_items ORDER BY Item";
                        $getitemresult = mysqli_query($dblink, $getitemquery) or die ("Could not show items. Try again later.");
                        $getitemnum = mysqli_num_rows($getitemresult);
                        if ($getitemnum == 0)
                            echo "<i>Add an item first before setting a default.</i>";
                        else {
// If select item is set
                            if ($defaultcheckrow[1] == "Yes") {
                                echo "<select size=\"1\" name=\"productid\">";
                                echo "<option ";
                                if (!$defaultcheckrow[0])
                                    echo "selected ";
                                echo "value=\"0\">None</option>";
                                for ($getitemcount = 1; $getitemrow = mysqli_fetch_row($getitemresult); ++$getitemcount) {
                                    $display = stripslashes($getitemrow[2]);
                                    if ($getitemrow[1])
                                        $display .= " (# $getitemrow[1])";
                                    echo "<option ";
                                    if ($getitemrow[0] == $defaultcheckrow[0])
                                        echo "selected ";
                                    echo "value=\"$getitemrow[0]\">$display</option>";
                                }
                                echo "</select>";
                            } // Otherwise ask for catalog number
                            else {
                                $idquery = "SELECT Catalog FROM " . $DB_Prefix . "_items WHERE ID='$defaultcheckrow[0]'";
                                $idresult = mysqli_query($dblink, $idquery) or die ("Could not show items. Try again later.");
                                if (mysqli_num_rows($idresult) == 1) {
                                    $idrow = mysqli_fetch_row($idresult);
                                    $catid = str_replace('"', '&quot;', stripslashes($idrow[0]));
                                }
                                echo "<input type=\"text\" size=\"12\" name=\"product_id\" value=\"$catid\">";
                                echo " <span class=\"accent\">(Enter Catalog #)</span>";
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td valign="middle" align="center" colspan="2">
                        <input type="hidden" value="items" name="mode">
                        <input type="submit" class="button" value="Update Defaults" name="Submit">
                    </td>
                </tr>
            </table>
        </center>
    </div>
</form>

<?php
include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
