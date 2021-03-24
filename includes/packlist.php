<html>

<head>
    <title>Administration</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<?php
include("open.php");
if ($ordid) {
    $ordquery = "SELECT * FROM " . $DB_Prefix . "_orders WHERE OrderNumber='$ordid'";
    $ordresult = mysqli_query($dblink, $ordquery) or die ("Unable to select. Try again later.");
    $ordnum = mysqli_num_rows($ordresult);
}

if ($ordnum == 1) {
    $ordrow = mysqli_fetch_array($ordresult);
    if ($ordrow[OrderDate] != 0) {
        $splitodate = explode("-", $ordrow[OrderDate]);
        $orderdate = date("n/j/y", mktime(0, 0, 0, $splitodate[1], $splitodate[2], $splitodate[0]));
    }
    $method = $ordrow[Method];
    $discount = number_format($ordrow[Discount], 2);
    $subtotal = number_format($ordrow[Subtotal], 2);
    $shipping = number_format($ordrow[Shipping], 2);
    $tax = number_format($ordrow[Tax], 2);
    $total = number_format($ordrow[Total], 2);
    $shipzone = $ordrow[ShippingZone];
    $invname = stripslashes($ordrow[InvName]);
    $invcompany = stripslashes($ordrow[InvCompany]);
    $invaddress = stripslashes($ordrow[InvAddress]);
    $invcity = stripslashes($ordrow[InvCity]);
    $invstate = stripslashes($ordrow[InvState]);
    $invzip = $ordrow[InvZip];
    $invcountry = stripslashes($ordrow[InvCountry]);
    $email = $ordrow[Email];
    $phone = $ordrow[Phone];
    $fax = $ordrow[Fax];
    if ($ordrow[ShipName])
        $shipname = stripslashes($ordrow[ShipName]);
    else
        $shipname = $invname;
    if ($ordrow[ShipAddress])
        $shipaddress = stripslashes($ordrow[ShipAddress]);
    else
        $shipaddress = $invaddress;
    if ($ordrow[ShipCity])
        $shipcity = stripslashes($ordrow[ShipCity]);
    else
        $shipcity = $invcity;
    if ($ordrow[ShipState])
        $shipstate = stripslashes($ordrow[ShipState]);
    else
        $shipstate = $invstate;
    if ($ordrow[ShipZip])
        $shipzip = $ordrow[ShipZip];
    else
        $shipzip = $invzip;
    if ($ordrow[ShipCountry])
        $shipcountry = stripslashes($ordrow[ShipCountry]);
    else
        $shipcountry = $invcountry;
    if ($ordrow[ShipPhone])
        $shipphone = $ordrow[ShipPhone];
    else
        $shipphone = $phone;
    $voucher = stripslashes($ordrow[Voucher]);
    $vval = number_format($ordrow[VoucherVal], 2);
    $message = stripslashes($ordrow[Message]);
    $status = $ordrow[Status];
    $tracknum = $ordrow[TrackingNumber];
    if ($ordrow[ShipDate] != 0) {
        $splitsdate = explode("-", $ordrow[ShipDate]);
        $shipdate = date("n/j/y", mktime(0, 0, 0, $splitsdate[1], $splitsdate[2], $splitsdate[0]));
    }
    $varquery = "SELECT SiteName, URL, AdminEmail, InvLogo, Address, Phone, Fax, Currency ";
    $varquery .= "FROM " . $DB_Prefix . "_vars WHERE ID='1'";
    $varresult = mysqli_query($dblink, $varquery) or die ("Unable to select. Try again later.");
    $varrow = mysqli_fetch_array($varresult);
    $myurl = "http://" . $varrow[URL];
    if (substr($varrow[InvLogo], 0, 7) == "http://")
        $mylogo = $varrow[InvLogo];
    else
        $mylogo = $myurl . "/" . $varrow[InvLogo];
    $mycompany = stripslashes($varrow[SiteName]);
    $myaddress = str_replace("\n", "<br>", stripslashes($varrow[Address]));
    $myemail = $varrow[AdminEmail];
    $myphone = $varrow[Phone];
    $myfax = $varrow[Fax];
    $currency = $varrow[Currency];
    ?>
    <table border="0" cellpadding="3" cellspacing="0" width="100%">
        <tr>
            <td width="100%" valign="middle" align="center" colspan="4">
                &nbsp;
            </td>
        </tr>
        <tr>
            <?php
            if ($mylogo) {
                echo "<td width=\"25%\" valign=\"top\" align=\"right\">";
                echo "<img src=\"$mylogo\" alt=\"$mycompany\" border=\"0\"></td>";
                echo "<td width=\"25%\" valign=\"top\" align=\"left\">";
            } else
                echo "<td width=\"50%\" colspan=\"2\" valign=\"top\" align=\"center\">";
            echo "<b>$mycompany</b><br>";
            if ($myaddress)
                echo "$myaddress<br>";
            if ($myphone)
                echo "Phone: $myphone<br>";
            if ($myfax)
                echo "FAX: $myfax<br>";
            if ($myurl)
                echo "$myurl<br>";
            if ($myemail)
                echo "$myemail";
            echo "</td>";
            ?>
            <td width="25%" align="center">
                <?php
                if ($pr == "n")
                    echo "<h3>RECEIPT</h3>";
                else
                    echo "<h3>PACKING LIST</h3>";
                ?>
                Customer #: <?php echo "$ordid"; ?><br>
                Order Date: <?php echo "$orderdate"; ?><br>
                <?php if ($pr != "n") echo "Paid Via: $method"; ?>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td valign="top" align="center" width="50%" colspan="2">
                <table border="0" cellspacing="0" cellpadding="3" width="95%">
                    <tr>
                        <td class="fieldname" align="center" colSpan="2" bgcolor="#000000" valign="middle">
                            <font color="#FFFFFF">SHIP TO:</font>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">Name:</td>
                        <td align="left" width="25%"><?php echo "$shipname"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">Address:</td>
                        <td align="left" width="25%"><?php echo "$shipaddress"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">City:</td>
                        <td align="left" width="25%"><?php echo "$shipcity"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">State:</td>
                        <td align="left" width="25%"><?php echo "$shipstate"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">Zip Code:</td>
                        <td align="left" width="25%"><?php echo "$shipzip"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">Country:</td>
                        <td align="left" width="25%"><?php echo "$shipcountry"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">Phone:</td>
                        <td align="left" width="25%"><?php echo "$shipphone"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">Ship Zone:</td>
                        <td align="left" width="25%"><?php echo "$shipzone"; ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="25%">Ship Date:</td>
                        <td align="left" width="25%"><?php echo "$shipdate"; ?></td>
                    </tr>
                </table>
            </td>
            <td valign="top" align="center" width="50%" colspan="2">
                <?php
                if ($pr != "n") {
                    ?>
                    <table border="0" cellspacing="0" cellpadding="3" width="95%">
                        <tr>
                            <td class="fieldname" align="center" colSpan="2" valign="middle" bgcolor="#000000">
                                <font color="#FFFFFF">BILL TO:</font>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">Name:</td>
                            <td align="left" width="25%"><?php echo "$invname"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">Company:</td>
                            <td align="left" width="25%"><?php echo "$invcompany"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">Address:</td>
                            <td align="left" width="25%"><?php echo "$invaddress"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">City:</td>
                            <td align="left" width="25%"><?php echo "$invcity"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">State:</td>
                            <td align="left" width="25%"><?php echo "$invstate"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">Zip Code:</td>
                            <td align="left" width="25%"><?php echo "$invzip"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">Country:</td>
                            <td align="left" width="25%"><?php echo "$invcountry"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">Phone:</td>
                            <td align="left" width="25%"><?php echo "$phone"; ?></td>
                        </tr>
                        <tr>
                            <td align="right" width="25%">Email:</td>
                            <td align="left" width="25%"><?php echo "$email"; ?></td>
                        </tr>
                    </table>
                    <?php
                }
                ?>
            </td>
        </tr>

        <tr>
            <td valign="top" align="center" width="100%" colspan="4">&nbsp;</td>
        </tr>
    </table>
    <div align="center">
        <table cellSpacing="0" cellPadding="3" border="0" width="95%">
            <tr>
                <td class="fieldname" width="10" bgcolor="#000000">
                    <font color="#FFFFFF">QTY</font>
                </td>
                <td class="fieldname" width="100%" colspan="2" bgcolor="#000000">
                    <font color="#FFFFFF">ITEM</font>
                </td>
                <?php
                if ($pr != "n") {
                    ?>
                    <td class="fieldname" align="right" width="30" bgcolor="#000000">
                        <font color="#FFFFFF">PRICE</font>
                    </td>
                    <td class="fieldname" align="right" width="30" bgcolor="#000000">
                        <font color="#FFFFFF">EXT</font>
                    </td>
                    <?php
                }
                ?>
            </tr>

            <?php
            $salequery = "SELECT * FROM " . $DB_Prefix . "_sales WHERE OrderNumber='$ordid'";
            $saleresult = mysqli_query($dblink, $salequery) or die ("Unable to select. Try again later.");
            while ($salerow = mysqli_fetch_array($saleresult)) {
                $qty = (float)$salerow[Quantity];
                $item = stripslashes($salerow[Item]);
                $price = $currency . number_format($salerow[Price], 2);
                $extension = $salerow[Quantity] * $salerow[Price];
                $ext = $currency . number_format($extension, 2);
                ?>
                <tr>
                    <td width="10"><?php echo "$qty"; ?></td>
                    <td width="100%" colspan="2"><?php echo "$item"; ?></td>
                    <?php
                    if ($pr != "n") {
                        ?>
                        <td align="right" width="30"><?php echo "$price"; ?></td>
                        <td align="right" width="30"><?php echo "$ext"; ?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>

            <tr>
                <td width="10" rowspan="6">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td width="100%" rowspan="6" valign="middle" align="center">
                    <table border="0" cellpadding="5" cellspacing="0" style="border: 1 solid #000000" width="90%"
                           height="100">
                        <tr>
                            <td valign="top" align="left">Notes: <?php echo "$message"; ?></td>
                        </tr>
                    </table>
                </td>
                <td width="10" rowspan="6">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <?php
                if ($pr != "n")
                {
                ?>
                <td align="left" width="30">Subtotal:</td>
                <td align="left" width="30"><?php echo "$currency$subtotal"; ?></td>
            </tr>
            <tr>
                <td align="left" width="30">Voucher:</td>
                <td align="left" width="30"><?php echo "$currency$vval"; ?></td>
            </tr>
            <tr>
                <td align="left" width="30">Discount:</td>
                <td align="left" width="30"><?php echo "$currency$discount"; ?></td>
            </tr>
            <tr>
                <td align="left" width="30">Shipping:</td>
                <td align="left" width="30"><?php echo "$currency$shipping"; ?></td>
            </tr>
            <tr>
                <td align="left" width="30">Tax:</td>
                <td align="left" width="30"><?php echo "$currency$tax"; ?></td>
            </tr>
            <tr>
                <td align="left" width="30">Total:</td>
                <td align="left" width="30"><?php echo "$currency$total"; ?></td>
                <?php
                }
                ?>
            </tr>
        </table>
    </div>
    <?php
} else
    echo "<p align=\"center\">No Records Found</a>";
?>
</body>

</html>