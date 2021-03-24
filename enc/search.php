<form method="GET" action="<?php echo "$Catalog_Page"; ?>">
    <div align="center">
        <center>
            <table border="0" cellpadding="3" cellspacing="0">
                <tr>
                    <td valign="top" align="right">Keyword(s):</td>
                    <td valign="top" align="left">
                        <input type="text" name="keyword" size="32"><br>
                        <span class="smfont">
<input type="radio" name="cond" value="advand" checked>All Words 
<input type="radio" name="cond" value="advor">Any Word 
<input type="radio" name="cond" value="advphrase">Exact Phrase</span></td>
                </tr>
                <tr>
                    <td valign="top" align="right">Category:</td>
                    <td valign="top" align="left">
                        <select size="1" name="category">
                            <option selected value="">All Categories</option>
                            <?php
                            if (file_exists("openinfo.php"))
                                die("Cannot access file directly.");

                            $getcatquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '' AND Active <> 'No' ORDER BY CatOrder, Category";
                            $getcatresult = mysqli_query($dblink, $getcatquery) or die ("Could not show categories. Try again later.");
                            for ($getcatcount = 1; $getcatrow = mysqli_fetch_row($getcatresult); ++$getcatcount) {
                                $display = stripslashes($getcatrow[0]);
                                echo "<option value=\"$getcatrow[1]\">$display</option>";
                                $subcatquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$getcatrow[1]' AND Active <> 'No' ORDER BY CatOrder, Category";
                                $subcatresult = mysqli_query($dblink, $subcatquery) or die ("Could not show categories. Try again later.");
                                $subcatnum = mysqli_num_rows($subcatresult);
                                for ($subcatcount = 1; $subcatrow = mysqli_fetch_row($subcatresult); ++$subcatcount) {
                                    $subdisplay = stripslashes($subcatrow[0]);
                                    echo "<option value=\"$subcatrow[1]\">&nbsp;-&nbsp;$subdisplay</option>";
// START MULTI CATEGORY
                                    $endcatquery = "SELECT Category, ID FROM " . $DB_Prefix . "_categories WHERE Parent = '$subcatrow[1]' AND Active <> 'No' ORDER BY CatOrder, Category";
                                    $endcatresult = mysqli_query($dblink, $endcatquery) or die ("Could not show categories. Try again later.");
                                    $endcatnum = mysqli_num_rows($endcatresult);
                                    for ($endcatcount = 1; $endcatrow = mysqli_fetch_row($endcatresult); ++$endcatcount) {
                                        $enddisplay = stripslashes($endcatrow[0]);
                                        echo "<option value=\"$endcatrow[1]\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;$enddisplay</option>";
                                    }
// END MULTI CATEGORY
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <?php
                $pricecheckquery = "SELECT * FROM " . $DB_Prefix . "_prices WHERE StartPrice > 0 OR EndPrice > 0";
                $pricecheckresult = mysqli_query($dblink, $pricecheckquery) or die ("Unable to select. Try again later.");
                if (mysqli_num_rows($pricecheckresult) > 0) {
                    ?>
                    <tr>
                        <td valign="top" align="right">Price Range:</td>
                        <td valign="top" align="left">
                            <select name="price" size="1">
                                <option selected value="">All Prices</option>
                                <?php
                                $pricecheckquery = "SELECT * FROM " . $DB_Prefix . "_prices WHERE StartPrice > 0 OR EndPrice > 0";
                                $pricecheckresult = mysqli_query($dblink, $pricecheckquery) or die ("Unable to select. Try again later.");
                                if (mysqli_num_rows($pricecheckresult) > 0) {
                                    for ($p = 1; $pricecheckrow = mysqli_fetch_row($pricecheckresult); ++$p) {
                                        echo "<option value=\"$pricecheckrow[1]-$pricecheckrow[2]\">";
                                        if ($pricecheckrow[1] == 0)
                                            echo "Under $Currency$pricecheckrow[2]";
                                        else if ($pricecheckrow[2] == 0)
                                            echo "Over $Currency$pricecheckrow[1]";
                                        else
                                            echo "$Currency$pricecheckrow[1]-$Currency$pricecheckrow[2]";
                                        echo "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td valign="top" align="center" colspan="2">
                        <input type="submit" value="Search" class="formbutton">
                    </td>
                </tr>
            </table>
        </center>
    </div>
</form>
