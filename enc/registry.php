<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

// SET HIDDEN VALUES FOR ADD
if ($pid AND $md == "a") {
    $addlink = "&md=$md&pid=$pid&q=$q&on=$on";
    $addhidden = "<input type=\"hidden\" name=\"md\" value=\"$md\">";
    $addhidden .= "<input type=\"hidden\" name=\"pid\" value=\"$pid\">";
    $addhidden .= "<input type=\"hidden\" name=\"q\" value=\"$q\">";
    $addhidden .= "<input type=\"hidden\" name=\"on\" value=\"$on\">";
    for ($i = 1; $i < $on; ++$i) {
        $addlink .= "&ov$i=${"ov$i"}";
        $addhidden .= "<input type=\"hidden\" name=\"ov$i\" value=\"${"ov$i"}\">";
    }
}

// CHECK USER NAME AND PASSWORD FOR REGISTRY
$regquery = "SELECT ID FROM " . $DB_Prefix . "_registry WHERE RegUser='$rguser' AND RegPass='$rgpass'";

$regresult = mysqli_query($dblink, $regquery) or die ("Unable to select. Try again later.");
$regnum = mysqli_num_rows($regresult);

// REGISTRY USER IS LOGGED IN
if (isset($rguser) AND isset($rgpass)) {
    // USER LOGGED IN CORRECTLY
    if ($regnum == 1) {
        $regrow = mysqli_fetch_row($regresult);

        // PRODUCT EXISTS - ADD
        if ($pid > 0 and $md == "a") {
            // Create options if they exist
            $optlist = array();
            for ($i = 1; $i <= $on; ++$i) {
                $optval = ${"ov$i"};
                // Find this position in the options
                $optquery = "SELECT Name, Attributes FROM " . $DB_Prefix . "_options WHERE ItemID='$pid' AND OptionNum='$i'";
                $optresult = mysqli_query($dblink, $optquery) or die ("Unable to select. Try again later.");
                $optrow = mysqli_fetch_row($optresult);
                $expatts = explode("~", $optrow[1]);
                for ($a = 0; $a < count($expatts); ++$a) {
                    $formatatt = explode(":", $expatts[$a]);
                    if ($optval == $a + 1)
                        $optlist[] = "$optrow[0] - $formatatt[0]";
                    else if ($a == 0 AND $optval == "y")
                        $optlist[] = "$optrow[0] - Yes";
                }
            }
            if ($optlist)
                $options = implode(", ", $optlist);
            $dateadded = date("Y-m-d");
            // Check for duplicates
            $chkquery = "SELECT ID FROM " . $DB_Prefix . "_reglist WHERE RegistryID='$regrow[0]' ";
            $chkquery .= "AND ProductID='$pid' AND Options='$options'";
            $chkresult = mysqli_query($dblink, $chkquery) or die ("Unable to select. Try again later.");
            $chknum = mysqli_num_rows($chkresult);
            if ($chknum == 0) {
                $insquery = "INSERT INTO " . $DB_Prefix . "_reglist (RegistryID, ProductID, Options, QtyWanted, QtyReceived, ";
                $insquery .= "DateAdded) VALUES ('$regrow[0]', '$pid', '$options', '$q', '0', '$dateadded')";
                $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
            } else {
                $updquery = "UPDATE " . $DB_Prefix . "_reglist SET QtyWanted=QtyWanted+$q, DateAdded='$dateadded' ";
                $updquery .= "WHERE RegistryID='$regrow[0]' AND ProductID='$pid' AND Options='$options'";
                $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
            }
        } // PRODUCT EXISTS - DELETE
        else if ($pid > 0 and $md == "d") {
            $delquery = "DELETE FROM " . $DB_Prefix . "_reglist WHERE ID='$pid'";
            $delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
        } // INCREASE QUANTITY WANTED
        else if ($pid > 0 and $md == "iw") {
            $updquery = "UPDATE " . $DB_Prefix . "_reglist SET QtyWanted=QtyWanted+1 WHERE ID='$pid'";
            $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
        } // DECREASE QUANTITY WANTED
        else if ($pid > 0 and $md == "dw") {
            $updquery = "UPDATE " . $DB_Prefix . "_reglist SET QtyWanted=QtyWanted-1 WHERE ID='$pid' AND QtyWanted > 1";
            $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
        }

        // SHOW LIST WITH EDIT AND DELETE CAPABILITIES
        if ($mode == "update") {
            include("$Inc_Dir/registry_update.php");
        } else if ($mode == "delreg") {
            include("$Inc_Dir/registry_delete.php");
        } else if ($mode == "viewlink") {
            include("$Inc_Dir/registry_link.php");
        } else {
            include("$Inc_Dir/registry_showlist.php");
        }
        ?>

        <?php
    } // USER HAS LOGGED OUT
    else if ($logout == "yes")
        echo "<p>Thank you. You have been logged out of the gift registry.</p>";

    // USER HAS BEEN DELETED
    else if ($_POST[deleteregistry] == "Yes")
        echo "<p>Thank you. Your gift registry has been removed.</p>";

    // USER DID NOT REGISTER CORRECTLY
    else if ($mode == "register") {
        echo "<p class=\"salecolor\">";
        if ($regerror)
            echo "$regerror";
        else
            echo "Sorry, your information could not be updated. Please try again.";
        echo "</p>";
        $show = "register";
    } // USER DID NOT LOG IN CORRECTLY
    else if ($mode == "login") {
        echo "<p class=\"salecolor\">Sorry, but your login information was not correct. Please try again.</p>";
        $show = "login";
    } // OTHERWISE GO RIGHT TO LOGIN BOX
    else if (!$show)
        $show = "login";

} // REGISTRY USER IS NOT LOGGED IN
else {

// CHECK TO SEE IF USER EXISTS
    $usrquery = "SELECT * FROM " . $DB_Prefix . "_registry WHERE RegUser='$rguser' AND ID='$rgid'";
    $usrresult = mysqli_query($dblink, $usrquery) or die ("Unable to select. Try again later.");
    $usrnum = mysqli_num_rows($usrresult);

    // PUBLIC LIST
    if ($usrnum == 1) {
        include("$Inc_Dir/registry_showlist.php");
    } // USER DOES NOT EXIST
    else if ($mode == "search") {
        include("$Inc_Dir/registry_results.php");
    } else if (!$show AND $pid)
        $show = "login";
    else if (!$show)
        $show = "search";

}

// SHOW LOG IN FORM
if ($show == "login") {
    include("$Inc_Dir/registry_login.php");
} // SHOW REGISTER FORM
else if ($show == "register") {
    if ($name1)
        $name1 = str_replace('"', "&quot;", stripslashes($name1));
    if ($name2)
        $name2 = str_replace('"', "&quot;", stripslashes($name2));
    if ($shiptoaddress)
        $shiptoaddress = str_replace('"', "&quot;", stripslashes($shiptoaddress));
    if ($shiptocity)
        $shiptocity = str_replace('"', "&quot;", stripslashes($shiptocity));
    if ($shiptostate)
        $shiptostate = str_replace('"', "&quot;", stripslashes($shiptostate));
    if ($shiptocountry)
        $shiptocountry = str_replace('"', "&quot;", stripslashes($shiptocountry));
    include("$Inc_Dir/registry_register.php");
}

// SHOW SEARCH FORM
if ($show == "search") {
    include("$Inc_Dir/registry_search.php");
} // SHOW FORGOT FORM
else if ($show == "forgot") {
    include("$Inc_Dir/registry_forgot.php");
}
?>
