<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Set site map?
$sitemap = "No";
$smquery = "SELECT Active FROM " .$DB_Prefix ."_pages WHERE PageName = 'sitemap' AND PageType='optional'";
$smresult = mysql_query($smquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($smresult) == 1)
{
$smrow = mysql_fetch_row($smresult);
$sitemap = $smrow[0];
}
// Set site search?
$sitesearch = "No";
$ssquery = "SELECT Active FROM " .$DB_Prefix ."_pages WHERE PageName = 'sitesearch' AND PageType='optional'";
$ssresult = mysql_query($ssquery, $dblink) or die ("Unable to select. Try again later.");
if (mysql_num_rows($ssresult) == 1)
{
$ssrow = mysql_fetch_row($ssresult);
$sitesearch = $ssrow[0];
}
echo "<p align=\"center\" class=\"lgfont\">Page Not Found</p>";
echo "<p>";
echo "Sorry, but the page you are looking for cannot be found.";
if ($sitemap == "No" AND $sitesearch == "No")
echo " If you feel you have reached this page in error, please contact us for assistance.";
else if ($sitemap == "No")
{
echo " Please use our site search below to find your page or contact us for assistance.";
include("$Home_Path/$Inc_Dir/sitesearch.php");
}
else if ($sitesearch == "No")
{
echo " Please use our site map links below to find your page or contact us for assistance.";
include("$Home_Path/$Inc_Dir/sitemap.php");
}
else
{
echo " Please use our site search or site map links below to find your page or contact us for assistance.";
include("$Home_Path/$Inc_Dir/sitesearch.php");
include("$Home_Path/$Inc_Dir/sitemap.php");
}
?>
