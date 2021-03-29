<?php
if (file_exists("openinfo.php"))
    die("Cannot access file directly.");

if ($Meta_Title)
    $site_title = str_replace('"', '', $Meta_Title);
else
    $site_title = str_replace('"', '', $Site_Name);
echo "$site_title";
?>
