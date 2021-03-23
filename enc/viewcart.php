<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($OrderProcess == "iframe")
$viewcart = "$URL/go/order.php?vc=y&return=$ReturnURL";
else
$viewcart = "$View_Page?userid=$Mals_Cart_ID&return=$ReturnURL";
if ($Cart_Button)
echo "<a href=\"$viewcart\"><img border=\"0\" src=\"$Cart_Button\" alt=\"View Your Cart\"></a>";
else
echo "<a href=\"$viewcart\" class=\"cartcolor\">View&nbsp;Your&nbsp;Cart</a>";
?>
