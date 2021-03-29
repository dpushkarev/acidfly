<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($changetheme)
$sc = "?changetheme=$changetheme";
else
$sc = "";
echo "<link href=\"$urldir/$Inc_Dir/stylecss.php$sc\" rel=\"stylesheet\" type=\"text/css\">\r\n";
?>
