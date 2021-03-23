<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

$catalogproducts = $indexproducts;
include ("$Inc_Dir/catalog.php");
?>
