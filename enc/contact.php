<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

if ($_POST[Submit] == "Send") {
// Check for domain emails
    $toemailcheck = explode("@", $_POST[youremail]);
// Compare to input var
    $starttime = date("YmdHis", mktime(date("H"), date("i") - 10, date("s"), date("m"), date("d"), date("Y")));
    $chquery = "SELECT * FROM " . $DB_Prefix . "_check WHERE CheckVar='$_POST[checkvar]' AND DateTime>'$starttime'";
    $chresult = mysqli_query($dblink, $chquery) or die ("Unable to select. Try again later.");

    if (!$_POST[yourname] OR !$_POST[youremail] OR !$_POST[yourcomments])
        echo "<p>Sorry, but either your name, email address or comments could not be determined. Please go back and try again.</p>";
    else if (!$Admin_Email OR $Admin_Email == "")
        echo "<p>Sorry, but this form could not be processed. Please contact site administrator.</p>";
    else if (substr_count($urlbase, $toemailcheck[1]) > 0)
        echo "<p>Sorry, but messages cannot be sent from this domain. Please try again.</p>";
    else if (mysqli_num_rows($chresult) > 1)
        echo "<p>Sorry, but the form has timed out or has already been processed.</p>";
    else {
        $stripname = stripslashes(stripbadstuff($yourname));
        $stripcomments = stripslashes(stripbadstuff($yourcomments));
        $stripcomments = str_replace("@", " at ", $stripcomments);

        mail("$Admin_Email", "Web Site Comments", "The following request was submitted:

Name: $stripname
Email: $youremail
Comments: $stripcomments", "From: $youremail\r\nReply-To: $youremail");
        echo "<p>Thank you for your comments. We will respond to your request as soon as possible.</p>";

        $delquery = "DELETE FROM " . $DB_Prefix . "_check WHERE CheckVar='$_POST[checkvar]' AND DateTime>'$starttime'";
        $delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
    }
} else {
    $ipaddress = $_SERVER[REMOTE_ADDR];
// Create random characters
    mt_srand((double)microtime() * 1000000000);
    $rc = chr(mt_rand(97, 122));
    $rc .= chr(mt_rand(97, 122));
    $rc .= mt_rand(1, 9);
    $rc .= mt_rand(1, 9);
    $rc .= chr(mt_rand(97, 122));
    $rc .= mt_rand(1, 9);
    $rc .= mt_rand(1, 9);
    $rc .= chr(mt_rand(97, 122));
    $checkvar = md5($rc);
    $insquery = "INSERT INTO " . $DB_Prefix . "_check (IPAddress, CheckVar) VALUES ('$ipaddress', '$checkvar')";
    $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
    ?>

    <form action="<?php echo "contact.$pageext"; ?>" method="POST">
        <div align="center">
            <center>
                <table border="0" cellpadding="3" cellspacing="0">
                    <tr>
                        <td align="right" valign="top">Name:</td>
                        <td align="left" valign="top">
                            <input type="text" name="yourname" size="40">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">Email:</td>
                        <td align="left" valign="top">
                            <input type="text" name="youremail" size="40">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">Comments:</td>
                        <td align="left" valign="top">
                            <textarea rows="6" name="yourcomments" cols="34"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" colspan="2">
                            <?php echo "<input type=\"hidden\" name=\"checkvar\" value=\"$checkvar\">"; ?>
                            <input type="submit" value="Send" name="Submit" class="formbutton">
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <?php
}
?>
