<?php
require_once("../stconfig.php");
$finish_msg = "Order #$id was submitted.\r\n";

// GET ADMIN EMAIL AND MALS ACCOUNT
$varquery = "SELECT AdminEmail, MalsCart, InventoryLimit FROM " . $DB_Prefix . "_vars";
$varresult = mysqli_query($dblink, $varquery) or die ("Unable to select your system variables. Try again later.");
$varrow = mysqli_fetch_row($varresult);
$adminemail = $varrow[0];
$invlimit = $varrow[2];
$malscart = $varrow[1];

// LOOP THROUGH CART FIELDS
while (list($fieldname, $fieldvalue) = each($_GET)) {
    if (is_array($fieldvalue)) {
        for ($f = 0; $f < count($fieldvalue); ++$f) {
            $finish_msg .= "$$fieldname = \"$fieldvalue[$f]\"\r\n";
        }
    } else
        $finish_msg .= "$$fieldname = \"$fieldvalue\"\r\n";
}

if (isset($username) AND isset($cart) AND isset($id)) {

    if ($malscart == $username) {
        $finish_msg .= "Cart ID is valid.\r\n";

// FORMAT ORDER DATA
        $today = date("Y-m-d");
        $method = addslash_mq($method);
        $shippingzone = addslash_mq($shipping_zone);
        $invname = addslash_mq($inv_name);
        $invcompany = addslash_mq($inv_company);
        $invaddress = addslash_mq($inv_addr1);
        $invcity = addslash_mq($inv_addr2);
        $invstate = addslash_mq($inv_state);
        $invzip = addslash_mq($inv_zip);
        $invcountry = addslash_mq($inv_country);
        $shipname = addslash_mq($del_name);
        $shipaddress = addslash_mq($del_addr1);
        $shipcity = addslash_mq($del_addr2);
        $shipstate = addslash_mq($del_state);
        $shipzip = addslash_mq($del_zip);
        $shipcountry = addslash_mq($del_country);
        $extrainfo = addslash_mq($extra);
        if ($edata)
            $extrainfo .= " " . addslash_mq($edata);
        $message = addslash_mq($message);

// WAS ORDER ALREADY PLACED?
        $chkquery = "SELECT ID FROM " . $DB_Prefix . "_orders WHERE OrderNumber='$id' AND OrderDate='$today'";
        $chkresult = mysqli_query($dblink, $chkquery) or die ("Unable to select order. Try again later.");
        if (mysqli_num_rows($chkresult) == 0) {
            $insquery = "INSERT INTO " . $DB_Prefix . "_orders (OrderNumber, IPAddress, OrderDate, Method, Discount, ";
            $insquery .= "Subtotal, Shipping, Tax, Total, ShippingZone, InvName, InvCompany, InvAddress, ";
            $insquery .= "InvCity, InvState, InvZip, InvCountry, Email, Phone, Fax, ShipName, ShipAddress, ";
            $insquery .= "ShipCity, ShipState, ShipZip, ShipCountry, ShipPhone, Extra, SessData, ";
            $insquery .= "Voucher, VoucherVal, Message, Status) VALUES ('$id', '$ip', '$today', '$method', ";
            $insquery .= "'$discount', '$subtotal', '$shipping', '$tax', '$total', '$shippingzone', '$invname', ";
            $insquery .= "'$invcompany', '$invaddress', '$invcity', '$invstate', '$invzip', '$invcountry', '$email', ";
            $insquery .= "'$tel', '$fax', '$shipname', '$shipaddress', '$shipcity', '$shipstate', '$shipzip', '$shipcountry', ";
            $insquery .= "'$del_tel', '$extrainfo', '$sd', '$voucher', '$vval', '$message', 'On Order')";
            $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
            $setordid = mysqli_insert_id($dblink);
            $finish_msg .= "Order information was added.\r\n";

// SEPARATE CART CONTENTS
            $splitcart = explode("~", $cart);
            $totalprods = count($splitcart);
            $finish_msg .= "Start product loop.\r\n";
            $invmessage = "";
            for ($i = 0; $i < $totalprods; ++$i) {

                $splitprods = explode(":", $splitcart[$i]);
                $productinfo = trim($splitprods[0]);
                $productinfo = str_replace("{b}", "", $productinfo);
                $productinfo = str_replace("{/b}", "", $productinfo);
                $quantity = trim($splitprods[1]);
                $price = trim($splitprods[2]);
                $units = trim($splitprods[3]);
                $idinfo = trim($splitprods[4]);

// SEPARATE PRODUCT OPTIONS
                $optioninfo = explode("{br}", $productinfo);
                $totalopts = count($optioninfo);
                $prodinfo = explode("#", $optioninfo[0]);
                $productname = trim($prodinfo[0]);
                $catalog = trim($prodinfo[1]);
                if ($catalog)
                    $productname = $catalog . ". " . $productname;

// LOOP THROUGH OPTIONS IF NEEDED
                $optsaledisplay = "";
                if ($totalopts > 0) {
                    for ($o = 1; $o < $totalopts; ++$o) {
                        $optinfo = explode(" - ", $optioninfo[$o]);
                        $optionname[$o] = trim($optinfo[0]);
                        $optionval[$o] = trim($optinfo[1]);
                        if ($o > 1)
                            $optsaledisplay .= ", ";
                        $optsaledisplay .= $optionname[$o] . "-" . $optionval[$o];
                    }
                }

// PULL OUT ID INFORMATION
                $idnums = explode("-", $idinfo);
                $itemid = trim($idnums[0]);
                $registrant = trim($idnums[1]);
                $regprodid = trim($idnums[2]);
                $wholesaleid = trim($idnums[3]);

                $finish_msg .= "Product: $productname\r\n";
                $finish_msg .= "Options: $optsaledisplay\r\n";
                $finish_msg .= "Catalog: $catalog\r\n";
                $finish_msg .= "Qty: $quantity\r\n";
                $finish_msg .= "Price: $price\r\n";
                $finish_msg .= "Units: $units\r\n";
                $finish_msg .= "Product ID: $itemid\r\n";
                $finish_msg .= "Reg ID: $registrant\r\n";
                $finish_msg .= "Reg Prod: $regprodid\r\n";
                $finish_msg .= "WS ID: $wholesaleid\r\n";

// UPDATE GIFT CERTIFICATES
                if ($itemid == "GC") {
// Determine next issue number, based on the cost
                    $expmo = trim($idnums[1]);
                    $issuepos = trim($idnums[2]);
                    $mxquery = "SELECT MAX(IssueNum) FROM " . $DB_Prefix . "_giftcerts WHERE Amount = '$price'";
                    $mxresult = mysqli_query($dblink, $mxquery) or die ("Unable to select. Try again later.");
                    $mxrow = mysqli_fetch_row($mxresult);
                    if (!$mxrow[0])
                        $issuenum = $issuepos * 1000;
                    else
                        $issuenum = $mxrow[0] + 1;

// Pull gift certificate to/from/address
                    $toinfo = addslash_mq($optionval[1]);
                    $frominfo = addslash_mq($optionval[2]);
                    $sendinfo = addslash_mq($optionval[3]);
                    $exp_date = date("n/j/y", mktime(0, 0, 0, date("m") + $expmo, date("d"), date("Y")));

// Enter the gift certificate information
                    $insquery = "INSERT INTO " . $DB_Prefix . "_giftcerts (ToInfo,�FromInfo,�IssueNum,�Amount,�";
                    $insquery .= "IssuedOrder,�DateIssued,�SendTo) VALUES ('$toinfo', '$frominfo', '$issuenum', ";
                    $insquery .= "'$price', '$id', '$today', '$sendinfo')";
                    $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");

// Send notification email to site owner
                    $gcmsg = "The following gift certificate has been purchased:\r\n\r\n";
                    $gcmsg .= "Issue Number: $issuenum\r\n";
                    $gcmsg .= "Expiration Date: $exp_date\r\n";
                    $gcmsg .= "Purchased By: $frominfo\r\n";
                    $gcmsg .= "Issued To: $toinfo\r\n";
                    $gcmsg .= "Send To: $sendinfo\r\n\r\n";
                    $gcmsg .= "This certificate has NOT yet been sent to the recipient. Please email ";
                    $gcmsg .= "or mail the certificate (based on your settings) to the recipient, with ";
                    $gcmsg .= "instructions explaining how to use the certificate. Recipients should enter ";
                    $gcmsg .= "the issue number in the Voucher box on your web site shopping cart to cash ";
                    $gcmsg .= "the voucher. It is important that they are aware that they cannot receive ";
                    $gcmsg .= "fees or other vouchers back if they do not use the entire voucher in one ";
                    $gcmsg .= "shopping session. More than one voucher cannot be used at one time. For ";
                    $gcmsg .= "more information about vouchers, please check the Mals-E instructions.";
                    @mail("$adminemail", "Gift Certificate Purchase", "$gcmsg", "From: $adminemail\r\nReply-To: $adminemail");
                } // START UPDATE PRODUCT INVENTORY
                else {

// UPDATE INVENTORY QUANTITIES IF ITEM HAS OPTIONS
                    if ($totalopts > 0) {
                        $checkqtyquery = "SELECT Quantity, ID FROM " . $DB_Prefix . "_inventory WHERE ProductID='$itemid' AND Attribute='$optionval[1]'";
                        $checkqtyresult = mysqli_query($dblink, $checkqtyquery) or die ("Unable to select attribute inventory. Try again later.");
                        $checkqtynum = mysqli_num_rows($checkqtyresult);
                        if ($checkqtynum == 1) {
                            $checkqtyrow = mysqli_fetch_row($checkqtyresult);
                            $newinvqty = $checkqtyrow[0] - $quantity;
                            if ($newinvqty < 0)
                                $newinvqty = 0;
                            $updqtyquery = "UPDATE " . $DB_Prefix . "_inventory SET Quantity='$newinvqty' WHERE ID='$checkqtyrow[1]'";
                            $updqtyresult = mysqli_query($dblink, $updqtyquery) or die("Unable to update. Please try again later.");
                            $countquery = "SELECT SUM(Quantity) FROM " . $DB_Prefix . "_inventory WHERE ProductID='$itemid'";
                            $countresult = mysqli_query($dblink, $countquery) or die("Unable to count. Please try again later.");
                            $countrow = mysqli_fetch_row($countresult);
                            $updinvquery = "UPDATE " . $DB_Prefix . "_items SET Inventory='$countrow[0]' WHERE ID='$itemid'";
                            $updinvresult = mysqli_query($dblink, $updinvquery) or die("Unable to update. Please try again later.");
                            if ($newinvqty == 0)
                                $invmessage .= "\r\n $productname - $optionval[1] is sold out";
                            else if ($newinvqty <= $invlimit)
                                $invmessage .= "\r\n $productname - $optionval[1] was updated from $checkqtyrow[0] to $newinvqty";
                        }
                    }

// UPDATE INVENTORY QUANTITIES IF ITEM HAS NO OPTIONS
                    if ($totalopts == 0 OR $checkqtynum == 0) {
                        $checkinvquery = "SELECT Inventory FROM " . $DB_Prefix . "_items WHERE ID='$itemid' AND Inventory > 0";
                        $checkinvresult = mysqli_query($dblink, $checkinvquery) or die ("Unable to check item inventory. Try again later.");
                        $checkinvnum = mysqli_num_rows($checkinvresult);
                        if (mysqli_num_rows($checkinvresult) == 1) {
                            $checkinvrow = mysqli_fetch_row($checkinvresult);
                            $newinv = $checkinvrow[0] - $quantity;
                            if ($newinv < 0)
                                $newinv = 0;
                            $updinvquery = "UPDATE " . $DB_Prefix . "_items SET Inventory='$newinv' WHERE ID='$itemid'";
                            $updinvresult = mysqli_query($dblink, $updinvquery) or die("Unable to update. Please try again later.");
                            if ($newinv == 0)
                                $invmessage .= "\r\n$productname is sold out";
                            else if ($newinv <= $invlimit)
                                $invmessage .= "\r\n$productname was updated from $checkinvrow[0] to $newinv";
                        }
                    }

                }
// END UPDATE PRODUCT INVENTORY

// UPDATE SALES RECORD EVEN IF NO INV CHANGES
                if ($optsaledisplay)
                    $product_display = $productname . ": " . $optsaledisplay;
                else
                    $product_display = $productname;
                $itemname = addslash_mq($product_display);
                $inssalesquery = "INSERT INTO " . $DB_Prefix . "_sales (Quantity, Item, Price, Units, DateSold, OrderNumber) VALUES ('$quantity', '$itemname', '$price', '$units', '$today', '$id')";
                $inssalesresult = mysqli_query($dblink, $inssalesquery) or die("Unable to add. Please try again later.");

// UPDATE WISH LIST IF NEEDED
                if ($registrant AND $regprodid) {
                    $updquery = "UPDATE " . $DB_Prefix . "_reglist SET QtyReceived=QtyReceived+$quantity ";
                    $updquery .= "WHERE ID='$regprodid' AND RegistryID='$registrant'";
                    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
                }

// UPDATE WHOLESALE ORDER IF NEEDED
                if ($wholesaleid) {
                    $updquery = "UPDATE " . $DB_Prefix . "_orders SET WholesaleID='$wholesaleid' WHERE ID='$setordid'";
                    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
                }

            }
            $finish_msg .= "End product loop.\r\n";
            if ($invmessage) {
                $finish_msg .= "$invmessage\r\n";
                @mail("$adminemail", "Inventory Update", "The following changes have been made to your inventory:$invmessage", "From: $adminemail\r\nReply-To: $adminemail");
            }

        } else
            $finish_msg .= "Order information was NOT added.\r\n";
    } else
        $finish_msg .= "Cart ID is NOT valid.\r\n";
} else
    $finish_msg .= "Not all information was entered.\r\n";
$show_finish_msg = str_replace("\r\n", "<br>", $finish_msg);
echo "$show_finish_msg";
// mail("$adminemail", "Mals Test", "$finish_msg", "From: $adminemail\r\nReply-To: $adminemail");


?>