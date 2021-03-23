<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

include ("$Home_Path/$Inc_Dir/viewcart.php");
if (!empty($qtyvals) AND $OrderProcess != "iframe")
echo "<br>$qtyvals&nbsp;items&nbsp; $Currency" .number_format($totvals, 2);
?>