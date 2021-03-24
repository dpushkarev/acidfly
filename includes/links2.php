</td>
<td width="1" valign="top" class="verticalline"><img src="images/spacer.gif" width="1" height="1"></td>
<td width="120" valign="top" class="padded">
    <?php
    include_once("../stconfig.php");
    $linkheadings = array("Site Extras", "Administration");
    foreach ($group as $linkval => $linkset) {
        if ($linkval >= $linkend) {
            $lkquery = "SELECT * FROM " . $DB_Prefix . "_permissions WHERE ";
            if ($set_master_key == "no")
                $lkquery .= "GivePermission = 'Yes' AND ";
            $lkquery .= "SiteGroup = '$linkset' ORDER BY ID";
            $lkresult = mysqli_query($dblink, $lkquery) or die ("Unable to select links.");
            if (mysqli_num_rows($lkresult) > 0) {
                echo "<p><b>$linkset</b>";
                for ($lk = 1; $lkrow = mysqli_fetch_array($lkresult); ++$lk) {
                    $featurename = str_replace(" ", "&nbsp;", stripslashes($lkrow[Feature]));
                    if ($setpg == $lkrow[SetPg])
                        echo "<br><a href=\"$lkrow[SetPg].php\" class=\"linkset\">$featurename</a>";
                    else
                        echo "<br><a href=\"$lkrow[SetPg].php\" class=\"links\">$featurename</a>";
                }
                echo "<br><img src=\"images/spacer.gif\" width=\"120\" height=\"1\">";
                echo "</p>";
            }
        }
    }
    ?>
</td>
</tr>
<tr>
    <td colspan="5" align="center">
        <?php
        echo "&nbsp;<br><a href=\"index.php";
        if (isset($_COOKIE['adminmstr']) AND $_COOKIE['adminmstr'] == $Master_Key)
            echo "?master=y";
        echo "\">Log Out</a>";
        ?>
    </td>
</tr>
</table>