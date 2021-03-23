<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$startyear = "2004";
$thisyear = date("Y");
echo "<span class=\"smfont\">";
echo "©";
if ($startyear == $thisyear)
echo $startyear;
else
echo "$startyear - $thisyear";
echo " $Site_Name. All rights reserved.";
echo "</span>";
?>