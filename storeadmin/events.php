<html>

<head>
    <title>Administration</title>
    <link rel="stylesheet" type="text/css" href="includes/style.css">
    <script language="php">include("includes/htmlarea.php");</script>
</head>

<body
<script language="php">$java = "description"; include("includes/htmlareabody.php");</script>
>
<?php
include("includes/open.php");
include("includes/header.htm");
include("includes/links.php");

$thismonth = date("n", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
$thisday = date("j", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
$thisyear = date("Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

if ($Submit == "Add Event") {
    $date = $eventyear . "-" . $eventmonth . "-" . $eventday;
    $expiredate = $expireyear . "-" . $expiremonth . "-" . $expireday;
    $name = addslash_mq($name);
    $description = addslash_mq($description);
    $insquery = "INSERT INTO " . $DB_Prefix . "_events (Name, Description, Date, Expire) ";
    $insquery .= "VALUES ('$name', '$description', '$date', '$expiredate')";
    $insresult = mysqli_query($dblink, $insquery) or die("Unable to add. Please try again later.");
} else if ($Submit == "Edit Event") {
    $date = $eventyear . "-" . $eventmonth . "-" . $eventday;
    $expiredate = $expireyear . "-" . $expiremonth . "-" . $expireday;
    $name = addslash_mq($name);
    $description = addslash_mq($description);
    $updquery = "UPDATE " . $DB_Prefix . "_events SET Name='$name', Description='$description', Date='$date', ";
    $updquery .= "Expire='$expiredate' WHERE ID='$eventid'";
    $updresult = mysqli_query($dblink, $updquery) or die("Unable to update. Please try again later.");
} else if ($Submit == "Yes, Delete Event") {
    $delquery = "DELETE FROM " . $DB_Prefix . "_events WHERE ID='$eventid'";
    $delresult = mysqli_query($dblink, $delquery) or die("Unable to delete. Please try again later.");
}

if ($mode == "Add" OR $mode == "Edit") {
    if ($mode == "Edit") {
        $evquery = "SELECT * FROM " . $DB_Prefix . "_events WHERE ID='$eventid'";
        $evresult = mysqli_query($dblink, $evquery) or die ("Unable to select. Try again later.");
        $evrow = mysqli_fetch_row($evresult);
        $name = stripslashes($evrow[1]);
        $name = str_replace("\"", "&quot;", $name);
        $description = stripslashes($evrow[2]);
        if ($evrow[3] != 0) {
            $splitdate = explode("-", $evrow[3]);
            $thismonth = date("n", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
            $thisday = date("j", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
            $thisyear = date("Y", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
        }
        if ($evrow[4] != 0) {
            $splitdate = explode("-", $evrow[4]);
            $expmonth = date("n", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
            $expday = date("j", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
            $expyear = date("Y", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0]));
        }
    } else {
        $thismonth = date("n", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $thisday = date("j", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $thisyear = date("Y", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $expmonth = date("n", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
        $expday = date("j", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
        $expyear = date("Y", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
    }
    ?>
    <form method="POST" action="events.php">
        <div align="center">
            <center>
                <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                    <tr>
                        <td align="right" valign="top">
                            Event Name:
                        </td>
                        <td align="left" valign="top">
                            <input type="text" name="name" value="<?php echo "$name"; ?>" size="40">
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">
                            Event Date:
                        </td>
                        <td align="left" valign="top">
                            <select size="1" name="eventmonth">
                                <?php
                                for ($m = 1; $m <= 12; ++$m) {
                                    $descripmonth = date("F", mktime(0, 0, 0, date($m), date("1"), date("Y")));
                                    if ($thismonth == $m) echo "<option selected value=$m>$descripmonth</option>"; else echo "<option value=$m>$descripmonth</option>";
                                }
                                ?>
                            </select>
                            <select size="1" name="eventday">
                                <?php
                                for ($d = 1; $d <= 31; ++$d) {
                                    if ($thisday == $d) echo "<option selected value=$d>$d</option>"; else echo "<option value=$d>$d</option>";
                                }
                                ?>
                            </select>
                            <select size="1" name="eventyear">
                                <?php
                                for ($y = $thisyear - 3; $y <= $thisyear + 7; ++$y) {
                                    if ($thisyear == $y) echo "<option selected value=$y>$y</option>"; else echo "<option value=$y>$y</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">
                            Description:
                        </td>
                        <td align="left" valign="top">
                            <textarea rows="6" name="description" id="description"
                                      cols="40"><?php echo "$description"; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">
                            Expire Date:
                        </td>
                        <td align="left" valign="top">
                            <select size="1" name="expiremonth">
                                <?php
                                for ($m = 1; $m <= 12; ++$m) {
                                    $descripmonth = date("F", mktime(0, 0, 0, date($m), date("1"), date("Y")));
                                    if ($expmonth == $m) echo "<option selected value=$m>$descripmonth</option>"; else echo "<option value=$m>$descripmonth</option>";
                                }
                                ?>
                            </select>
                            <select size="1" name="expireday">
                                <?php
                                for ($d = 1; $d <= 31; ++$d) {
                                    if ($expday == $d) echo "<option selected value=$d>$d</option>"; else echo "<option value=$d>$d</option>";
                                }
                                ?>
                            </select>
                            <select size="1" name="expireyear">
                                <?php
                                for ($y = $thisyear - 3; $y <= $thisyear + 7; ++$y) {
                                    if ($expyear == $y) echo "<option selected value=$y>$y</option>"; else echo "<option value=$y>$y</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <?php
                            if ($mode == "Edit") {
                                echo "<input type=\"hidden\" value=\"$eventid\" name=\"eventid\">";
                                echo "<input type=\"submit\" class=\"button\" value=\"Edit Event\" name=\"Submit\">";
                            } else
                                echo "<input type=\"submit\" class=\"button\" value=\"Add Event\" name=\"Submit\">";
                            ?>
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <p align="center"><a href="events.php">Back to Events</a></p>

    <?php
} else if ($mode == "Delete") {
    $getevquery = "SELECT * FROM " . $DB_Prefix . "_events WHERE ID = '$eventid'";
    $getevresult = mysqli_query($dblink, $getevquery) or die ("Could not show categories. Try again later.");
    $getevrow = mysqli_fetch_row($getevresult);
    $stripevent = stripslashes($getevrow[1]);
    ?>
    <form method="POST" action="events.php">
        <div align="center">
            <center>
                <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                    <tr>
                        <td>
                            You are about to delete the following event:
                            <p align="center"><b>
                                    <?php
                                    echo "$stripevent";
                                    ?>
                                </b></p>
                            <p>Do you want to continue?</p>
                            <?php
                            echo "<input type=\"hidden\" value=\"$eventid\" name=\"eventid\">";
                            ?>
                            <input type="submit" class="button" value="Yes, Delete Event" name="Submit">&nbsp; <input
                                    type="submit" class="button" value="No, Don't Delete" name="Submit">
                        </td>
                    </tr>
                </table>
            </center>
        </div>
    </form>

    <p align="center"><a href="events.php">Back to Events</a></p>

    <?php
} else {
    $setpg_lower = "events";
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

    if ($setactive == "Yes") {
        ?>
        <form method="POST" action="events.php">
            <div align="center">
                <center>
                    <table border=0 cellpadding=0 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center">
                                <b>Events:</b>
                                <?php
                                $getevquery = "SELECT ID, Name, Date FROM " . $DB_Prefix . "_events ORDER BY Date";
                                $getevresult = mysqli_query($dblink, $getevquery) or die ("Could not show categories. Try again later.");
                                $getevnum = mysqli_num_rows($getevresult);

                                if ($getevnum == 0)
                                    echo "<i>No Events Listed.</i>";
                                else {
                                    echo "<select size=\"1\" name=\"eventid\">";
                                    for ($getevcount = 1; $getevrow = mysqli_fetch_row($getevresult); ++$getevcount) {
                                        $display = stripslashes($getevrow[1]);
                                        if ($getevrow[2] != 0) {
                                            $splitdate = explode("-", $getevrow[2]);
                                            $display .= " (" . date("n/j/y", mktime(0, 0, 0, $splitdate[1], $splitdate[2], $splitdate[0])) . ")";
                                        }
                                        echo "<option value=\"$getevrow[0]\">$display</option>";
                                    }
                                    echo "</select>";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                <input type="submit" class="button" value="Add" name="mode">
                                <?php
                                if ($getevnum != 0) {
                                    echo " <input type=\"submit\" class=\"button\" value=\"Edit\" name=\"mode\">";
                                    echo " <input type=\"submit\" class=\"button\" value=\"Delete\" name=\"mode\">";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>

        <?php
        if ($pgid)
            $setpg_lower = "<a href=\"pages.php?edit=yes&pgid=$pgid\">$setpg_lower</a>";
    }

    if ($setactive == "No") {
        ?>

        <form method="POST" action="<?php echo "$setpg.php"; ?>">
            <div align="center">
                <center>
                    <table border=0 cellpadding=3 cellspacing=0 class="generaltable">
                        <tr>
                            <td valign="middle" align="center">
                                The events page is currently inactive. If you would like to<br>activate it, please click
                                the button below.
                            </td>
                        </tr>
                        <tr>
                            <td valign="middle" align="center">
                                <input type="submit" class="button" value="Activate Events Page" name="submit">
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
        echo "<a href=\"events.php?submit=Deactivate+Events+Page\">Deactivate</a></p>";
    }

}
?>

<script language="php">
include("includes/links2.php");
include("includes/footer.htm");

</script>

</html>
