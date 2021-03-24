<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

// IF REGISTERED, ENTER INFO FIRST
if ($_POST[mode] == "register") {
// CHECK TO SEE IF USER NAME ALREADY EXISTS
    $exquery = "SELECT ID FROM " . $DB_Prefix . "_registry WHERE RegUser='$_POST[wshusr]'";
    $exresult = mysqli_query($dblink, $exquery) or die ("Unable to select. Try again later.");
    $exnum = mysqli_num_rows($exresult);
    if (!$_POST[name1] AND $Registry == "bridal")
        $regerror = "Please enter the bride's name.";
    else if (!$_POST[name2] AND $Registry == "bridal")
        $regerror = "Please enter the groom's name.";
    else if (!$_POST[name1] AND $Registry == "gift")
        $regerror = "Please enter the receiver's name.";
    else if (!$_POST[regemail])
        $regerror = "Please enter the registrant's email address.";
    else if (!$_POST[eventdate] AND $Registry == "bridal")
        $regerror = "Please enter the wedding date.";
    else if (!$_POST[eventdate] AND $Registry == "baby")
        $regerror = "Please enter the baby's due date.";
    else if ($exnum > 0)
        $regerror = "This user name has already been chosen. Please use another name.";
    else if (!$_POST[wshusr] OR !$_POST[wshpsd])
        $regerror = "Please enter a user name and password to use with the registry system.";
// IF AN ERROR MESSAGE EXISTS, WIPE USER INFO AND DISPLAY ERROR
    if ($regerror) {
        $wshusr = "";
        $wshpsd = "";
    } // IF NO ERROR, ADD INFO
    else {
        $addname1 = addslash_mq(stripbadstuff($name1));
        $addname2 = addslash_mq(stripbadstuff($name2));
        $addshipname = addslash_mq(stripbadstuff($shiptoname));
        $addshipaddress = addslash_mq(stripbadstuff($shiptoaddress));
        $addshipcity = addslash_mq(stripbadstuff($shiptocity));
        $addshipstate = addslash_mq(stripbadstuff($shiptostate));
        $addshipcountry = addslash_mq(stripbadstuff($shiptocountry));
        if ($eventdate != 0) {
            $splitdate = explode("/", stripbadstuff($eventdate));
            $dateofevent = date("Y-m-d", mktime(0, 0, 0, $splitdate[0], $splitdate[1], $splitdate[2]));
        }
        $thisdate = date("Y-m-d");
        $insquery = "INSERT INTO " . $DB_Prefix . "_registry (RegUser, RegPass, Email, RegName1, RegName2, ";
        $insquery .= "ShipToName, ShipToAddress, ShipToCity, ShipToState, ShipToZip, ShipToCountry, ";
        $insquery .= "EventDate, Type, CreateDate) VALUES ('$wshusr', '$wshpsd', '$regemail', '$addname1', ";
        $insquery .= "'$addname2', '$addshipname', '$addshipaddress', '$addshipcity', '$addshipstate', ";
        $insquery .= "'$shiptozip', '$addshipcountry', '$dateofevent', '$regtype', '$thisdate')";
        $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
    }
}

// SET WISH LIST USER
if (isset($_POST[wshusr]) AND isset($_POST[wshpsd])) {
    setcookie("rguser", "$_POST[wshusr]", 0, "", "", 0);
    $rguser = $_POST[wshusr];
    setcookie("rgpass", "$_POST[wshpsd]", 0, "", "", 0);
    $rgpass = $_POST[wshpsd];
} else if (isset($_COOKIE[rguser]) AND isset($_COOKIE[rgpass])) {
    $rguser = $_COOKIE[rguser];
    $rgpass = $_COOKIE[rgpass];
}

// DELET REGISTRANT
if ($deleteregistry == "Yes") {
// Double check entry
    $ckquery = "SELECT * FROM " . $DB_Prefix . "_registry WHERE ID='$registry_id' ";
    $ckquery .= "AND RegUser='$rguser' AND RegPass='$rgpass'";
    $ckresult = mysqli_query($dblink, $ckquery) or die ("Unable to select. Try again later.");
    if (mysqli_num_rows($ckresult) == 1) {
        $delquery = "DELETE FROM " . $DB_Prefix . "_registry WHERE ID='$registry_id' ";
        $delquery .= "AND RegUser='$rguser' AND RegPass='$rgpass'";
        $delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
        $dellquery = "DELETE FROM " . $DB_Prefix . "_reglist WHERE RegistryID='$registry_id'";
        $dellresult = mysqli_query($dblink, $dellquery) or die("Unable to delete. Please try again later.");
        setcookie("rguser", "", 0, "", "", 0);
        $rguser = "";
        setcookie("rgpass", "", 0, "", "", 0);
        $rgpass = "";
    }
}

// LOG OUT OF WISH LIST
if (isset($logout)) {
    setcookie("rguser", "", 0, "", "", 0);
    $rguser = "";
    setcookie("rgpass", "", 0, "", "", 0);
    $rgpass = "";
}
?>
