<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$pricecheckquery = "SELECT * FROM " .$DB_Prefix ."_prices WHERE StartPrice > 0 OR EndPrice > 0";
$pricecheckresult = mysql_query($pricecheckquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($pricecheckresult) > 0)
{
echo "<form method=\"GET\" action=\"$Catalog_Page\" style=\"margin-bottom:0;\">";
echo "<select name=\"price\" size=\"1\">";
echo "<option selected value=\"\">Select Price</option>";
for ($p = 1; $pricecheckrow = mysql_fetch_row($pricecheckresult); ++$p)
{
echo "<option value=\"$pricecheckrow[1]-$pricecheckrow[2]\">";
if ($pricecheckrow[1] == 0)
echo "Under $Currency$pricecheckrow[2]";
else if ($pricecheckrow[2] == 0)
echo "Over $Currency$pricecheckrow[1]";
else
echo "$Currency$pricecheckrow[1]-$Currency$pricecheckrow[2]";
echo "</option>";
}
echo "</select> ";
if ($Search_Button)
echo "<input type=\"image\" alt=\"Search\" src=\"$Search_Button\" value=\"Search\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Go\" class=\"formbutton\">";
echo "</form>";
}
?>
