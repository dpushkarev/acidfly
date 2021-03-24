<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// If user is logged in
if ($regnum == 1)
{
$getquery = "SELECT ID, RegUser FROM " .$DB_Prefix ."_registry WHERE ID='$regrow[0]'";
$getresult = mysqli_query($dblink, $getquery) or die ("Unable to select. Try again later.");
if (mysqli_num_rows($getresult) == 1)
{
$getrow = mysqli_fetch_array($getresult);
$reg_link = "$urldir/registry.$pageext?rgid=$getrow[0]&rguser=$getrow[1]";
echo "<p>If you wish to send your friends or family a link to your registry ";
echo "so they can purchase gifts for you, use the following link:</p>";
echo "<p align=\"center\" class=\"boldtext\">";
echo "<a href=\"$reg_link\" target=\"_blank\">$reg_link</a></p>";
}
}
echo "<p align=\"center\">";
echo "<a href=\"registry.$pageext?mode=registry\">View Registry</a> | ";
echo "<a href=\"registry.$pageext?logout=yes\">Log Out</a></p>";
?>
