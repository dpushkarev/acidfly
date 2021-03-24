<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$catalogquery = "SELECT Catalog FROM " .$DB_Prefix ."_items WHERE Catalog <> ''";
if (!$wspass AND !$wsemail AND $wsnum != 1)
$catalogquery .= " AND WSOnly = 'No'";
$catalogquery .= " GROUP BY Catalog ORDER BY Catalog";
$catalogresult = mysqli_query($dblink, $catalogquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($catalogresult) > 0)
{
echo "<form method=\"GET\" action=\"$Catalog_Page\" style=\"margin-bottom:0;\">";
echo "<select name=\"catalog\" size=\"1\">";
echo "<option selected value=\"\">Select Item #</option>";
for ($c = 1; $catalogrow = mysqli_fetch_row($catalogresult); ++$c)
{
echo "<option value=\"$catalogrow[0]\">$catalogrow[0]</option>";
}
echo "</select> ";
if ($Search_Button)
echo "<input type=\"image\" alt=\"Search\" src=\"$Search_Button\" value=\"Search\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Go\" class=\"formbutton\">";
echo "</form>";
}
?>
