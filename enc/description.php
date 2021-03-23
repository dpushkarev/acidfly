<?php
if (file_exists("openinfo.php"))
die("Cannot access file directly.");

if ($Catalog_Description)
{
if (strstr($Catalog_Description, '<') == FALSE AND strstr($Catalog_Description, '>') == FALSE)
{
$Catalog_Description = str_replace ("\r", "<br>", $Catalog_Description);
echo "<p>$Catalog_Description</p>";
}
else
echo "$Catalog_Description";
}
?>
