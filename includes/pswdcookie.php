<?php
require_once("../stconfig.php");
// First check to make sure old info is correct
$err_msg = "<p align=\"center\">Sorry, but your user name or password was not correct. Please <a href=\"index.php$mastaddl\">go back</a> and try again.</p>";
$pswdquery = "SELECT AdminPass, OpenSet, URL FROM " . $DB_Prefix . "_vars WHERE ID=1";
$pswdresult = mysqli_query($dblink, $pswdquery) or die ("Unable to select your system variables. Try again later.");
if (mysqli_num_rows($pswdresult) == 1) {
// Check for correct password
    $pswdrow = mysqli_fetch_row($pswdresult);
    if ($_COOKIE['adminpswd'] != $pswdrow[0] OR $_COOKIE['adminusr'] != $Admin_User)
        die($err_msg);
} else
    die($err_msg);
// Update password if new info is provided
if (isset($_POST[newpass]) AND isset($_POST[retypepass]) AND $_POST[pmode] == "update" AND strlen($_POST[newpass]) < 25 AND ctype_alnum($_POST[newpass])) {
// If new password does not equal retyped password, or passwords are missing
    if ($_POST[newpass] != $_POST[retypepass])
        $pssmode = "missing";
    else {
// If password info is correct, continue to update password
        $adminpswd = md5($_POST[newpass]);
        setcookie("adminpswd", "$adminpswd", 0, "/", "", 0);
        $updquery = "UPDATE " . $DB_Prefix . "_vars SET AdminPass='$adminpswd' WHERE ID=1";
        $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
        $gotolink .= "password.php?scs=y";
        $gotosize = strlen($gotolink);
        @header("location: $gotolink");
        @header("Content-type: text/plain");
        @header("Content-Length: $gotosize");
        @header("Connection: close");
        die("<p align=\"center\">Your password has been updated. <a href=\"$gotolink\">Continue</a>.</p>");
    }
}
mysqli_close($dblink);

?>
