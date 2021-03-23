<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

echo "<form method=\"GET\" action=\"$URL\" style=\"margin-bottom:0;\">";
echo "Enter Coupon Code: ";
echo "<input type=\"text\" name=\"code\" size=\"12\"> ";
if ($Search_Button)
echo "<input type=\"image\" alt=\"Go\" src=\"$Search_Button\" value=\"Go\" align=\"middle\">";
else
echo "<input type=\"submit\" value=\"Go\" class=\"formbutton\">";
echo "</form>";
?>
