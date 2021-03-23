<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

// Display header image if it exists
if ($Header_Image)
echo "<img src=\"$Header_Image\" alt=\"$pagetitle\" border=\"0\">";
else if ($pagetitle)
echo "<span class=\"lgfont\">$pagetitle</span>";
?>