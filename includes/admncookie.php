<?php
include_once("../stconfig.php");
if (isset($_POST[adminuser]) AND isset($_POST[adminpass])) {
    if (strlen($_POST[adminuser]) > 25 OR strlen($_POST[adminpass]) > 25 OR !ctype_alnum($_POST[adminuser]) OR !ctype_alnum($_POST[adminpass]))
        die ("Invalid Input");
    $adminusr = $_POST[adminuser];
    setcookie("adminusr", "$adminusr", 0, "/", "", 0);
    $adminpswd = md5($_POST[adminpass]);
    setcookie("adminpswd", "$adminpswd", 0, "/", "", 0);
    $adminmstr = $_POST[adminmaster];
    setcookie("adminmstr", "$adminmstr", 0, "/", "", 0);
    $gotolink .= "admin.php";
    $gotosize = strlen($gotolink);
    @header("location: $gotolink");
    @header("Content-type: text/plain");
    @header("Content-Length: $gotosize");
    @header("Connection: close");
    die("<p align=\"center\">You have been logged in. <a href=\"$gotolink\">Continue</a>.</p>");
}
?>