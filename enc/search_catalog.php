<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

echo "<form method=\"POST\" action=\"$Catalog_Page\" style=\"margin:0\">";
echo "<input type=\"text\" name=\"keyword\" size=\"12\"> ";
if ($Search_Button)
echo "<input type=\"image\" alt=\"Search\" src=\"$Search_Button\" value=\"Search\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Go\" class=\"formbutton\">";
echo "</form>";
?>
