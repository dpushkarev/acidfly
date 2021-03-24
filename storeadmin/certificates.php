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

$malsquery = "SELECT MalsCart, Currency FROM " . $DB_Prefix . "_vars WHERE ID='1'";
$malsresult = mysqli_query($dblink, $malsquery) or die ("Unable to select. Try again later.");
$malsrow = mysqli_fetch_row($malsresult);
$malsid = $malsrow[0];
$Currency = $malsrow[1];

if (!empty($certid) AND $mode == "Search") {
    $gcid = $certid;
    $mode = "edit";
}

$setpg_lower = "certificates";
$setpg_upper = ucfirst($setpg_lower);

if ($submit == "Activate $setpg_upper Page") {
    $updquery = "UPDATE " . $DB_Prefix . "_pages SET Active='Yes' WHERE PageName='$setpg_lower' AND PageType='optional'";
    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}
if ($submit == "Deactivate $setpg_upper Page") {
    $updquery = "UPDATE " . $DB_Prefix . "_pages SET Active='No' WHERE PageName='$setpg_lower' AND PageType='optional'";
    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
}

$getquery = "SELECT ID, Active FROM " . $DB_Prefix . "_pages WHERE PageName='$setpg_lower' AND PageType='optional'";
$getresult = mysqli_query($dblink, $getquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($getresult) == 1) {
    $getrow = mysqli_fetch_row($getresult);
    $pgid = $getrow[0];
    $setactive = $getrow[1];
}
if (!$setactive)
    $setactive = "No";

// Show details only if page is active
if ($setactive == "Yes") {
// ADD/EDIT SCREEN
    if ($mode == "add" OR $mode == "edit") {
// Get info if edited
        if ($mode == "edit" AND $gcid) {
            $gcquery = "SELECT * FROM " . $DB_Prefix . "_giftcerts WHERE ID='$gcid'";
            $gcresult = mysqli_query($dblink, $gcquery) or die ("Unable to select. Try again later.");
            $gcrow = mysqli_fetch_array($gcresult);
            $toinfo = str_replace('"', '&quot;', stripslashes($gcrow[ToInfo]));
            $frominfo = str_replace('"', '&quot;', stripslashes($gcrow[FromInfo]));
            $sendto = str_replace('"', '&quot;', stripslashes($gcrow[SendTo]));
            $comments = str_replace('"', '&quot;', stripslashes($gcrow[Comments]));
            $issuenum = $gcrow[IssueNum];
            $amount = number_format($gcrow[Amount], 2);
            $issuedorder = $gcrow[IssuedOrder];
            $redeemedorder = $gcrow[RedeemedOrder];
            if ($gcrow[DateIssued] != 0) {
                $splitidate = explode("-", $gcrow[DateIssued]);
                $dateissued = date("n/j/y", mktime(0, 0, 0, $splitidate[1], $splitidate[2], $splitidate[0]));
            }
            if ($gcrow[DateRedeemed] != 0) {
                $splitrdate = explode("-", $gcrow[DateRedeemed]);
                $dateredeemed = date("n/j/y", mktime(0, 0, 0, $splitrdate[1], $splitrdate[2], $splitrdate[0]));
            }
        }

        $ddquery = "SELECT * FROM " . $DB_Prefix . "_certificates";
        $ddresult = mysqli_query($dblink, $ddquery) or die ("Unable to select. Try again later.");
        $ddrow = mysqli_fetch_array($ddresult);
        $amt1 = $ddrow[Amount1];
        $amt2 = $ddrow[Amount2];
        $amt3 = $ddrow[Amount3];
        $amt4 = $ddrow[Amount4];
        $expmos = $ddrow[ExpireMonths];
        ?>

        <form action="certificates.php" method="POST">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="largetable">
                        <tr>
                            <td align="right" valign="top">Issued To:</td>
                            <td colspan="3" valign="top" align="left"><input type="text" name="toinfo"
                                                                             value="<?php echo "$toinfo"; ?>" size="42">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Send To:</td>
                            <td colspan="3" valign="top" align="left"><input type="text" name="sendto"
                                                                             value="<?php echo "$sendto"; ?>" size="42">
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" colspan="4" class="accent">
                                <?php
                                if ($mode == "edit" AND $gcid)
                                    echo "Note: Only change issue numbers if issue does not already exist.";
                                else
                                    echo "Note: Leave issue number blank to calculate number automatically.";
                                echo "<br>If issue number is duplicated, certificate info will not be saved.";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Amount:</td>
                            <td valign="top" align="left">
                                <select name="amount" size="1">
                                    <?php
                                    if ($amt1 > 0) {
                                        echo "<option ";
                                        if ($amount == $amt1 OR !$amount)
                                            echo "selected ";
                                        echo "value=\"1~$amt1\">$Currency$amt1</option>";
                                    }
                                    if ($amt2 > 0) {
                                        echo "<option ";
                                        if ($amount == $amt2)
                                            echo "selected ";
                                        echo "value=\"2~$amt2\">$Currency$amt2</option>";
                                    }
                                    if ($amt3 > 0) {
                                        echo "<option ";
                                        if ($amount == $amt3)
                                            echo "selected ";
                                        echo "value=\"3~$amt3\">$Currency$amt3</option>";
                                    }
                                    if ($amt4 > 0) {
                                        echo "<option ";
                                        if ($amount == $amt4)
                                            echo "selected ";
                                        echo "value=\"4~$amt4\">$Currency$amt4</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td align="right" valign="top">Issue Number:</td>
                            <td valign="top" align="left">
                                <input type="text" name="issuenum" value="<?php echo "$issuenum"; ?>" size="12">
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Purchased By:</td>
                            <td colspan="3" valign="top" align="left"><input type="text" name="frominfo"
                                                                             value="<?php echo "$frominfo"; ?>"
                                                                             size="42"></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Issue Order #:</td>
                            <td valign="top" align="left"><input type="text" name="issuedorder"
                                                                 value="<?php echo "$issuedorder"; ?>" size="12"></td>
                            <td align="right" valign="top">Issue Date:</td>
                            <td valign="top" align="left"><input type="text" name="dateissued"
                                                                 value="<?php echo "$dateissued"; ?>" size="12"></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Redeem Order #:</td>
                            <td valign="top" align="left"><input type="text" name="redeemedorder"
                                                                 value="<?php echo "$redeemedorder"; ?>" size="12"></td>
                            <td align="right" valign="top">Redeem Date:</td>
                            <td valign="top" align="left"><input type="text" name="dateredeemed"
                                                                 value="<?php echo "$dateredeemed"; ?>" size="12"></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top">Comments:</td>
                            <td colspan="3" valign="top" align="left"><textarea rows="2" name="comments"
                                                                                cols="36"><?php echo "$comments"; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" colspan="4">
                                <?php
                                if ($mode == "edit" AND $gcid)
                                    echo "<input type=\"hidden\" value=\"$gcid\" name=\"gcid\">";
                                echo "<input type=\"hidden\" value=\"$certid\" name=\"certid\">";
                                echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
                                echo "<input type=\"hidden\" value=\"Search\" name=\"mode\">";
                                ?>
                                <input type="submit" value="Update" name="submit" class="button">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>

        <?php
        echo "<p align=\"center\"><a href=\"certificates.php?mode=Search";
        echo "&certid=$certid&orderby=$orderby\">";
        echo "Back to Certificates</a></p>";
    } // DELETE SCREEN
    else if ($mode == "delete") {
        ?>
        <form action="certificates.php" method="POST">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="largetable">
                        <tr>
                            <td align="center">
                                Do you want to delete this certificate? This will remove<br>
                                the certificate permanently, and cannot be reversed.
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <?php
                                echo "<input type=\"hidden\" value=\"$gcid\" name=\"gcid\">";
                                echo "<input type=\"hidden\" value=\"$certid\" name=\"certid\">";
                                echo "<input type=\"hidden\" value=\"$orderby\" name=\"orderby\">";
                                echo "<input type=\"hidden\" value=\"Search\" name=\"mode\">";
                                ?>
                                <input type="submit" value="Yes" name="submit" class="button">
                                <input type="submit" value="No" name="submit" class="button"></td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
        echo "<p align=\"center\"><a href=\"certificates.php?mode=Search";
        echo "&certid=$certid&orderby=$orderby\">";
        echo "Back to Certificates</a></p>";
    } // SEARCH SCREEN
    else if ($mode == "Search") {
// UPDATE FILES
        if ($submit == "Update") {
// Strip out the amount
            $amtval = explode("~", $amount);
            $amt = $amtval[1];
            $rg = $amtval[0];
// Add or edit?
            if ($gcid)
                $issmsg = "Certificate Not Edited";
            else
                $issmsg = "Certificate Not Added";
// What is the next issue number?
            if ($issuenum == "") {
                $mxquery = "SELECT MAX(IssueNum) FROM " . $DB_Prefix . "_giftcerts WHERE Amount = '$amt'";
                $mxresult = mysqli_query($dblink, $mxquery) or die ("Unable to select. Try again later.");
                $mxrow = mysqli_fetch_row($mxresult);
                if (!$mxrow[0])
                    $issuenum = $rg * 1000;
                else
                    $issuenum = $mxrow[0] + 1;
            }
// Does the issue number range match the amount?
            $chk_range = substr($issuenum, 0, 1);
            if ($chk_range != $rg)
                echo "<p align=\"center\" class=\"error\"><b>Issue Range Incorrect - $issmsg</b></p>";
            else {
// Is issue number a duplicate?
                $issquery = "SELECT * FROM " . $DB_Prefix . "_giftcerts WHERE IssueNum='$issuenum'";
                if ($gcid)
                    $issquery .= " AND ID <> '$gcid'";
                $issresult = mysqli_query($dblink, $issquery) or die ("Unable to select. Try again later.");
                $issuecount = mysqli_num_rows($issresult);
// Issue is a duplicate
                if ($issuenum != "" AND $issuecount > 0)
                    echo "<p align=\"center\" class=\"error\"><b>Duplicate Issue Number - $issmsg</b></p>";
                else {
                    $addfrominfo = addslash_mq($frominfo);
                    $addtoinfo = addslash_mq($toinfo);
                    $addsendto = addslash_mq($sendto);
                    $addcomments = addslash_mq($comments);
                    if ($dateissued != 0) {
                        $splitidate = explode("/", $dateissued);
                        $date_issued = date("Y-m-d", mktime(0, 0, 0, $splitidate[0], $splitidate[1], $splitidate[2]));
                    }
                    if ($dateredeemed != 0) {
                        $splitrdate = explode("/", $dateredeemed);
                        $date_redeemed = date("Y-m-d", mktime(0, 0, 0, $splitrdate[0], $splitrdate[1], $splitrdate[2]));
                    }
// Edit
                    if ($gcid) {
                        $updquery = "UPDATE " . $DB_Prefix . "_giftcerts SET ToInfo='$addtoinfo', FromInfo='$addfrominfo', ";
                        $updquery .= "IssueNum='$issuenum', Amount='$amt', IssuedOrder='$issuedorder', ";
                        $updquery .= "DateIssued='$date_issued', RedeemedOrder='$redeemedorder', DateRedeemed='$date_redeemed', ";
                        $updquery .= "SendTo='$addsendto', Comments='$addcomments' WHERE ID='$gcid'";
                        $updresult = mysqli_query($dblink, $updquery) or die ("Unable to update. Try again later.");
                    } // Add
                    else {
                        $insquery = "INSERT INTO " . $DB_Prefix . "_giftcerts (ToInfo, FromInfo, IssueNum, Amount, ";
                        $insquery .= "IssuedOrder, DateIssued, RedeemedOrder, DateRedeemed, SendTo, Comments) ";
                        $insquery .= "VALUES ('$addtoinfo', '$addfrominfo', '$issuenum', '$amt', '$issuedorder', ";
                        $insquery .= "'$date_issued', '$redeemedorder', '$date_redeemed', '$addsendto', '$addcomments')";
                        $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
                    }
                }
            }
        }

// DELETE FILES
        if ($submit == "Yes" AND $gcid) {
            $delquery = "DELETE FROM " . $DB_Prefix . "_giftcerts WHERE ID='$gcid'";
            $delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
        }

        $dcquery = "SELECT ExpireMonths FROM " . $DB_Prefix . "_certificates";
        $dcresult = mysqli_query($dblink, $dcquery) or die ("Unable to select. Try again later.");
        $dcrow = mysqli_fetch_row($dcresult);
        $expmos = $dcrow[0];
        ?>

        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="largetable">
                    <?php
                    $gcquery = "SELECT * FROM " . $DB_Prefix . "_giftcerts ";
                    if ($_POST[certid] != "")
                        $gcquery .= "WHERE ID='$certid' ";
                    $gcquery .= "ORDER BY '$_POST[orderby]'";
                    $gcresult = mysqli_query($dblink, $gcquery) or die ("Unable to select. Try again later.");
                    if (mysqli_num_rows($gcresult) == 0)
                        echo "<tr><td align=\"center\">No gift certificate recipients could be found.</td></tr>";
                    else {
                        ?>
                        <tr class="fieldname">
                            <td>Issued To</td>
                            <td>Number</td>
                            <td>Amount</td>
                            <td colspan="2">Issued</td>
                            <td colspan="3">Redeemed</td>
                        </tr>
                        <?php
                        while ($gcrow = mysqli_fetch_array($gcresult)) {
                            if ($gcrow[DateIssued] != 0) {
                                $splitidate = explode("-", $gcrow[DateIssued]);
                                $issueddate = date("n/j/y", mktime(0, 0, 0, $splitidate[1], $splitidate[2], $splitidate[0]));
                                $expire_mo = date("m", mktime(0, 0, 0, $splitidate[1] + $expmos, $splitidate[2], $splitidate[0]));
                                $expire_yr = date("y", mktime(0, 0, 0, $splitidate[1] + $expmos, $splitidate[2], $splitidate[0]));
                            } else {
                                $issueddate = date("n/j/y");
                                $expire_mo = date("m", mktime(0, 0, 0, date("m") + $expmos, date("d"), date("Y")));
                                $expire_yr = date("y", mktime(0, 0, 0, date("m") + $expmos, date("d"), date("Y")));
                            }
                            if ($gcrow[DateRedeemed] != 0) {
                                $splitrdate = explode("-", $gcrow[DateRedeemed]);
                                $redeemeddate = date("n/j/y", mktime(0, 0, 0, $splitrdate[1], $splitrdate[2], $splitrdate[0]));
                            } else
                                $redeemeddate = "";
                            ?>
                            <tr>
                                <td><?php echo stripslashes($gcrow[ToInfo]); ?></td>
                                <td>
                                    <?php
                                    $issuenum = stripslashes($gcrow[IssueNum]);
                                    $cval = $issuenum{0};
                                    echo $cval . $expire_mo . $expire_yr . $issuenum;
                                    ?>
                                </td>
                                <td><?php echo $Currency . stripslashes($gcrow[Amount]); ?></td>
                                <td><?php echo stripslashes($gcrow[IssuedOrder]); ?></td>
                                <td><?php echo "$issueddate"; ?></td>
                                <td><?php echo stripslashes($gcrow[RedeemedOrder]); ?></td>
                                <td><?php echo "$redeemeddate"; ?></td>
                                <td>
                                    <?php
                                    echo "<a href=\"certificates.php?mode=edit&gcid=$gcrow[ID]";
                                    echo "&certid=$certid&orderby=$orderby\">Edit</a> | ";
                                    echo "<a href=\"certificates.php?mode=delete&gcid=$gcrow[ID]";
                                    echo "&certid=$certid&orderby=$orderby\">Delete</a>";
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    echo "<tr><td align=\"center\" colspan=\"8\">";
                    echo "<a href=\"certificates.php?mode=add&certid=$certid&orderby=$orderby\">";
                    echo "Add New</a></td></tr>";
                    ?>
                </table>
            </center>
        </div>
        <?php
        echo "<p align=\"center\"><a href=\"certificates.php\">Back to Certificates</a></p>";
    } else {
// SHOW INITIAL FORMS
        if ($mode == "Update" OR $mode == "malscode") {
            $chkquery = "SELECT * FROM " . $DB_Prefix . "_certificates";
            $chkresult = mysqli_query($dblink, $chkquery) or die ("Unable to select. Try again later.");
            $chknum = mysqli_num_rows($chkresult);
            if ($expiremonths <= 0)
                $expiremonths = 12;
            if ($expiremonths > 99)
                $expiremonths = 99;
            if ($name)
                $addname = addslash_mq($name);
            else
                $addname = "Gift Certificate";
            if ($sendinfo)
                $addsendinfo = addslash_mq($sendinfo);
            else
                $addsendinfo = "Email";

// Add prices
            if ($chknum == 0) {
                if ($amount1 > 0 OR $amount2 > 0 OR $amount3 > 0 OR $amount4 > 0) {
                    $insquery = "INSERT INTO " . $DB_Prefix . "_certificates (Name, Amount1, Amount2, Amount3, Amount4, ExpireMonths, SendInfo) VALUES ('$addname', '$amount1', '$amount2', '$amount3', '$amount4', '$expiremonths', '$addsendinfo')";
                    $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
                    $mode = "malscode";
                } else
                    echo "<p align=\"center\">Please enter at least one gift certificate amount.</p>";
            } // Edit prices
            else if ($chknum == 1) {
                $updquery = "UPDATE " . $DB_Prefix . "_certificates SET Name='$addname', Amount1='$amount1', Amount2='$amount2', Amount3='$amount3', Amount4='$amount4', ExpireMonths='$expiremonths', SendInfo='$addsendinfo'";
                $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
                $chkrow = mysqli_fetch_array($chkresult);
                if ($amount1 != $oldamount1 OR $amount2 != $oldamount2 OR $amount3 != $oldamount3 OR $amount4 != $oldamount4)
                    $mode = "malscode";
            }
        }

// Show voucher info if updates
        if ($mode == "malscode") {
            ?>

            <div align="center">
                <center>
                    <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                        <tr>
                            <td colspan="5" align="center" class="fieldname">Update Your Mals Account</td>
                        </tr>
                        <tr>
                            <td>
                                To finish your gift certificate setup, you will need to log in to your
                                <a href="mals.php?mode=gc">Mals Account</a> and follow the instructions on the screen
                                to update your gift vouchers. You will only need to use this procedure when you
                                first set up gift certificates, or when you change any of your gift certificate
                                setup information.
                            </td>
                        </tr>
                    </table>
                </center>
            </div>

            <?php
        }

// Show certificate amounts
        $certquery = "SELECT * FROM " . $DB_Prefix . "_certificates";
        $certresult = mysqli_query($dblink, $certquery) or die ("Unable to select. Try again later.");
        $certnum = mysqli_num_rows($certresult);
        if ($certnum == 1) {
            $certrow = mysqli_fetch_array($certresult);
            $amount1 = $certrow[Amount1];
            $amount2 = $certrow[Amount2];
            $amount3 = $certrow[Amount3];
            $amount4 = $certrow[Amount4];
            $expiremonths = $certrow[ExpireMonths];
            $stripname = str_replace('"', '&quot;', stripslashes($certrow[Name]));
            $stripsendinfo = str_replace('"', '&quot;', stripslashes($certrow[SendInfo]));
        }
        if (!$stripname)
            $stripname = "Gift Certificates";
        if (!$expiremonths)
            $expiremonths = 12;
        if (!$stripsendinfo)
            $stripsendinfo = "Email";
        ?>

        <form method="POST" action="certificates.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center" colspan="2" class="fieldname">Gift Certificates</td>
                        </tr>
                        <tr>
                            <td valign="middle" align="left" colspan="2">
                                <?php
                                if ($certnum == 1) {
                                    ?>
                                    To update any gift certificate information, modify the details below, then select 'Update' to
                                    save. If you change any certificate amounts, you will also need to update your Mals-E account
                                    to process the changes.
                                    <?php
                                } else {
                                    ?>
                                    Enter a brief gift certificate description (what the customer will see in the shopping cart,
                                    ie. "Gift Certificate&quot;) and the method you plan to use to send the certificates (for customer's
                                    viewing only, ie. "Email" or "USPS"). Then enter up to four gift certificate amounts for
                                    your customers to purchase, and the number of months the certificates will be
                                    good before they expire. When you are finished entering this information, select 'Update' to
                                    save, and follow the instructions to update your Mals account.
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">Description:</td>
                            <td valign="middle" align="left">&nbsp;
                                <input type="text" name="name" value="<?php echo "$stripname"; ?>" size="35"
                                       maxLength="50">
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">Send Via:</td>
                            <td valign="middle" align="left">&nbsp;
                                <input type="text" name="sendinfo" value="<?php echo "$stripsendinfo"; ?>" size="35"
                                       maxLength="50">
                            </td>
                        </tr>
                        <?php
                        for ($i = 1; $i <= 4; ++$i) {
                            ?>
                            <tr>
                                <td valign="middle" align="right">Certificate <?php echo "#$i"; ?>:</td>
                                <td valign="middle" align="left">
                                    <?php
                                    $amtval = ${"amount$i"};
                                    if ($amtval > 0)
                                        $amountvalue = "$amtval";
                                    else
                                        $amountvalue = "";
                                    echo "$Currency";
                                    echo "<input type=\"text\" name=\"amount$i\" value=\"$amountvalue\" size=\"10\">";
                                    echo "<input type=\"hidden\" name=\"oldamount$i\" value=\"$amountvalue\">";
                                    if ($amountvalue != "") {
                                        $mxquery = "SELECT MAX(IssueNum) FROM " . $DB_Prefix . "_giftcerts WHERE Amount='$amountvalue'";
                                        $mxresult = mysqli_query($dblink, $mxquery) or die ("Unable to select. Try again later.");
                                        $mxrow = mysqli_fetch_row($mxresult);
                                        if (!$mxrow[0])
                                            $mxval = $i * 1000;
                                        else
                                            $mxval = $mxrow[0] + 1;
                                        echo " <span class=\"smalltext\">(Next Issue #: $mxval)</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td valign="middle" align="right">Expire Within:</td>
                            <td valign="middle" align="left">&nbsp;
                                <input type="text" name="expiremonths" value="<?php echo "$expiremonths"; ?>" size="2"
                                       maxLength="2">
                                months of purchase
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" colspan="2">
                                <input type="submit" value="Update" name="mode" class="button">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>

        <form method="POST" action="certificates.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center" colspan="2"><b>View Certificates</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                Please Note: Certificates are <b>not</b> automatically emailed to your customers.
                                Therefore, you will need to email a certificate number or mail a paper certificate
                                (your choice) to every customer who receives a gift certificate.
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">Certificate:</td>
                            <td valign="middle" align="left">
                                <select size="1" name="certid">
                                    <option value="" selected>All Certificates</option>
                                    <?php
                                    $gctquery = "SELECT * FROM " . $DB_Prefix . "_giftcerts ORDER BY IssueNum";
                                    $gctresult = mysqli_query($dblink, $gctquery) or die ("Unable to select. Try again later.");
                                    while ($gctrow = mysqli_fetch_array($gctresult)) {
                                        $to_name = stripslashes($gctrow[ToInfo]);
                                        if ($gctrow[DateIssued] != 0) {
                                            $splitdate = explode("-", $gctrow[DateIssued]);
                                            $issdate = date("n/j/y", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
                                            $expire_mo = date("m", mktime(0, 0, 0, $splitdate[1] + $expiremonths, $splitdate[2], $splitdate[0]));
                                            $expire_yr = date("y", mktime(0, 0, 0, $splitdate[1] + $expiremonths, $splitdate[2], $splitdate[0]));
                                        } else {
                                            $issdate = date("n/j/y");
                                            $expire_mo = date("m", mktime(0, 0, 0, date("m") + $expiremonths, date("d"), date("Y")));
                                            $expire_yr = date("y", mktime(0, 0, 0, date("m") + $expiremonths, date("d"), date("Y")));
                                        }
                                        $issuen = stripslashes($gctrow[IssueNum]);
                                        $cnval = $issuen{0};
                                        echo "<option value=\"$gctrow[ID]\">";
                                        echo $cnval . $expire_mo . $expire_yr . $issuen;
                                        if ($issdate)
                                            echo "($issdate) ";
                                        echo "- $to_name</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="right">Order By:</td>
                            <td valign="middle" align="left">
                                <select name="orderby" size="1">
                                    <option selected value="ToInfo">Buyer Name</option>
                                    <option value="FromInfo">Recipient Name</option>
                                    <option value="IssueNum">Gift Certificate Number</option>
                                    <option value="DateIssued DESC">Most Recently Issued</option>
                                    <option value="DateRedeemed DESC">Most Recently Redeemed</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center" colspan="2">
                                <input type="submit" value="Search" name="mode" class="button">
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
        <?php
    }

}

if ($setactive == "No") {
    ?>

    <form method="POST" action="<?php echo "$setpg.php"; ?>">
        <div align="center">
            <center>
                <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                    <tr>
                        <td valign="middle">
                            Gift certificates can be used <b>only</b> if you have gift vouchers set up in the Mals-E
                            cart.
                            After setting up the gift certificates here, you will be given information that you will
                            need
                            to enter into the Mals-E cart setup to finish the gift certificate setup procedure.
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center">
                            <?php
                            echo "The $setpg_lower page is currently inactive. If you would like to<br>activate it, please click the button below.";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="middle" align="center">
                            <?php
                            echo "<input type=\"submit\" class=\"button\" value=\"Activate $setpg_upper Page\" name=\"submit\">";
                            ?>
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <?php
} else {
    echo "<p align=\"center\" class=\"smalltext\">";
    echo "<a href=\"pages.php?edit=yes&pgid=$pgid\">Contents</a> | ";
    echo "<a href=\"certificates.php?submit=Deactivate+Certificates+Page\">Deactivate</a></p>";
}

include("includes/links2.php");
include("includes/footer.htm");
?>
</body>

</html>