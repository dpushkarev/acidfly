<p>It's quick and easy to sign up for our <?php echo "$Registry"; ?> registry! Just enter
    <?php
    if (file_exists("openinfo.php"))
        die("Cannot access file directly.");

    if ($Registry == "bridal")
        echo "the bride's name (first and maiden) and groom's name (first and last), the date of the wedding, ";
    else if ($Registry == "baby")
        echo "the baby's name (if known), the names of the parents or guardians, the estimated due date (if known), ";
    else
        echo "your name, the date of the event for which you are registering (if applicable), ";
    ?>
    and a shipping address if you would like gifts to be sent directly to you. Then select a user name
    and password to use when you want to add or modify items you've added to the directory,
    choose whether you want the list to be visible only to yourself or to the general public, and
    submit to be registered.</p>
<form action="<?php echo "registry.$pageext"; ?>" method="POST">
    <table border="0" cellpadding="3" cellspacing="0" align="center">
        <tr>
            <td valign="top" align="right">
                <?php
                if ($Registry == "bridal")
                    echo "Bride's Name:";
                else if ($Registry == "baby")
                    echo "Parents' Names:";
                else
                    echo "Registered To:";
                ?>
            <td valign="top" align="left" colspan="3"><input type="text" name="name1" value="<?php echo "$name1"; ?>"
                                                             size="30"></td>
        </tr>
        <?php
        if ($Registry == "bridal" OR $Registry == "baby") {
            ?>
            <tr>
                <td valign="top" align="right">
                    <?php
                    if ($Registry == "bridal")
                        echo "Groom's Name:";
                    else if ($Registry == "baby")
                        echo "Baby's Name:";
                    ?>
                </td>
                <td valign="top" align="left" colspan="3"><input type="text" name="name2"
                                                                 value="<?php echo "$name2"; ?>" size="30"></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td valign="top" align="right">Email Address:</td>
            <td valign="top" align="left" colspan="3"><input type="text" name="regemail"
                                                             value="<?php echo "$regemail"; ?>" size="30"></td>
        </tr>
        <tr>
            <td valign="top" align="right">
                <?php
                if ($Registry == "bridal")
                    echo "Wedding Date:";
                else if ($Registry == "baby")
                    echo "Due Date:";
                else
                    echo "Event Date:";
                ?>
            </td>
            <td valign="top" align="left" colspan="3"><input type="text" name="eventdate"
                                                             value="<?php echo "$eventdate"; ?>" size="12"> (MM/DD/YY)
            </td>
        </tr>
        <tr>
            <td valign="top" align="center" colspan="4" class="accent">Only fill out address info below if you<br>
                want people ship items directly to you<br>
                and you don't mind address information<br>
                being publicly displayed on our site:
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">Ship To:</td>
            <td valign="top" align="left" colspan="3"><input type="text" name="shiptoname"
                                                             value="<?php echo "$shiptoname"; ?>" size="30"></td>
        </tr>
        <tr>
            <td valign="top" align="right">Street Address:</td>
            <td valign="top" align="left" colspan="3"><input type="text" name="shiptoaddress"
                                                             value="<?php echo "$shiptoaddress"; ?>" size="30"></td>
        </tr>
        <tr>
            <td valign="top" align="right">City:</td>
            <td valign="top" align="left" colspan="3"><input type="text" name="shiptocity"
                                                             value="<?php echo "$shiptocity"; ?>" size="30"></td>
        </tr>
        <tr>
            <td valign="top" align="right">State/Region:</td>
            <td valign="top" align="left"><input type="text" name="shiptostate" value="<?php echo "$shiptostate"; ?>"
                                                 size="6"></td>
            <td valign="top" align="right">Zip Code:</td>
            <td valign="top" align="left"><input type="text" name="shiptozip" value="<?php echo "$shiptozip"; ?>"
                                                 size="10"></td>
        </tr>
        <tr>
            <td valign="top" align="right">Country:</td>
            <td valign="top" align="left" colspan="3"><input type="text" name="shiptocountry"
                                                             value="<?php echo "$shiptocountry"; ?>" size="30"></td>
        </tr>
        <tr>
            <td valign="top" align="center" colspan="4" class="accent">Please select a user name and password to<br>
                use when adding items to the registry.
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">User Name:</td>
            <td valign="top" align="left" colspan="3"><input type="text" name="wshusr" value="<?php echo "$wshusr"; ?>"
                                                             size="10" maxlength="8"></td>
        </tr>
        <tr>
            <td valign="top" align="right">Password:</td>
            <td valign="top" align="left" colspan="3"><input type="password" name="wshpsd" size="10" maxlength="8"></td>
        </tr>
        <tr>
            <td valign="top" align="center" colspan="4" class="accent">
                Do you want your gift registry to be viewed<br>
                by visitors searching through our registry?
            </td>
        </tr>
        <tr>
            <td valign="top" align="right">Registry Type:</td>
            <td valign="top" align="left" colspan="3">
                <select size="1" name="regtype">
                    <?php
                    echo "<option ";
                    if ($regtype == "Private")
                        echo "selected ";
                    echo "value=\"Private\">Private - Not In Searches</option>";
                    echo "<option ";
                    if ($regtype == "Public")
                        echo "selected ";
                    echo "value=\"Public\">Public - Seen By Everyone</option>";
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="4" valign="top" align="center">
                <input type="hidden" name="mode" value="register">
                <?php echo "$addhidden"; ?>
                <input type="submit" value="Register" name="submit" class="formbutton">
            </td>
        </tr>
    </table>
</form>
