<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Check to see if site search is active
$showcat = "no";
$ssquery = "SELECT Active FROM " .$DB_Prefix ."_pages WHERE PageName='sitesearch' AND PageType='optional'";
$ssresult = mysql_query($ssquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($ssresult) == 0)
$showcat = "yes";
else
{
$ssrow = mysql_fetch_row($ssresult);
if ($ssrow[0] == "No")
$showcat = "yes";
}
if ($showcat == "yes")
$search_pg = $Catalog_Page;
else
$search_pg = "$URL/sitesearch.$pageext";
echo "<form method=\"POST\" action=\"$search_pg\" style=\"margin:0\">";
echo "<input type=\"text\" name=\"keyword\" size=\"12\"> ";
if ($Search_Button)
echo "<input type=\"image\" alt=\"Search\" src=\"$Search_Button\" value=\"Search\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Go\" class=\"formbutton\">";
echo "</form>";
?>
