<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Show logo if it exists
if ($logourl)
echo "<img src=\"$logourl\" alt=\"$Site_Name\" border=\"0\" width=\"$logowidth\" height=\"$logoheight\">";
?>
