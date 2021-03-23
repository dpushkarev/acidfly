<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Display site message if it exists
if ($Site_Message)
echo "<p>$Site_Message</p>";
?>
