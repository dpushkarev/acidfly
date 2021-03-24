<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$getcatquery = "SELECT Category, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '' AND Active <> 'No' ORDER BY CatOrder, Category";
$getcatresult = mysqli_query($dblink, $getcatquery) or die ("Could not show categories. Try again later.");
$getcatnum = mysqli_num_rows($getcatresult);

if ($getcatnum != 0)
{
echo "<form method=\"GET\" action=\"$Catalog_Page\" style=\"margin-bottom:0;\">";
echo "<select size=\"1\" name=\"category\">";
echo "<option selected value=\"\">Select Category</option>";
for ($getcatcount = 1; $getcatrow = mysqli_fetch_row($getcatresult); ++$getcatcount)
{
$display = stripslashes($getcatrow[0]);
echo "<option value=\"$getcatrow[1]\">$display</option>";
$subcatquery = "SELECT Category, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '$getcatrow[1]' AND Active <> 'No' ORDER BY CatOrder, Category";
$subcatresult = mysqli_query($dblink, $subcatquery) or die ("Could not show categories. Try again later.");
$subcatnum = mysqli_num_rows($subcatresult);
for ($subcatcount = 1; $subcatrow = mysqli_fetch_row($subcatresult); ++$subcatcount)
{
$subdisplay = stripslashes($subcatrow[0]);
echo "<option value=\"$subcatrow[1]\">&nbsp;-&nbsp;$subdisplay</option>";
// START MULTI CATEGORY
$endcatquery = "SELECT Category, ID FROM " .$DB_Prefix ."_categories WHERE Parent = '$subcatrow[1]' AND Active <> 'No' ORDER BY CatOrder, Category";
$endcatresult = mysqli_query($dblink, $endcatquery) or die ("Could not show categories. Try again later.");
$endcatnum = mysqli_num_rows($endcatresult);
for ($endcatcount = 1; $endcatrow = mysqli_fetch_row($endcatresult); ++$endcatcount)
{
$enddisplay = stripslashes($endcatrow[0]);
echo "<option value=\"$endcatrow[1]\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;$enddisplay</option>";
}
// END MULTI CATEGORY
}
}
echo "</select> ";
if ($Search_Button)
echo "<input type=\"image\" alt=\"Search\" src=\"$Search_Button\" value=\"Search\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Go\" class=\"formbutton\">";
echo "</form>";
}
?>
