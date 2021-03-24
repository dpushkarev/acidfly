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

if ($mode == "submit" AND $_POST[froogleuser] AND $_POST[frooglepass]) {
    if (!$froogleserver)
        $froogle_server = "hedwig.google.com";
    if ($froogleconversion == "CAD$")
        $froogleconversion = "CAD";
    else if ($froogleconversion == "�")
        $froogleconversion = "GPB";
    else if ($froogleconversion == "�")
        $froogleconversion = "JPY";
    else if ($froogleconversion == "$" OR !$froogleconversion)
        $froogleconversion = "USD";
    $froogleval = "$froogleserver~$froogleuser~$frooglepass~$froogleconversion";
    $updquery = "UPDATE " . $DB_Prefix . "_vars SET Froogle='$froogleval' WHERE ID='1'";
    $updresult = mysqli_query($dblink, $updquery) or die ("Unable to select. Try again later.");

    $varquery = "SELECT CatalogPage, PageExt FROM " . $DB_Prefix . "_vars WHERE ID='1'";
    $varresult = mysqli_query($dblink, $varquery) or die ("Unable to select. Try again later.");
    $varrow = mysqli_fetch_row($varresult);
    $catalogpage = $varrow[0];
    $pageext = $varrow[2];
    $headers = "# html_escaped=NO\r\n";
    $headers .= "# updates_only=NO\r\n";
    $headers .= "# quoted=NO\r\n";
    $headers .= "product_url\t";
    $headers .= "name\t";
    $headers .= "description\t";
    $headers .= "price\t";
    $headers .= "image_url\t";
    $headers .= "category\t";
    $headers .= "offer_id\t";
    $headers .= "manufacturer_id\t";
    $headers .= "currency";
    $recordlist = "";

    $itemquery = "SELECT * FROM " . $DB_Prefix . "_items WHERE Active = 'Yes' AND OutOfStock = 'No' AND WSOnly = 'No'";
    $itemquery .= "AND (Inventory IS NULL OR Inventory > 0)";
    $itemresult = mysqli_query($dblink, $itemquery) or die ("Unable to select. Try again later.");
    while ($itemrow = mysqli_fetch_array($itemresult)) {
// Get URL
        $showurl = $urldir . "/" . $catalogpage . "?item=" . $itemrow[ID];

// Get Image
        if (substr($itemrow[SmImage], 0, 7) == "http://")
            $showimage = "$itemrow[SmImage]";
        else
            $showimage = "$urldir/$itemrow[SmImage]";

// Get Item Info
        $showitem = substr(str_replace('"', "''", stripslashes($itemrow[Item])), 0, 80);
        $showcatalog = str_replace('"', "''", stripslashes($itemrow[Catalog]));
        $showdesc = strip_tags(stripslashes($itemrow[Description]));
        $showdesc = str_replace("\t", "", $showdesc);
        $showdesc = str_replace("\r", "", $showdesc);
        $showdesc = str_replace("\n", "", $showdesc);
        $showdesc = str_replace('"', "''", $showdesc);
        $showdesc = substr($showdesc, 0, 1000);

// Get prices
        if ($itemrow[SalePrice] > 0)
            $setprice = $itemrow[SalePrice];
        else
            $setprice = $itemrow[RegPrice];

// Get categories
        $showcategory = "";
        if ($itemrow[Category1] > 0) {
            $catquery = "SELECT Category, Parent FROM " . $DB_Prefix . "_categories WHERE ID='$itemrow[Category1]'";
            $catresult = mysqli_query($dblink, $catquery) or die ("Unable to select. Try again later.");
            if (mysqli_num_rows($catresult) == 1) {
                $catrow = mysqli_fetch_row($catresult);
                if ($catrow[1] > 0) {
                    $parcatquery = "SELECT Category, Parent FROM " . $DB_Prefix . "_categories WHERE ID='$catrow[1]'";
                    $parcatresult = mysqli_query($dblink, $parcatquery) or die ("Unable to select. Try again later.");
                    if (mysqli_num_rows($parcatresult) == 1) {
                        $parcatrow = mysqli_fetch_row($parcatresult);
                        if ($parcatrow[1] > 0) {
                            $gpcatquery = "SELECT Category FROM " . $DB_Prefix . "_categories WHERE ID='$parcatrow[1]'";
                            $gpcatresult = mysqli_query($dblink, $gpcatquery) or die ("Unable to select. Try again later.");
                            if (mysqli_num_rows($gpcatresult) == 1)
                                $gpcatrow = mysqli_fetch_row($gpcatresult);
                        }
                    }
                }
            }

            if ($gpcatrow[0])
                $showcategory = "$gpcatrow[0] > $parcatrow[0] > $catrow[0]";
            else if ($parcatrow[0])
                $showcategory = "$parcatrow[0] > $catrow[0]";
            else if ($catrow[0])
                $showcategory = $catrow[0];
        }

        $showcurrency = $froogleconversion;

// Show options
        $chkatts = "";
        $chkquery = "SELECT Attributes FROM " . $DB_Prefix . "_options WHERE ItemID='$itemrow[ID]' AND OptionNum = '1' ";
        $chkquery .= "AND Attributes LIKE '%:%' AND (Type='Drop Down' OR Type='Radio Button') AND Active='Yes'";
        $chkresult = mysqli_query($dblink, $chkquery) or die ("Unable to select options. Try again later.");
        if (mysqli_num_rows($chkresult) == 1 AND $_POST[splitoptions] == "Yes") {
            $chkrow = mysqli_fetch_array($chkresult);
            $chkatts = explode("~", $chkrow[0]);
            $loopend = count($chkatts);
        } else
            $loopend = 1;

        for ($s = 1; $s <= $loopend; ++$s) {
            $invchk = 0;
            $sless = $s - 1;
            if ($chkatts[$sless] AND $_POST[splitoptions] == "Yes") {
                $firstopt = explode(":", $chkatts[$sless]);
                $showproduct = " $firstopt[0]";
                $showprice = $setprice + $firstopt[1];
// Check to ensure inventory is okay
                $invquery = "SELECT ID FROM " . $DB_Prefix . "_inventory WHERE ProductID='$itemrow[ID]' ";
                $invquery .= "AND Attribute='$firstopt[0]' AND Quantity='0'";
                $invresult = mysqli_query($dblink, $invquery) or die ("Unable to select options. Try again later.");
                $invchk = mysqli_num_rows($invresult);
// Get alpha character for the number
                if ($s < 27)
                    $showchr = strtoupper(chr($s + 96));
                else
                    $showchr = ".$s";
                $showofferid = $itemrow[ID] . $showchr;
            } else {
                $showproduct = "";
                $showprice = $setprice;
                $showofferid = $itemrow[ID];
            }
            $showprice = number_format($showprice, 2);
            if ($invchk == 0 AND $showurl AND $showitem AND $showprice AND $showcategory) {
                $recordlist .= "\r\n";
                $recordlist .= "$showurl\t";
                $recordlist .= "$showitem$showproduct\t";
                $recordlist .= "$showdesc\t";
                $recordlist .= "$showprice\t";
                $recordlist .= "$showimage\t";
                $recordlist .= "$showcategory\t";
                $recordlist .= "$showofferid\t";
                $recordlist .= "$showcatalog\t";
                $recordlist .= "$showcurrency";
            }
        }
    }

// START TABLE INFO
    echo "<div align=\"center\">";
    echo "<center>";
    echo "<table border=0 cellpadding=3 cellspacing=0 class=\"generaltable\">";
    echo "<tr>";
    echo "<td align=\"center\" class=\"fieldname\">";
    echo "Froogle Feeder";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align=\"center\">";
// Create feeder and post to Froogle
    if ($recordlist) {
        $feederdata = $headers . $recordlist;
        $froog_feed = "$Home_Path/$Adm_Dir/files/$froogleuser.txt";
        $sql_path = "$Home_Path/$Adm_Dir/files";
        if (!empty($ftp_site)) {
            $fsql_path = "$ftp_path/$Adm_Dir/files";
            $conn_id = @ftp_connect($ftp_site);
            $login_result = @ftp_login($conn_id, $ftp_user, $ftp_pass);
            @ftp_site($conn_id, "CHMOD " . $chmod_update . " " . $fsql_path);
        }
        $handle = fopen($froog_feed, "w+");
        if (!$handle)
            die ("Could not create froogle file");
        fwrite($handle, "$feederdata");
        fclose($handle);
        if (!empty($ftp_site)) {
            @ftp_site($conn_id, "CHMOD " . $chmod_file . " " . $fsql_path);
            @ftp_close($conn_id);
        }

// Set remote and local files
        $remotefile = "$froogleuser.txt";
        $localfile = "files/$froogleuser.txt";
        $failed = "<p class=\"error\" align=\"center\">FAILED - PLEASE <a href=\"froogle.php\" class=\"error\">TRY AGAIN</a></p>";
        $accept = "<p class=\"error\" align=\"center\">SUCCESS - FILE HAS BEEN SENT</p>";

        if ($deletefile == "Yes")
            echo "Status of Froogle Feeder Upload:";
        else
            echo "Status of <a href=\"$localfile\" target=\"_blank\">Froogle Feeder Upload</a>:";
        $sentfile = "No";
// Connect via FTP
        $con = @ftp_connect($froogleserver);
        if (!$con)
            echo "<br>Could not connect to <i>$froogleserver</i> for user <i>$froogleuser</i>.$failed";
        else {
            echo "<br>Connected to <i>$froogleserver</i> for user <i>$froogleuser</i>.";
// Log In via FTP
            $login = @ftp_login($con, $froogleuser, $frooglepass);
            if (!$login)
                echo "<br>Could not log in to <i>$froogleserver</i> for user <i>$froogleuser</i>.$failed";
            else {
                echo "<br>Logged in to <i><i>$froogleserver</i></i> for user <i>$froogleuser</i>.";
// Upload the file
                $upload = @ftp_put($con, $remotefile, $localfile, FTP_ASCII);
                if (!$upload)
                    echo "<br>FTP upload has failed.$failed";
                else {
                    $sentfile = "Yes";
                    echo "<br>Uploaded <i>$localfile</i> to <i>$froogleserver</i> as <i>$remotefile</i>.$accept";
                }
            }
        }

// close the FTP stream 
        if ($con)
            ftp_close($con);
        if (file_exists($localfile) AND $deletefile == "Yes") {
            if (!empty($ftp_site)) {
                $ftp_con = @ftp_connect($ftp_site);
                $ftp_login = @ftp_login($ftp_con, $ftp_user, $ftp_pass);
                $ftppagename = "$ftp_path/$localfile";
                $ftp_del_file = @ftp_delete($ftp_con, $ftppagename);
                if (!$ftp_con OR !$ftp_login OR !$ftp_del_file)
                    die("Could not remove page");
            } else
                @unlink($localfile) or die("Could not remove page.");
        }
    } else
        echo "Data could not be created.";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</center>";
    echo "</div>";
} else {
    $fvarquery = "SELECT Froogle, Currency FROM " . $DB_Prefix . "_vars WHERE ID='1'";
    $fvarresult = mysqli_query($dblink, $fvarquery) or die ("Unable to select. Try again later.");
    $fvarrow = mysqli_fetch_row($fvarresult);
    $froogleinfo = explode("~", $fvarrow[0]);
    if ($froogleinfo[0])
        $froogle_server = $froogleinfo[0];
    else
        $froogle_server = "hedwig.google.com";
    $froogle_user = $froogleinfo[1];
    $froogle_pass = $froogleinfo[2];
    if ($froogleinfo[3])
        $froogle_conv = $froogleinfo[3];
    else if ($fvarrow[1] == "CAD$")
        $froogle_conv = "CAD";
    else if ($fvarrow[1] == "�")
        $froogle_conv = "GPB";
    else if ($fvarrow[1] == "�")
        $froogle_conv = "JPY";
    else
        $froogle_conv = "USD";
    ?>

    <form method="POST" action="froogle.php">
        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                    <tr>
                        <td align="center" class="fieldname" colspan="4">Froogle File</td>
                    </tr>
                    <tr>
                        <td colspan="4">If you use Froogle to showcase your products and have a Froogle
                            user name and password, enter this information below to load your product catalog
                            automatically into the Froogle system. Only instock items will be listed. If you don't
                            yet have a Froogle account, you can view more information about this system at
                            <a href="https://www.google.com/froogle/merchants/" target="_blank">Froogle.com</a>.
                            Please note that we are not affiliated with Froogle in any way - we simply provide
                            you with this tool to help you load your products easily.
                        </td>
                    </tr>
                    <tr>
                        <td align="right" nowrap>Froogle Server:</td>
                        <td colspan="3"><input type="text" name="froogleserver" value="<?php echo "$froogle_server"; ?>"
                                               size="30">
                            &nbsp; &nbsp; &nbsp;<input type="checkbox" name="deletefile" value="Yes" checked>Delete File
                        </td>
                    </tr>
                    <tr>
                        <td align="right" nowrap>User Name:</td>
                        <td align="left"><input type="text" name="froogleuser" value="<?php echo "$froogle_user"; ?>"
                                                size="10"></td>
                        <td align="right" nowrap>Password:</td>
                        <td align="left"><input type="password" name="frooglepass"
                                                value="<?php echo "$froogle_pass"; ?>" size="10"></td>
                    </tr>
                    <tr>
                        <td align="right" nowrap>ISO Currency:</td>
                        <td><input type="text" name="froogleconversion" value="<?php echo "$froogle_conv"; ?>" size="10"
                                   maxlength="3"></td>
                        <td align="right" nowrap>Split Options:</td>
                        <td align="left">
                            <select size="1" name="splitoptions">
                                <option selected value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center" colspan="4">
                            <input type="hidden" name="mode" value="submit">
                            <input type="submit" class="button" value="Create" name="Submit">
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <?php
}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>
